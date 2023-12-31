<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?= base_url('swal-css') ?>">
    <title>Editar roles</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        #usuarios-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-around;
        }

        .card {
            border: 2px solid #ccc;
            padding: 10px;
            height: 130px;
            width: 200px;
            text-align: center;
            background-color: #fff;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .user {
            background-color: white;
        }

        .admin {
            background-color: aliceblue;
        }

        p {
            margin: 5px 0;
            padding: 3px;
        }

        button {
            background-color: black;
            color: #fff;
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .pagination {
            margin-top: 15px;
            text-align: center;
        }

        button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <h1>Usuarios</h1>
    <div id="usuarios-container"></div>
    <div class="pagination" id="pagination">
        <button id="button-back" disabled>Anterior</button>
        <button id="button-next" disabled>Siguiente</button>
    </div>
</body>

</html>

<script type="text/javascript" src="<?= base_url('swal-js') ?>"></script>
<script>
    const jwtToken = localStorage.getItem('jwtToken');
    if (jwtToken) {
        const tokenPayload = jwtToken.split('.')[1];
        const decodedPayload = JSON.parse(atob(tokenPayload));
        if (decodedPayload.rol != 'superadmin') {
            window.location.href = '/';
        }
    }

    const usuariosContainer = document.getElementById('usuarios-container');
    const paginationContainer = document.getElementById('pagination');
    const backButton = document.getElementById('button-back');
    const nextButton = document.getElementById('button-next');
    let currentPage = 1;
    let totalPaginas = 1;
    let page = 1;
    var baseUrl = '<?php echo base_url('user/all') ?>';
    var queryParams = 'page=';
    var fetchUrl1 = baseUrl + '?' + queryParams;

    function fetchUsuarios(page) {
        usuariosContainer.innerHTML = '';

        fetch(fetchUrl1 + `${page}`, {
            method: 'GET',
            headers: {
                'Authorization': jwtToken
            },
        }).then(response => {
            if (response.status === 200) {
                return response.json();
            }
        }).then(data => {
            totalPaginas = data.totalPaginas;

            if (data.usuarios.length > 0) {
                data.usuarios.forEach(usuario => {
                    const card = document.createElement('div');
                    card.className = 'card';

                    card.classList.add(usuario.rol.toLowerCase());

                    const idElement = document.createElement('p');
                    idElement.textContent = `ID: ${usuario.usuario_id}`;

                    const nombreElement = document.createElement('p');
                    nombreElement.textContent = `Nombre: ${usuario.nombre}`;

                    const rolElement = document.createElement('p');
                    rolElement.textContent = `Rol: ${usuario.rol}`;

                    const modificarRolButton = document.createElement('button');
                    modificarRolButton.textContent = 'Modificar Rol';
                    modificarRolButton.id = 'modificarRolButton';

                    card.appendChild(idElement);
                    card.appendChild(nombreElement);
                    card.appendChild(rolElement);
                    card.appendChild(modificarRolButton);

                    usuariosContainer.appendChild(card);

                    modificarRolButton.addEventListener('click', () => {
                        Swal.fire({
                            title: "¿Quieres modificar el rol de este usuario?",
                            showDenyButton: true,
                            confirmButtonText: "Modificar",
                            denyButtonText: "Cancelar",
                        }).then((result) => {
                            if (result.isConfirmed) {
                                const usuarioId = cardUser_id(modificarRolButton);
                                if (usuarioId) {
                                    modificarRol(usuarioId);
                                }
                            }
                        })
                    });

                });

                function cardUser_id(button) {
                    const card = button.closest('.card');
                    if (card) {
                        const idElement = card.querySelector('p');
                        if (idElement) {
                            const idText = idElement.textContent.trim();
                            const idParts = idText.split(': ');
                            if (idParts.length === 2) {
                                return idParts[1];
                            }
                        }
                    }
                    return null;
                }

		var fetchUrl2 = '<?php echo base_url('user/rol') ?>';

                function modificarRol(usuarioId) {
                    fetch(fetchUrl2, {
                        method: 'POST',
                        headers: {
                            'Authorization': jwtToken
                        },
                        body: JSON.stringify({ id: usuarioId }),
                    }).then(response => {
                        if (response.status === 200) {
                            Swal.fire('Modificado', 'El rol del usuario ha sido modificado con éxito', 'success');
                        } else {
                            Swal.fire('Error', 'Hubo un error al modificar el rol del usuario', 'error');
                        }
                    }).catch(error => {

                    });
                }

                backButton.disabled = currentPage === 1;
                nextButton.disabled = currentPage === data.totalPaginas;

            } else {
                usuariosContainer.innerHTML = '<p>No se encontraron usuarios.</p>';
            }
        }).catch(error => {
            console.error('Error al cargar usuarios:', error);
        });
    }

    currentPage = page;

    fetchUsuarios(currentPage)
    backButton.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            fetchUsuarios(currentPage);
        } else {
            backButton.disabled = true;
            console.log('Estás en la primera página');
        }
    });

    nextButton.addEventListener('click', () => {
        if (currentPage < totalPaginas) {
            currentPage++;
            fetchUsuarios(currentPage);
        }
    });
</script>
