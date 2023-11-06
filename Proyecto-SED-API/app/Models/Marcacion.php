<?php

namespace App\Models;

use Codeigniter\Model;

class Marcacion extends Model{

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
}