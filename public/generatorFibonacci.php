<?php
declare(strict_types=1);

require_once '../core/include.php';

use ServiceSystem\{
    Parameters\Provider             as ParametersProvider,
    Generator\GeneratorFibonacci    as Generator
};

try {
    $generator      = new Generator();
    $parameters     = ParametersProvider::getInstance();

    $sequenceSize   = (int) $parameters->get('fibonacci.generationSize');
    $delay          = (int) $parameters->get('fibonacci.generationDelay');
    $chanel         = $parameters->get('fibonacci.dataBusChanel');

    $generator->setSize($sequenceSize);
    $generator->setDelay($delay);
    $generator->setChanel($chanel);

    $generator->run();
} catch (RangeException $exception) {
    echo "generator params set failed, {$exception->getMessage()}";
} catch (RuntimeException $exception) {
    echo "generator work process error, {$exception->getMessage()}";
} catch (Throwable $exception) {
    $message    = $exception->getMessage();
    $file       = $exception->getFile();
    $line       = $exception->getLine();

    echo "unexpected error \"$message\" in file $file on line $line";
}