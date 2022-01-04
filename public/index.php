<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

// Descomentar para evitar problemas de configuración HTTPS en servidor de Mate
//$_SERVER['HTTPS'] = 'on';
//$_SERVER['SERVER_PORT'] = 443;

return function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
