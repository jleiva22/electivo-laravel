DROP PROCEDURE IF EXISTS sp_importar_alumno;
CREATE PROCEDURE sp_importar_alumno(
    IN p_email VARCHAR(150),
    IN p_rut VARCHAR(12),
    IN p_nombres VARCHAR(255),
    IN p_ap_paterno VARCHAR(255),
    IN p_ap_materno VARCHAR(255),
    IN p_password_hash VARCHAR(255)
)
BEGIN
    DECLARE v_user_id   INT;
    DECLARE v_apellido  VARCHAR(255);
    DECLARE v_existe    INT DEFAULT 0;

    -- Verificar email duplicado
    SELECT COUNT(*) INTO v_existe
    FROM users
    WHERE email = p_email;

    IF v_existe = 0 THEN

        SET v_apellido = TRIM(CONCAT(p_ap_paterno, ' ', p_ap_materno));

        INSERT INTO users (name, apellido, email, password, rol)
        VALUES (
            TRIM(p_nombres),
            v_apellido,
            p_email,
            p_password_hash,
            'alumno'
        );

        SET v_user_id = LAST_INSERT_ID();

        INSERT INTO alumnos (user_id, rut, nombre, apellido, nivel_actual)
        VALUES (
            v_user_id,
            p_rut,
            TRIM(p_nombres),
            v_apellido,
            '3'
        );

    END IF;
END
