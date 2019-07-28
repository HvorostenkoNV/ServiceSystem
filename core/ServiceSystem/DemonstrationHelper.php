<?php
declare(strict_types=1);

namespace ServiceSystem;

use Throwable;
use Exception;
use RuntimeException;
use Predis\{
    ClientException as RedisException,
    Client          as RedisClient
};
use ServiceSystem\{
    Parameters\Provider as ParametersProvider,
    Http\RequestException,
    Http\CurlRequest,
    DataBase\ConnectionException,
    DataBase\QueryException,
    DataBase\DataBase
};

use function is_string;
use function strlen;
use function count;
use function array_filter;
/** ***********************************************************************************************
 * System demonstration helper class.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class DemonstrationHelper
{
    private $database   = null;
    private $redis      = null;
    /** **********************************************************************
     * Constructor.
     *
     * @throws  Exception                   Any error.
     ************************************************************************/
    public function __construct()
    {
        try {
            $this->database = $this->getDatabase();
            $this->redis    = $this->getRedis();
        } catch (ConnectionException | RedisException $exception) {
            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Clean all previous generated data.
     *
     * @return  void
     * @throws  Exception                   Any error.
     ************************************************************************/
    public function rewind(): void
    {
        try {
            $this->clearDatabase();
            $this->clearRedis();
        } catch (RuntimeException $exception) {
            throw new Exception($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Execute URIs.
     *
     * @param   string[] $uriSet            URI set.
     *
     * @return  void
     * @throws  Exception                   Any error.
     ************************************************************************/
    public function execute(array $uriSet): void
    {
        foreach ($uriSet as $uri) {
            try {
                CurlRequest::make($uri, 'GET', '', false);
            } catch (RequestException $exception) {

            } catch (Throwable $exception) {
                throw new Exception($exception->getMessage(), 0, $exception);
            }
        }
    }
    /** **********************************************************************
     * Get database current state.
     *
     * @return  array                       Database current state.
     ************************************************************************/
    public function getDatabaseCurrentState(): array
    {
        try {
            return $this->database->query("
                SELECT
                    *
                FROM
                    service_system
            ");
        } catch (QueryException $exception) {
            return [];
        }
    }
    /** **********************************************************************
     * Get database.
     *
     * @return  DataBase                    Database.
     * @throws  ConnectionException         Database unreachable.
     ************************************************************************/
    private function getDatabase(): DataBase
    {
        try {
            $parameters = ParametersProvider::getInstance();

            return new DataBase(
                $parameters->get('dataBase.host'),
                $parameters->get('dataBase.name'),
                $parameters->get('dataBase.login'),
                $parameters->get('dataBase.password')
            );
        } catch (ConnectionException $exception) {
            throw $exception;
        }
    }
    /** **********************************************************************
     * Get redis.
     *
     * @return  RedisClient                 Redis.
     * @throws  RedisException              Redis unreachable.
     ************************************************************************/
    private function getRedis(): RedisClient
    {
        try {
            return new RedisClient([
                'scheme'    => 'tcp',
                'host'      => '127.0.0.1',
                'port'      => 6379
            ]);
        } catch (Throwable $exception) {
            throw new RedisException($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Clear database.
     *
     * @return  void
     * @throws  RuntimeException            Process failed.
     ************************************************************************/
    private function clearDatabase(): void
    {
        try {
            $this->database->query("
                UPDATE
                    service_system
                SET
                    sum = 0,
                    count_fib = 0,
                    count_prime = 0
            ");
        } catch (QueryException $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Clear redis.
     *
     * @return  void
     * @throws  RuntimeException            Process failed.
     ************************************************************************/
    private function clearRedis(): void
    {
        try {
            $keys       = self::getRedis()->keys('*');
            $keysValid  = array_filter($keys, function($value) {
                return is_string($value) && strlen($value) > 0;
            });

            if (count($keysValid) > 0) {
                self::getRedis()->del($keysValid);
            }
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }
    }
}