<?php
declare(strict_types=1);

namespace ServiceSystem\Client;

use RuntimeException;
use ServiceSystem\DataBase\QueryException;

use function is_integer;
use function is_null;
/** ***********************************************************************************************
 * Fibonacci numeric sequence client reader.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class ClientFibonacci extends AbstractClient
{
    /** **********************************************************************
     * Save value.
     *
     * @param   mixed $value                Value.
     *
     * @return  void
     * @throws  RuntimeException            Saving process error.
     ************************************************************************/
    protected function save($value): void
    {
        $valueInteger = is_integer($value) ? (int) $value : null;

        if (is_null($valueInteger)) {
            return;
        }

        try {
            $this->getDataBase()->query("
                UPDATE
                    service_system
                SET
                    sum = sum + $valueInteger,
                    count_fib = count_fib + 1
            ");
        } catch (QueryException $exception) {
            throw new RuntimeException(
                $exception->getMessage(),
                0,
                $exception
            );
        }
    }
}