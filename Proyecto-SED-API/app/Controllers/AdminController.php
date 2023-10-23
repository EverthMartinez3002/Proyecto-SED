<?php

namespace App\Controllers;

use App\Models\Admin;

class AdminController extends BaseController
{
    public function guardar()
    {
        $datos = $this->request->getJSON();

        $nombre = $datos->nombre;
        $apellido = $datos->apellido;
        $email = $datos->email;
        $contrasena = $datos->contrasena;

        $adminmodel = new Admin();

        $nuevoadmin = $adminmodel->guardarAdmin($nombre,$apellido,$email,$contrasena);

        if($nuevoadmin){
            $mensaje = 'Usuario registrado correctamente';
        } else {
            $mensaje = 'No se pudo registrar el usuario';
        }

        return $this->response->setJSON(['mensaje' => $mensaje]);
    }
}