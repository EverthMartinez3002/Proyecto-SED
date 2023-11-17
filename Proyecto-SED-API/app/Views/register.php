<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('swal-css') ?>">
    <title>Registro - Sistema de Marcación de Asistencia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            padding: 20px;
            max-width: 600px;
        }

        h1 {
            color: #333;
            font-size: 36px;
            margin-bottom: 20px;
        }

        p {
            color: #333;
            font-size: 18px;
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            color: #333;
            font-size: 16px;
            margin-bottom: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .btn {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Registro sistema de marcación de asistencia</h1>
        <p>Por favor, complete el formulario de registro:</p>
        <form id="register-form">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" required>
            <label for="apellido">Apellido:</label>
            <input type="text" id="apellido" name="apellido" required>
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="contrasena">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" required>
            <label for="rol">Rol:</label>
            <select id="rol" name="rol" required>
                <option value="user">Usuario</option>
                <option value="admin">Admin</option>
                <option value="superadmin">SuperAdmin</option>
            </select>
            <button class="btn" type="submit">Registrarse</button>
        </form>
    </div>
</body>

</html>

<script type="text/javascript" src="<?= base_url('swal-js') ?>"></script>
<script>
    document.getElementById('fecha_nacimiento').addEventListener('change', function () {
        const fechaNacimiento = new Date(this.value);
        const hoy = new Date();
        const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();

        const botonGuardar = document.querySelector('.btn');
        if (edad < 18) {
            botonGuardar.disabled = true;
            botonGuardar.classList.add('btn-disabled');
            alert('Debes ser mayor de 18 años para continuar.');
        } else {
            botonGuardar.disabled = false;
            botonGuardar.classList.remove('btn-disabled');
        }
    });

    document.getElementById('register-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const nombre = document.getElementById('nombre').value;
        const apellido = document.getElementById('apellido').value;
        const email = document.getElementById('email').value;
        const contrasena = document.getElementById('contrasena').value;
        const fecha_nacimiento = document.getElementById('fecha_nacimiento').value;
        const direccion = document.getElementById('direccion').value;
        const rol = document.getElementById('rol').value;

        const regex = /^(?=.*[A-Z])(?=.*[!@#$%^&*])(.{8,})$/;
        if (!regex.test(contrasena)) {
            alert('La contraseña debe tener al menos 8 caracteres, una mayúscula y un carácter especial.');
            return;
        }

var fetchURL = '<?php echo base_url('user/create') ?>';
        fetch(fetchURL, {
            method: 'POST',
            body: JSON.stringify({ nombre, apellido, email, contrasena, fecha_nacimiento, direccion, rol })
        }).then(response => {
            response.json().then(data => {
                if (response.status === 200) {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Registro exitoso",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        document.getElementById('nombre').value = '';
                        document.getElementById('apellido').value = '';
                        document.getElementById('email').value = '';
                        document.getElementById('contrasena').value = '';
                        document.getElementById('fecha_nacimiento').value = '';
                        document.getElementById('direccion').value = '';
                        document.getElementById('rol').value = '';

                        window.location.href = '/login'
                    });
                }

                if (response.status === 400) {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "El correo ya esta en uso",
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            })
        })
    });
</script>
