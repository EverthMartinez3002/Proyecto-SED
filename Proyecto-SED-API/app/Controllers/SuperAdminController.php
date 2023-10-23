<?php

namespace App\Controllers;

use App\Models\SuperAdmin;

class SuperAdminController extends BaseController
{

    public function guardar()
    {
        $datos = $this->request->getJSON();

        $nombre = $datos->nombre;
        $apellido = $datos->apellido;
        $email = $datos->email;
        $contrasena = $datos->contrasena;

        $superadminmodel = new SuperAdmin();

        $nuevosuperadmin = $superadminmodel->guardarSuperAdmin($nombre, $apellido, $email, $contrasena);

        if ($nuevosuperadmin) {
            $mensaje = 'Usuario registrado correctamente';
        } else {
            $mensaje = 'No se pudo registrar el usuario';
        }

        return $this->response->setJSON(['mensaje' => $mensaje]);
    }
}