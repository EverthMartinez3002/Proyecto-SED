<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registros</title>
</head>

<style>
    .cards {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    .card {
        width: 300px;
        margin: 10px;
        border-radius: 5px;
        background-color: #f0f0f0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .card-header {
        font-size: 1.2em;
        font-weight: bold;
        background-color: #333;
        color: #fff;
        padding: 10px;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }

    .card-body {
        padding: 2px;
    }

    h1 {
        font-family: 'Arial', sans-serif;
        text-align: center;
        font-size: 28px;
        color: #333;
        margin-bottom: 20px;
    }

    .tipo-marcacion {
        font-size: 1.2em;
        font-weight: bold;
    }

    .fecha-marcacion {
        font-size: 1em;
    }

    .pagination {
        margin-top: 15px;
        text-align: center;
    }

    #button-back {
        background-color: black;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        margin: 0 5px;
        cursor: pointer;
        font-size: 16px;
    }

    #button-next {
        background-color: black;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        margin: 0 5px;
        cursor: pointer;
        font-size: 16px;
    }

    #button-back:disabled,
    #button-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-logout {
        background-color: #f34336;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 5em;
        margin-bottom: 1em;
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
        <h1>Marcaciones</h1>
        <div class="buttons-container">
            <button class="btn-logout" id="logout-button">Logout</button>
            <button class="btn" id="edit-profile-button">Editar Mi Perfil</button>
        </div>
        <div class="cards"></div>
        <div class="pagination" id="pagination">
            <button id="button-back">Anterior</button>
            <button id="button-next">Siguiente</button>
        </div>
    </div>
</body>

</html>

</html>

<script>
    const jwtToken = localStorage.getItem('jwtToken');
    if (jwtToken === null || jwtToken === undefined) {
        window.location.href = '/';
    }
    if (jwtToken) {
        const tokenPayload = jwtToken.split('.')[1];
        const decodedPayload = JSON.parse(atob(tokenPayload));
        if (decodedPayload.rol != 'admin') {
            window.location.href = '/';
        }
    }

    const paginationContainer = document.getElementById('pagination');
    const cardsContainer = document.querySelector('.cards');
    const h1Title = document.querySelector('.h1-title');

    let registros = [];
    const registrosPorPagina = 9;
    let paginaActual = 1;

    const mostrarRegistros = () => {
        while (cardsContainer.firstChild) {
            cardsContainer.removeChild(cardsContainer.firstChild);
        }



        registros.forEach(registro => {
            const card = document.createElement('div');
            card.className = 'card';

            const cardHeader = document.createElement('div');
            cardHeader.className = 'card-header';
            cardHeader.textContent = `Usuario id: ${registro.usuario_id} `;

            const cardBody = document.createElement('div');
            cardBody.className = 'card-body';

            const tipoMarcacion = document.createElement('p');
            tipoMarcacion.textContent = `Tipo de marcación: ${registro.tipo_marcacion}`;

            const fechaMarcacion = document.createElement('p');
            fechaMarcacion.textContent = `Fecha: ${registro.fecha_marcacion}`;

            const horaMarcacion = document.createElement('p');
            horaMarcacion.textContent = `Hora: ${registro.hora_marcacion}`;

            cardBody.appendChild(tipoMarcacion);
            cardBody.appendChild(fechaMarcacion);
            cardBody.appendChild(horaMarcacion);

            card.appendChild(cardHeader);
            card.appendChild(cardBody);

            cardsContainer.appendChild(card);
        });
    };

    fetchRegistros = (page) => {
        fetch(`http://localhost:8080/marcacion/?page=${page}`, {
            method: 'GET',
            headers: {
                'Authorization': jwtToken
            }
        }).then(response => response.json())
            .then(data => {
                registros = data.registros;

                registros.forEach(registro => {
                    const horaMarcacionParts = registro.hora_marcacion.split(":");
                    const horaMarcacion = new Date();
                    horaMarcacion.setHours(parseInt(horaMarcacionParts[0]) - 6);
                    horaMarcacion.setMinutes(horaMarcacionParts[1]);
                    horaMarcacion.setSeconds(horaMarcacionParts[2]);

                    registro.hora_marcacion = horaMarcacion.toLocaleTimeString();
                });

                mostrarRegistros();

                const totalPaginas = data.totalPaginas;

                buttonBack.disabled = paginaActual === 1;
                buttonNext.disabled = paginaActual === totalPaginas;
            })
            .catch(error => {
                console.log(error);
            });
    };

    fetchRegistros(paginaActual);

    const buttonBack = document.getElementById('button-back');
    const buttonNext = document.getElementById('button-next');

    buttonBack.addEventListener('click', () => {
        if (paginaActual > 1) {
            paginaActual--;
            fetchRegistros(paginaActual);
            mostrarRegistros();
        }

    });

    buttonNext.addEventListener('click', () => {
        paginaActual++;
        fetchRegistros(paginaActual);
    });

    document.getElementById('logout-button').addEventListener('click', function () {
        localStorage.removeItem('jwtToken')

        window.location.href = '';
    });

    document.getElementById('edit-profile-button').addEventListener('click', function () {
        window.location.href = 'edit-profile'
    });
</script>