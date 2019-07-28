<?php
declare(strict_types=1);

require_once '../core/include.php';

use ServiceSystem\DataBus\DataBus;

try {
    $dataBus    = new DataBus();
    // Here have to be PSR-7 Request instance!!!
    $request    = [
        'SERVER'    => $_SERVER,
        'GET'       => $_GET,
        'POST'      => $_POST
    ];

    $dataBus->setRequest($request);
    $dataBus->run();
} catch (Throwable $exception) {
    $message    = $exception->getMessage();
    $file       = $exception->getFile();
    $line       = $exception->getLine();

    echo "unexpected error \"$message\" in file $file on line $line";
}