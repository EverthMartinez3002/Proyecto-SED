<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Marcación de Asistencia</title>
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
        .btn-container {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .btn {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        h2 {
            color: #333;
            font-size: 24px;
            margin-top: 40px;
        }
        ul {
            text-align: left;
        }
        li {
            color: #333;
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido al sistema de marcación de asistencia</h1>
        <p>Regístrate o inicia sesión para comenzar a usar nuestras características.</p>
        <div class="btn-container">
            <a class="btn" href="register">Registro</a>
            <a class="btn" href="login">Iniciar Sesión</a>
        </div>
        <h2>Características destacadas</h2>
        <ul>
            <li>Fácil marcación de asistencia.</li>
            <li>Registro y gestión de usuarios.</li>
        </ul>
    </div>
</body>
</html>

<script>
 localStorage.removeItem('jwtToken');
</script>
