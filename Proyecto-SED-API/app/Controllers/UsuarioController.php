<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Admin;
use App\Models\SuperAdmin;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UsuarioController extends BaseController
{
    public function guardar()
    {

        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $nombre = $data['nombre'];
        $apellido = $data['apellido'];
        $email = $data['email'];
        $contrasena = $data['contrasena'];
        $fecha_nacimiento = $data['fecha_nacimiento'];
        $direccion = $data['direccion'];
        $rol = $data['rol'];

        $usuarioModel = new Usuario();

        $usuarioExistente = $usuarioModel->where('email', $email)->first();

        if ($usuarioExistente) {
            $mensaje = 'El correo electrónico ya está registrado';

            return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
        } else {
            $nuevoUsuarioID = $usuarioModel->guardarUsuario($nombre, $apellido, $email, $contrasena, $fecha_nacimiento, $direccion);

            if ($nuevoUsuarioID) {
                if ($rol === 'admin') {
                    $administradorModel = new Admin();
                    $administradorModel->guardarAdmin($nuevoUsuarioID);
                }

                if ($rol === 'superadmin') {
                    $superadministradorModel = new SuperAdmin();
                    $superadministradorModel->guardarSuperAdmin($nuevoUsuarioID);
                }

                return $this->response->setJSON(['mensaje' => 'El usuario ha sido registrado con exito'])->setStatusCode(200);
            } else {
                $mensaje = 'No se pudo registrar el usuario';
                return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
            }
        }
    }

    public function login()
    {
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        $email = $data['email'];
        $contrasena = $data['contrasena'];
        $usuarioModel = new Usuario();
        $adminModel = new Admin();
        $superadminModel = new SuperAdmin();

        $usuario = $usuarioModel->customWhere('email', $email);
        $user_id = $usuario['usuario_id'];
        $esadmin = $adminModel->customWhere('usuario_id', $user_id);
        $essuperadmin = $superadminModel->customWhere('usuario_id', $user_id);

        if ($usuario) {
            if (password_verify($contrasena, $usuario['contraseña'])) {

                $payload = [
                    'sub' => $usuario['usuario_id'],
                    'email' => $usuario['email'],
                    'rol' => "user",
                    'iat' => time(),
                    'exp' => time() + (60 * 60),
                ];

                if ($esadmin) {
                    $payload = [
                        'sub' => $usuario['usuario_id'],
                        'email' => $usuario['email'],
                        'rol' => "admin",
                        'iat' => time(),
                        'exp' => time() + (60 * 60),
                    ];
                }

                if ($essuperadmin) {
                    $payload = [
                        'sub' => $usuario['usuario_id'],
                        'email' => $usuario['email'],
                        'rol' => "superadmin",
                        'iat' => time(),
                        'exp' => time() + (60 * 60),
                    ];
                }

                $jwt = JWT::encode($payload, 'your_secret_key', 'HS256');

                return $this->response->setJSON(['token' => $jwt])->setStatusCode(200);
            } else {
                return $this->response->setJSON(['mensaje' => 'la contraseña es incorrecta'])->setStatusCode(401);
            }
        } else {
            return $this->response->setJSON(['mensaje' => 'el usuario no existe'])->setStatusCode(401);
        }
    }

    public function getuserById()
    {
        $token = $this->request->getHeaderLine('Authorization');

        if (empty($token)) {
            return $this->response->setJSON(['mensaje' => 'Token no proporcionado'])->setStatusCode(401);
        }

        try {
            $decodedToken = JWT::decode($token, new Key('your_secret_key', 'HS256'));
            $user_id = $decodedToken->sub;

            $usuarioModel = new Usuario();

            $usuario = $usuarioModel->customWhere('usuario_id', $user_id, ['nombre', 'apellido', 'fecha_nacimiento', 'direccion']);

            if ($usuario) {
                return $this->response->setJSON(['usuario' => $usuario])->setStatusCode(200);
            } else {
                return $this->response->setJSON(['mensaje' => 'Usuario no encontrado'])->setStatusCode(404);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['mensaje' => 'Token no válido'])->setStatusCode(401);
        }
    }

    public function editProfileUser()
    {
        $token = $this->request->getHeaderLine('Authorization');

        if (empty($token)) {
            return $this->response->setJSON(['mensaje' => 'Token no proporcionado'])->setStatusCode(401);
        }

        try {
            $decodedToken = JWT::decode($token, new Key('your_secret_key', 'HS256'));
            $user_id = $decodedToken->sub;

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            $nombre = $data['nombre'];
            $apellido = $data['apellido'];
            $fecha_nacimiento = $data['fecha_nacimiento'];
            $direccion = $data['direccion'];

            $usuarioModel = new Usuario();

            $usuario = $usuarioModel->customWhere('usuario_id', $user_id);

            $newdata = [];

            if (!empty($data['nombre']) && $data['nombre'] !== $usuario['nombre']) {
                $newdata['nombre'] = $data['nombre'];
            }

            if (!empty($data['apellido']) && $data['apellido'] !== $usuario['apellido']) {
                $newdata['apellido'] = $data['apellido'];
            }

            if (!empty($data['fecha_nacimiento']) && $data['fecha_nacimiento'] !== $usuario['fecha_nacimiento']) {
                $newdata['fecha_nacimiento'] = $data['fecha_nacimiento'];
            }

            if (!empty($data['direccion']) && $data['direccion'] !== $usuario['direccion']) {
                $newdata['direccion'] = $data['direccion'];
            }

            if (!empty($newdata)) {
                $updated = $usuarioModel->updateUser($user_id, $newdata);
            }

            if ($updated) {
                return $this->response->setJSON(['mensaje' => 'Perfil actualizado con éxito']);
            } else {
                return $this->response->setJSON(['mensaje' => 'Error al actualizar el perfil'])->setStatusCode(500);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON(['mensaje' => 'No se ha modificado ningun dato'])->setStatusCode(500);
        }
    }

    public function getAllUsers()
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

        if ($rol != 'superadmin') {
            return $this->response->setJSON(['mensaje' => 'No eres administrador'])->setStatusCode(401);
        }

        $perPage = 10;
        $page = $this->request->getVar('page') ?? 1;

        $usuarioModel = new Usuario();
        $usuarios = $usuarioModel->getUsuariosConRoles($perPage, $page);

        return $this->response->setJSON($usuarios)->setStatusCode(200);
    }
}