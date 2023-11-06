<?php

namespace App\Controllers;

use App\Models\Marcacion;
use App\Models\Usuario;

class MarcacionController extends BaseController
{
    public function guardar()
    {

      /*  $token = $this->request->getHeaderLine('Authorization');

        if (empty($token)) {
            return $this->response->setJSON(['mensaje' => 'Token no proporcionado'])->setStatusCode(401);
        }
        try {
            $decodedToken = \Firebase\JWT\JWT::decode($token, ['HS256']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['mensaje' => 'Token no válido'])->setStatusCode(401);
        }*/

        $email = $this->request->getVar("email");
        $nombre = $this->request->getVar("nombre");

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