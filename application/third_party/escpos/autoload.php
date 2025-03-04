<?php
/**
 * Autoloading para escpos-php sin PSR-4 (compatible con PHP 5.4)
 */
spl_autoload_register(function ($class) {
    $prefix = "Mike42\\";
    $base_dir = __DIR__ . "/src/Mike42/";

    // Solo continuar para clases dentro de este espacio de nombres
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Reemplazar las barras invertidas (\) por directorios
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
