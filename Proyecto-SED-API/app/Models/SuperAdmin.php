<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdmin extends Model
{
    protected $table = "superadministradores";

    protected $allowedFields = [
        'id',
        'usuario_id'
    ];

    public function guardarSuperAdmin($usuario_id)
    {
        $this->insert([
            'usuario_id' => $usuario_id,
        ]);

        return $this->insertID();
    }

    public static function customWhere($columna, $valor)
    {
        $model = new static();
        return $model->where($columna, $valor)->first();
    }
}