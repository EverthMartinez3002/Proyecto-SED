<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = "admin";

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña',
    ];

    public function guardarAdmin($nombre, $apellido, $email, $contrasena){
        $this->insert([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contraseña' => password_hash($contrasena, PASSWORD_DEFAULT),
        ]);

        return $this->insertID();
    }
}