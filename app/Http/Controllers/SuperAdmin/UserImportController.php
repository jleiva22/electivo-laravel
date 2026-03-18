<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserImportController extends Controller
{
    public function index()
    {
        return view('superadmin.import-users');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        try {
            $content = file_get_contents($request->file('file')->getRealPath());

            if ($content === false) {
                return response()->json(['message' => 'No se pudo leer el archivo'], 400);
            }

            // Eliminar BOM UTF-8 si existe
            $content = ltrim($content, "\xEF\xBB\xBF");

            // Convertir a UTF-8 si viene en ISO-8859-1 (por defecto en Excel)
            if (!mb_check_encoding($content, 'UTF-8')) {
                $content = mb_convert_encoding($content, 'UTF-8', 'ISO-8859-1');
            }

            $lines = array_values(array_filter(
                preg_split('/\r\n|\r|\n/', $content),
                fn($l) => trim($l) !== ''
            ));

            if (!count($lines)) {
                return response()->json(['message' => 'El archivo está vacío'], 400);
            }

            $delimiter = substr_count($lines[0], ';') > substr_count($lines[0], ',') ? ';' : ',';

            $headers        = null;
            $created        = 0;
            $skipped        = 0;
            $invalidEmails  = 0;
            $skippedDetails = [];

            foreach ($lines as $lineIndex => $line) {
                $row = array_map('trim', str_getcsv($line, $delimiter));

                if (empty(array_filter($row))) {
                    continue;
                }

                    // Detectar fila de encabezados
                if ($headers === null) {
                    $normalized = array_map(fn($c) => mb_strtolower(trim($c)), $row);

                    // Construir un mapa índice -> campo esperado
                    $mapping = [];
                    foreach ($normalized as $idx => $col) {
                        $col = str_replace([' ', '_', '\t'], '', $col);
                        $col = str_replace(['á', 'é', 'í', 'ó', 'ú', 'ñ'], ['a', 'e', 'i', 'o', 'u', 'n'], $col);

                        if (in_array($col, ['run', 'rut'], true)) {
                            $mapping[$idx] = 'run';
                        } elseif (in_array($col, ['apellidopaterno', 'paterno', 'ap'], true)) {
                            $mapping[$idx] = 'apellido_paterno';
                        } elseif (in_array($col, ['apellidomaterno', 'materno'], true)) {
                            $mapping[$idx] = 'apellido_materno';
                        } elseif (in_array($col, ['nombres', 'nombre'], true)) {
                            $mapping[$idx] = 'nombres';
                        } elseif (in_array($col, ['email', 'correo', 'mail'], true)) {
                            $mapping[$idx] = 'email';
                        }
                    }

                    // Si encontramos al menos run + email, asumimos encabezado
                    if (in_array('run', $mapping, true) && in_array('email', $mapping, true)) {
                        $headers = $mapping;
                        continue;
                    }
                }

                // Mapear columnas (usa el map si existe, si no, cae en fallback por posición)
                if ($headers) {
                    $data = [];
                    foreach ($headers as $idx => $field) {
                        $data[$field] = $row[$idx] ?? null;
                    }
                } else {
                    $data = [
                        'run'              => $row[1] ?? null,
                        'apellido_paterno' => $row[2] ?? null,
                        'apellido_materno' => $row[3] ?? null,
                        'nombres'          => $row[4] ?? null,
                        'email'            => $row[5] ?? null,
                    ];
                }

                $run     = $data['run']              ?? $data['rut']              ?? null;
                $paterno = $data['apellido paterno'] ?? $data['apellido_paterno'] ?? null;
                $materno = $data['apellido materno'] ?? $data['apellido_materno'] ?? null;
                $nombres = $data['nombres']          ?? null;
                $email   = trim(strtolower($data['email'] ?? ''));

                // Validar campos obligatorios para el SP y tabla alumnos
                $missing = [];
                if (! $run) {
                    $missing[] = 'run';
                }
                if (! $nombres) {
                    $missing[] = 'nombres';
                }
                if (! $paterno) {
                    $missing[] = 'apellido_paterno';
                }
                if (! $materno) {
                    $missing[] = 'apellido_materno';
                }
                if (! $email) {
                    $missing[] = 'email';
                }

                if (count($missing)) {
                    $skipped++;
                    if (count($skippedDetails) < 10) {
                        $skippedDetails[] = [
                            'line'    => $lineIndex + 1,
                            'missing' => $missing,
                            'raw'     => $row,
                        ];
                    }
                    continue;
                }

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Si el email no es válido, intentamos limpiarlo de espacios y comillas.
                    $email = trim($email, " \t\n\r\0\x0B\"'");

                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $invalidEmails++;
                        $skipped++;
                        if (count($skippedDetails) < 10) {
                            $skippedDetails[] = [
                                'line'   => $lineIndex + 1,
                                'reason' => 'email_invalido',
                                'email'  => $email,
                            ];
                        }
                        continue;
                    }
                }

                // Preparar password desde RUT
                $rutClean      = preg_replace('/[^0-9kK]/', '', $run);
                $rutDigits     = preg_replace('/[^0-9]/', '', $rutClean);
                $passwordPlain = substr($rutDigits, 0, 4) ?: Str::random(4);

                // Llamar al SP — la verificación de duplicado está dentro del SP
                DB::statement('CALL sp_importar_alumno(?, ?, ?, ?, ?, ?)', [
                    $email,
                    $rutClean,
                    trim($nombres),
                    trim($paterno),
                    trim($materno),
                    bcrypt($passwordPlain),
                ]);

                $created++;
            }

            return response()->json([
                'created'         => $created,
                'skipped'         => $skipped,
                'invalid_emails'  => $invalidEmails,
                'skipped_details' => $skippedDetails,
                'message'         => "Usuarios importados: {$created}. Filas omitidas: {$skipped} (emails inválidos: {$invalidEmails}).",
            ]);

        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (!mb_check_encoding($msg, 'UTF-8')) {
                $msg = mb_convert_encoding($msg, 'UTF-8', 'ISO-8859-1');
            }
            return response()->json([
                'message' => 'Error al importar: ' . $msg,
            ], 500);
        }
    }
}