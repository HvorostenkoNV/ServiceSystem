<?php
declare(strict_types=1);

require_once '../core/include.php';

use ServiceSystem\{
    DataBase\ConnectionException,
    DataBase\DataBase,
    Parameters\Provider     as ParametersProvider,
    Client\ClientFibonacci  as Client
};

try {
    $client     = new Client();
    $parameters = ParametersProvider::getInstance();
    $chanel     = $parameters->get('fibonacci.dataBusChanel');
    $dataBase   = new DataBase(
        $parameters->get('dataBase.host'),
        $parameters->get('dataBase.name'),
        $parameters->get('dataBase.login'),
        $parameters->get('dataBase.password')
    );

    $client->setDataBase($dataBase);
    $client->setChanel($chanel);
    $client->run();
} catch (ConnectionException $exception) {
    echo "database initialization error, {$exception->getMessage()}";
} catch (Throwable $exception) {
    $message    = $exception->getMessage();
    $file       = $exception->getFile();
    $line       = $exception->getLine();

    echo "unexpected error \"$message\" in file $file on line $line";
}