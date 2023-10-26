<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\Usuario;
use App\Models\SuperAdmin;

class UsuarioController extends BaseController
{
    public function guardar()
    {
        $datos = $this->request->getJSON();

        $nombre = $datos->nombre;
        $apellido = $datos->apellido;
        $email = $datos->email;
        $contrasena = $datos->contrasena;
        $fecha_nacimiento = $datos->fecha_nacimiento;
        $direccion = $datos->direccion;

        $usuarioModel = new Usuario();

        $usuarioExistente = $usuarioModel->where('email', $email)->first();

        if ($usuarioExistente) {
            $mensaje = 'El correo electrónico ya esta registrado';

            return $this->response->setJSON(['mensaje' => $mensaje]);
        } else {
            $nuevoUsuarioID = $usuarioModel->guardarUsuario($nombre, $apellido, $email, $contrasena, $fecha_nacimiento, $direccion);

            if ($nuevoUsuarioID) {
                $mensaje = 'Usuario registrado correctamente';
            } else {
                $mensaje = 'No se pudo registrar el usuario';
            }
        }

        if ($datos->rol === 'admin') {
            $administradorModel = new Admin();
            $administradorModel->guardarAdmin($nuevoUsuarioID);
        }

        if ($datos->rol === 'superadmin') {
            $superadminModel = new SuperAdmin();
            $superadminModel->guardarSuperAdmin($nuevoUsuarioID);
        }

        return $this->response->setJSON(['mensaje' => $mensaje]);
    }
}