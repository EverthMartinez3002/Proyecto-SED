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

    public static function customWhere($columna, $valor)
    {
        $model = new static();
        return $model->where($columna, $valor)->first();
    }

    public function eliminarAdmin($usuario_id){
        return $this->where('usuario_id', $usuario_id)->delete();
    }
}