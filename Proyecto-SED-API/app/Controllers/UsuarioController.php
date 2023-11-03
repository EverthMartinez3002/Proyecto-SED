<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Admin;
use App\Models\SuperAdmin;
use Firebase\JWT\JWT;

class UsuarioController extends BaseController
{
    public function guardar()
    {
        // Obtén los datos del formulario de registro
        $nombre = $this->request->getVar('nombre');
        $apellido = $this->request->getVar('apellido');
        $email = $this->request->getVar('email');
        $contrasena = $this->request->getVar('contrasena');
        $fecha_nacimiento = $this->request->getVar('fecha_nacimiento');
        $direccion = $this->request->getVar('direccion');
        $rol = $this->request->getVar('rol');

        $usuarioModel = new Usuario();

        // Verifica si el correo electrónico ya está registrado
        $usuarioExistente = $usuarioModel->where('email', $email)->first();

        

        if ($usuarioExistente) {
            $mensaje = 'El correo electrónico ya está registrado';

            return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
        } else {
            // Registra al nuevo usuario
            $nuevoUsuarioID = $usuarioModel->guardarUsuario($nombre, $apellido, $email, $contrasena, $fecha_nacimiento, $direccion);

            if ($nuevoUsuarioID) {
                if ($rol === 'admin') {
                    $administradorModel = new Admin();
                    $administradorModel->guardarAdmin($nuevoUsuarioID);
                }
        
                if ($rol === 'superadmin') {
                    $superadminModel = new SuperAdmin();
                    $superadminModel->guardarSuperAdmin($nuevoUsuarioID);
                }

                return redirect()->to('/');
            } else {
                $mensaje = 'No se pudo registrar el usuario';
                return $this->response->setJSON(['mensaje' => $mensaje])->setStatusCode(400);
            }
        }
    }

   public function login(){
        $email = $this->request->getVar('email');
        $contrasena = $this->request->getVar('contrasena');
        $usuarioModel = new Usuario();

        $usuario = $usuarioModel->customWhere('email', $email);
        
        if($usuario) {
            if (password_verify($contrasena, $usuario->contrasena)) {

                $key = config('JWT.key');
                $algorithm = config('JWT.algorithm');
    
                $payload = [
                    'sub' => $usuario->id,
                    'email' => $usuario->email,
                    'rol' => $usuario->rol,
                    'iat' => time(),
                    'exp' => time() + (60 * 60), 
                ];

                $jwt = \Firebase\JWT\JWT::encode($payload, $key, $algorithm);

                return $this->response->setJSON(['token'=> $jwt])->setStatusCode(200);
        } else {
            return $this->response->setJSON(['mensaje' => 'la contraseña es incorrecta'])->setStatusCode(401);
        }
    } else {
        return $this->response->setJSON(['mensaje' => 'el usuario no existe'])->setStatusCode(401);
    }
 }
}