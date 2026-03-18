<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('alumno');

        if ($userId = $request->query('user_id')) {
            $query->where('id', $userId);
        }

        if ($rol = $request->query('rol')) {
            $query->where('rol', $rol);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'rol' => ['required', 'in:superadmin,admin,alumno'],
            'password' => ['nullable', 'string', 'min:6'],
            'rut' => ['nullable', 'string', 'max:12'],
            'curso' => ['nullable', 'string', 'max:255'],
            'nivel_actual' => ['nullable', 'in:3,4'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'rol' => $data['rol'],
            'password' => bcrypt($data['password'] ?? Str::random(12)),
        ]);

        if ($user->rol === 'alumno') {
            Alumno::create([
                'user_id' => $user->id,
                'rut' => $data['rut'] ?? '',
                'nombre' => $data['name'],
                'apellido' => $data['apellido'],
                'curso' => $data['curso'] ?? null,
                'nivel_actual' => $data['nivel_actual'] ?? '3',
            ]);
        }

        return response()->json($user->load('alumno'), 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'rol' => ['required', 'in:superadmin,admin,alumno'],
            'password' => ['nullable', 'string', 'min:6'],
            'rut' => ['nullable', 'string', 'max:12'],
            'curso' => ['nullable', 'string', 'max:255'],
            'nivel_actual' => ['nullable', 'in:3,4'],
        ]);

        $user->name = $data['name'];
        $user->apellido = $data['apellido'];
        $user->email = $data['email'];
        $user->rol = $data['rol'];

        if (! empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        if ($user->rol === 'alumno') {
            Alumno::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'rut' => $data['rut'] ?? '',
                    'nombre' => $data['name'],
                    'apellido' => $data['apellido'],
                    'curso' => $data['curso'] ?? null,
                    'nivel_actual' => $data['nivel_actual'] ?? '3',
                ]
            );
        }

        return response()->json($user->load('alumno'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado']);
    }
}
