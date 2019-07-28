<?php
declare(strict_types=1);

require_once '../core/include.php';

use ServiceSystem\{
    DemonstrationHelper,
    Parameters\Provider as ParametersProvider,
};

try {
    $demonstration  = new DemonstrationHelper();
    $parameters     = ParametersProvider::getInstance();

    $demonstration->rewind();
    $demonstration->execute([
        $parameters->get('fibonacci.generatorUri'),
        $parameters->get('primeNumber.generatorUri'),
        $parameters->get('fibonacci.clientUri'),
        $parameters->get('primeNumber.clientUri')
    ]);

    echo '<a href="databaseCurrentState.php" target="_blank">Database current state</a>';
} catch (Throwable $exception) {
    $message    = $exception->getMessage();
    $file       = $exception->getFile();
    $line       = $exception->getLine();

    echo "unexpected error \"$message\" in file $file on line $line";
}