<?php

spl_autoload_register(function ($class) {

    $baseNamespace = 'Afillix\\BankartGateway\\';
    $srcDir = __DIR__ . '/src/';

    $len = strlen($baseNamespace);

    if (strncmp($baseNamespace, $class, $len) !== 0) {
        return;
    }

    $relClass = substr($class, $len);

    $file = $srcDir.str_replace('\\', '/', $relClass).'.php';

    if (file_exists($file)) {
        require_once($file);
    }
});