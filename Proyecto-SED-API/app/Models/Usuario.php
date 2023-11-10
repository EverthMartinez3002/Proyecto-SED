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

    public function guardarUsuario($nombre, $apellido, $email, $contrasena, $fecha_nacimiento, $direccion)
    {

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

    public function getUsuariosConRoles($perPage = 10, $page = 1)
    {
        $camposSeleccionados = ['usuario_id', 'nombre'];

        $usuarios = $this->select($camposSeleccionados)->orderBy('usuario_id', 'ASC')->findAll();
    
        $usuariosFiltrados = array_filter($usuarios, function ($usuario) {
            $usuario_id = $usuario['usuario_id'];
            return !$this->esSuperadmin($usuario_id);
        });
    
        $totalUsuarios = count($usuariosFiltrados);
        $totalPaginas = ceil($totalUsuarios / $perPage);
    
        $offset = ($page - 1) * $perPage;
        $usuariosFiltrados = array_slice($usuariosFiltrados, $offset, $perPage);
    
        foreach ($usuariosFiltrados as &$usuario) {
            $usuario_id = $usuario['usuario_id'];
            $esAdmin = $this->db->table('administradores')->where('usuario_id', $usuario_id)->get()->getRow();
            $usuario['rol'] = $esAdmin ? 'admin' : 'user';
        }
    
        $respuesta = [
            'usuarios' => array_values($usuariosFiltrados),
            'totalPaginas' => $totalPaginas,
        ];
    
        return $respuesta;
    }
    
    protected function countUsuariosExcluyendoSuperadmin()
    {
        return $this->db->table('usuarios')
            ->whereNotIn('usuario_id', function ($builder) {
                $builder->select('usuario_id')->from('superadministradores');
            })
            ->countAllResults();
    }
    
    protected function esSuperadmin($usuario_id)
    {
        return $this->db->table('superadministradores')->where('usuario_id', $usuario_id)->get()->getRow();
    }
    
}