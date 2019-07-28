<?php
declare(strict_types=1);

$parentDirectoryPath    = realpath(__DIR__.DIRECTORY_SEPARATOR.'..');
$parametersFilePath     =
    $parentDirectoryPath.DIRECTORY_SEPARATOR.
    'params'.DIRECTORY_SEPARATOR.
    'systemParameters.txt';

define('PARAMETERS_FILE', $parametersFilePath);

spl_autoload_register(function($className) {
    $classPath      = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $classFilePath  = __DIR__.DIRECTORY_SEPARATOR.$classPath.'.php';
    $classFile      = new SplFileInfo($classFilePath);

    if ($classFile->isFile()) {
        require $classFile->getPathname();
    }
});

require 'Predis/autoload.php';