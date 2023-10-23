<?php

namespace App\Controllers;

use App\Models\Usuario;

class UsuarioController extends BaseController
{
    protected $request;
    protected $response;
    protected $validator;

    public function guardar()
    {
        $datos = $this->request->getJSON();

        $nombre = $datos->nombre;
        $apellido = $datos->apellido;
        $email = $datos->email;
        $contrasena = $datos->contrasena;

        $usuarioModel = new Usuario();

        $nuevoUsuarioID = $usuarioModel->guardarUsuario($nombre, $apellido, $email, $contrasena);

        if ($nuevoUsuarioID) {
            $mensaje = 'Usuario registrado correctamente';
        } else {
            $mensaje = 'No se pudo registrar el usuario';
        }

        return $this->response->setJSON(['mensaje' => $mensaje]);
    }
}