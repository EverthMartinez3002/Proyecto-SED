<?php

namespace App\Controllers;

use App\Models\Marcacion;
use App\Models\Usuario;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MarcacionController extends BaseController
{
    public function guardar()
    {
        $token = $this->request->getHeaderLine('Authorization');

        if (empty($token)) {
            return $this->response->setJSON(['mensaje' => 'Token no proporcionado'])->setStatusCode(401);
        }

        try {
            $decodedToken = JWT::decode($token, new Key('your_secret_key', 'HS256'));
        } catch (\Exception $e) {
            return $this->response->setJSON(['mensaje' => 'Token no válido'])->setStatusCode(401);
        }

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $nombre = $data['nombre'];
        $email = $data['email'];

        $usuarioModel = new Usuario();
        $marcacionModel = new Marcacion();
        $usuario = $usuarioModel->customWhere('email', $email);

        if (!$usuario || $usuario['nombre'] !== $nombre) {
            $mensaje = 'El usuario no existe o los datos no coinciden';
            return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
        }

        $usuarioId = $usuario['usuario_id'];
        $fechaActual = date('Y-m-d');
        $horaActual = date('H:i:s');

        $nuevaMarcacion = $marcacionModel->guardarMarcacion($usuarioId, 'en linea', $fechaActual, $horaActual);

        return $this->response->setJSON(['mensaje' => 'Marcación guardada'])->setStatusCode(200);
    }
}