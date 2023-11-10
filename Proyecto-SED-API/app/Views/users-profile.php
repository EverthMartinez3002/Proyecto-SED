<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('swal-css') ?>">
    <title>Editar perfil de usuarios</title>
</head>

<body>
    <!DOCTYPE html>
    <html lang="en">

    <head>
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
                font-size: 30px;
                margin-bottom: 15px;
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
            <h1>Editar perfil</h1>
            <form id="update-user">
                <input type="hidden" name="usuario_id" value>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre">
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido">
                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion">
                <button class="btn" type="submit">Guardar Cambios</button>
            </form>
        </div>
    </body>

    </html>

    <script type="text/javascript" src="<?= base_url('swal-js') ?>"></script>
    <script>
        const jwtToken = localStorage.getItem('jwtToken');

        if (jwtToken === null || jwtToken === undefined) {
            window.location.href = '/';
        }

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

        const form = document.querySelector('form');

        fetch('http://localhost:8080/user/id', {
            method: 'GET',
            headers: {
                'Authorization': jwtToken
            },
        }).then(response => {
            if (response.status === 200) {
                return response.json();
            }
        }).then(data => {
            form.usuario_id.value = data.usuario.usuario_id;
            form.nombre.value = data.usuario.nombre;
            form.apellido.value = data.usuario.apellido;
            form.fecha_nacimiento.value = data.usuario.fecha_nacimiento;
            form.direccion.value = data.usuario.direccion;
        }).catch(error => {
        })

        document.getElementById('update-user').addEventListener('submit', function (event) {
            event.preventDefault();

            const nombre = document.querySelector('#nombre').value;
            const apellido = document.querySelector('#apellido').value;
            const fecha_nacimiento = document.querySelector('#fecha_nacimiento').value;
            const direccion = document.querySelector('#direccion').value;

            fetch('http://localhost:8080/user/edit', {
                method: 'POST',
                headers: {
                    'Authorization': jwtToken
                },
                body: JSON.stringify({ nombre, apellido, fecha_nacimiento, direccion })
            }).then(response => {
                response.json().then(data => {
                    if (response.status === 200) {
                        const tokenPayload = jwtToken.split('.')[1];
                        const decodedPayload = JSON.parse(atob(tokenPayload));

                        if (decodedPayload.rol === 'user') {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Perfil editado con exito",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '/user';
                            });
                        }
                        if (decodedPayload.rol === 'admin' || decodedPayload.rol === 'superadmin') {
                            Swal.fire({
                                position: "center",
                                icon: "success",
                                title: "Perfil editado con exito",
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.href = '/admin'
                            })
                        }
                    } else {
                        if (data.mensaje === 'No se ha modificado ningun dato') {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: "No se modifico ningun dato",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            Swal.fire({
                                position: "center",
                                icon: "error",
                                title: "Error al actualizar el perfil",
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                })
            })
        });
    </script>