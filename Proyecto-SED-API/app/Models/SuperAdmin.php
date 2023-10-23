<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdmin extends Model{
    protected $table = "superadmin";

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña',
    ];

    public function guardarSuperAdmin($nombre, $apellido, $email, $contrasena){
        $this->insert([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contraseña' => password_hash($contrasena, PASSWORD_DEFAULT),
        ]);

        return $this->insertID();
    }
}