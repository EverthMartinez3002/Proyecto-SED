<?php

namespace App\Models;

use CodeIgniter\Model;

class SuperAdmin extends Model{
    protected $table = "superadmin";

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña',
    ];
}