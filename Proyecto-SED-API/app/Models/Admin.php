<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = "administradores";

    protected $allowedFields = [
        'id',
        'usuario_id',
    ];

    public function guardarAdmin($usuario_id){
        $this->insert([
            'usuario_id' => $usuario_id,
        ]);

        return $this->insertID();
    }
}