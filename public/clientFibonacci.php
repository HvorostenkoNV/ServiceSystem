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
    $client             = new Client();
    $parameters         = ParametersProvider::getInstance();

    $dataBaseHost       = $parameters->get('dataBase.host');
    $dataBaseName       = $parameters->get('dataBase.name');
    $dataBaseLogin      = $parameters->get('dataBase.login');
    $dataBasePassword   = $parameters->get('dataBase.password');
    $chanel             = $parameters->get('fibonacci.dataBusChanel');

    $dataBase           = new DataBase(
        $dataBaseHost,
        $dataBaseName,
        $dataBaseLogin,
        $dataBasePassword
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