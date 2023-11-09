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
        $user_id = $decodedToken->sub;

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $nombre = $data['nombre'];
        $email = $data['email'];

        $usuarioModel = new Usuario();
        $marcacionModel = new Marcacion();
        $usuario = $usuarioModel->customWhere('usuario_id', $user_id);

        if (!$usuario || $usuario['nombre'] !== $nombre || $usuario['email'] !== $email) {
            $mensaje = 'El usuario no existe o los datos no coinciden';
            return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
        }

        $usuarioId = $usuario['usuario_id'];
        $fechaActual = date('Y-m-d');
        $horaActual = date('H:i:s');

        $nuevaMarcacion = $marcacionModel->guardarMarcacion($usuarioId, 'en linea', $fechaActual, $horaActual);

        return $this->response->setJSON(['mensaje' => 'Marcación guardada'])->setStatusCode(200);
    }

    public function getAllRegistros()
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
        $rol = $decodedToken->rol;
        $user_id = $decodedToken->sub;

        if ($rol !== 'admin') {
            return $this->response->setJSON(['mensaje' => 'No eres administrador'])->setStatusCode(401);
        }

        $marcacionModel = new Marcacion();
        $totalRegistros = $marcacionModel->countAll();

        $perPage = 9;
        $page = $this->request->getVar('page') ?? 1;

        $totalPaginas = ceil($totalRegistros / $perPage);

        $registros = $marcacionModel->getAllRegistros($perPage, $page);

        return $this->response->setJSON(['registros' => $registros, 'totalPaginas' => $totalPaginas])->setStatusCode(200);
    }
}