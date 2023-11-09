<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('swal-css') ?>">
    <title>Marcación de usuario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 400px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            display: block;
            text-align: left;
            font-size: 16px;
            color: #333;
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
            background-color: black;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-logout {
            background-color: #f34336;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .buttons-container {
            text-align: center;
        }

        .buttons-container button {
            margin: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Marcación de Usuario</h1>
        <form id="marcacion-create">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
            <button class="btn" type="submit">Enviar</button>
        </form>
    </div>
    <div class="buttons-container">
        <button class="btn-logout" id="logout-button">Logout</button>
        <button class="btn" id="edit-profile-button">Editar Mi Perfil</button>
    </div>
</body>

</html>

<script type="text/javascript" src="<?= base_url('swal-js') ?>"></script>
<script>
    const jwtToken = localStorage.getItem('jwtToken');

    if (jwtToken === null || jwtToken === undefined) {
        window.location.href = '/';
    }
    if (jwtToken) {
        const tokenPayload = jwtToken.split('.')[1];
        const decodedPayload = JSON.parse(atob(tokenPayload));
        if (decodedPayload.rol != 'user') {
            window.location.href = '/';
        }
    }
    document.getElementById('marcacion-create').addEventListener('submit', function (event) {
        event.preventDefault();

        const nombre = document.querySelector('#nombre').value;
        const email = document.querySelector('#email').value;

        fetch('http://localhost:8080/marcacion/create', {
            method: 'POST',
            headers: {
                'Authorization': jwtToken
            },
            body: JSON.stringify({ nombre, email })
        }).then(response => {
            response.json().then(data => {
                if (response.status === 200 && data.mensaje === 'Marcación guardada') {
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "Marcacion registrada",
                        showConfirmButton: false,
                        timer: 1500
                    });


                    document.querySelector('#nombre').value = '';
                    document.querySelector('#email').value = '';
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

    document.getElementById('logout-button').addEventListener('click', function () {
        localStorage.removeItem('jwtToken')

        window.location.href = '';
    });

    document.getElementById('edit-profile-button').addEventListener('click', function () {
        window.location.href = 'edit-profile'
    });
</script>