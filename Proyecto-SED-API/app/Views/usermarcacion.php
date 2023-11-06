<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Marcación de Usuario</h1>
        <form id="marcacion-create" action="<?php echo base_url('marcacion/create'); ?>" method="post">
            <label for="nombre">Nombre:</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" placeholder="Ingrese su correo electrónico" required>
            <button class="btn" type="submit">Enviar</button>
        </form>
    </div>
</body>
</html>

<script>
document.getElementById('marcacion-create').addEventListener('submit', function (event){

    const jwtToken = localStorage.getItem('jwtToken')

    const nombre = document.querySelector('#nombre').value;
    const email = document.querySelector('#email').value;

    fetch(this.action, {
        method: 'POST',
        headers: {
            'Authorization': `${jwtToken}` 
        },
        body: JSON.stringify({ nombre, email }) 
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })

})
</script>