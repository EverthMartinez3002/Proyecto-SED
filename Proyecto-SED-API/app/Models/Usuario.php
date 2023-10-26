<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña',
        'fecha_nacimiento',
        'direccion',
    ];

    public function guardarUsuario($nombre, $apellido, $email, $contrasena, $fecha_nacimiento, $direccion){

        $this->insert([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contraseña' => password_hash($contrasena, PASSWORD_DEFAULT),
            'fecha_nacimiento' => $fecha_nacimiento,
            'direccion' => $direccion,
        ]);

        return $this->insertID();
    }
}