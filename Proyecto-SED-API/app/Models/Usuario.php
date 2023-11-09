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

    public static function customWhere($columna, $valor, $camposSeleccionados = [])
    {
        $model = new static();
        
        if (empty($camposSeleccionados)) {
            return $model->where($columna, $valor)->first();
        } else {
            return $model->select($camposSeleccionados)->where($columna, $valor)->first();
        }
    }

    public static function updateUser($userId, $data)
    {
        $usuarioModel = new Usuario();
        $builder = $usuarioModel->builder();
        $builder->where('usuario_id', $userId);
        return $builder->update($data);
    }
}