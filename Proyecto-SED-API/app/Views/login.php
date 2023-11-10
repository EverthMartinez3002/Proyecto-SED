<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('swal-css') ?>">
    <title>Iniciar Sesión - Sistema de Marcación de Asistencia</title>
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
            max-width: 400px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
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
    </style>
</head>

<body>
    <div class="container">
        <h1>Iniciar sesión</h1>
        <form id="login-form">
            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Contraseña:</label>
            <input type="password" id="contrasena" name="contrasena" required>
            <button class="btn" type="submit">Iniciar Sesión</button>
        </form>
    </div>
</body>

</html>

<script type="text/javascript" src="<?= base_url('swal-js') ?>"></script>
<script>
    document.getElementById('login-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const email = document.querySelector('#email').value;
        const contrasena = document.querySelector('#contrasena').value;

        fetch('http://localhost:8080/user/login', {
            method: 'POST',
            body: JSON.stringify({ email, contrasena })
        }).then(response => {
            response.json().then(data => {
                if (data.token) {
                    const tokenPayload = data.token.split('.')[1];
                    const decodedPayload = JSON.parse(atob(tokenPayload));
                    localStorage.setItem('jwtToken', data.token);

                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Login exitoso",
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        document.querySelector('#email').value = '';
                        document.querySelector('#contrasena').value = '';

                        if (decodedPayload.rol === 'admin') {
                            window.location.href = '/admin';
                        }
                        if (decodedPayload.rol === 'superadmin') {
                            window.location.href = '/admin';
                        }
                        if (decodedPayload.rol === "user") {
                            window.location.href = '/user';
                        }
                    })
                } else {
                    Swal.fire({
                        position: "center",
                        icon: "error",
                        title: "Credenciales incorrectas",
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            })
        })
    });
</script>