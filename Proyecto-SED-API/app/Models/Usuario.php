<?php

namespace App\Models;

use CodeIgniter\Model;

class Usuario extends Model
{
    protected $table = 'usuario';

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña'
    ];

    public function guardarUsuario($nombre, $apellido, $email, $contrasena){
        $this->insert([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $email,
            'contraseña' => password_hash($contrasena, PASSWORD_DEFAULT),
        ]);

        return $this->insertID();
    }
}