<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\Admin;
use App\Models\SuperAdmin;

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
}
