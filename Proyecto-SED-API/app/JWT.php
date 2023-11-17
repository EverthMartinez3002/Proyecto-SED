<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    public $key = 'your_secret_key';
    public $algorithm = 'HS256';
    public $validFor = 3600;
}