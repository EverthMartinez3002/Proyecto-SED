<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    protected $table = "admin";

    protected $allowedFields = [
        'id',
        'nombre',
        'apellido',
        'email',
        'contraseña',
    ];
}