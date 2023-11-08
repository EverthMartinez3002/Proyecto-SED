<?php

namespace App\Models;

use CodeIgniter\Model;

class Marcacion extends Model
{
    protected $table = "registrosmarcacion";

    protected $allowedFields = [
        'id',
        'usuario_id',
        'tipo_marcacion',
        'fecha_marcacion',
        'hora_marcacion'
    ];

    public function guardarMarcacion($usuario_id, $tipo_marcacion, $fecha_marcacion, $hora_marcacion){

        $this->insert([
           'usuario_id' => $usuario_id,
           'tipo_marcacion'  => $tipo_marcacion,
           'fecha_marcacion' => $fecha_marcacion,
           'hora_marcacion' => $hora_marcacion
        ]);

        return $this->insertID();
    }

    public static function getAllRegistros($perPage = 10, $page = 1)
    {
        $model = new static();

        $offset = ($page - 1) * $perPage; 
        
        $registros = $model->findAll($perPage, $offset);

        return $registros;
    }

    public static function Registros()
    {
        $model = new static();
       return $model->findAll();
    }
}