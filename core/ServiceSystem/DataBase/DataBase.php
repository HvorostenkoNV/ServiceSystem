<?php
declare(strict_types=1);

namespace ServiceSystem\DataBase;

use Throwable;
use PDOException;
use PDO;
use PDOStatement;

use function var_export;
/** ***********************************************************************************************
 * Database class, provides database access.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class DataBase
{
    private $pdo = null;
    /** **********************************************************************
     * Constructor.
     *
     * @param   string      $host               Host.
     * @param   string      $name               DB name.
     * @param   string      $login              Login.
     * @param   string|null $password           Password.
     *
     * @throws  ConnectionException             Database connection failed.
     ************************************************************************/
    public function __construct(string $host, string $name, string $login, ?string $password)
    {
        try {
            $this->pdo = $this->createPDOConnection($host, $name, $login, $password);
        } catch (PDOException $exception) {
            throw new ConnectionException($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Run SQL query and get result.
     *
     * @param   string  $sqlQuery               SQL query.
     * @param   array   $parameters             Query parameters.
     *
     * @return  array                           Query result in rows.
     * @throws  QueryException                  Query error.
     ************************************************************************/
    public function query(string $sqlQuery, array $parameters = []): array
    {
        try {
            $preparedQuery  = $this->pdo->prepare($sqlQuery);
            $queryResult    = $this->executeQueryStatement($preparedQuery, $parameters);

            return $queryResult;
        } catch (Throwable $exception) {
            $message                = $exception->getMessage();
            $parametersPrintable    = var_export($parameters, true);

            throw new QueryException(
                "query error \"$message\" on executing query \"$sqlQuery\" with parameters$parametersPrintable",
                0,
                $exception
            );
        }
    }
    /** **********************************************************************
     * Create new PDO connection.
     *
     * @param   string      $host               Host.
     * @param   string      $name               DB name.
     * @param   string      $login              Login.
     * @param   string|null $password           Password.
     *
     * @return  PDO                             New PDO connection.
     * @throws  PDOException                    PDO connection creating failed.
     ************************************************************************/
    private function createPDOConnection(string $host, string $name, string $login, ?string $password): PDO
    {
        try {
            $pdo = new PDO(
                "mysql:dbname=$name;host=$host",
                $login,
                $password,
                [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
                ]
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_PERSISTENT, false);

            return $pdo;
        } catch (PDOException $exception) {
            throw $exception;
        }
    }
    /** **********************************************************************
     * Execute prepared query statement.
     *
     * @param   PDOStatement    $preparedQuery  Prepared query statement.
     * @param   array           $parameters     Query parameters.
     *
     * @return  array                           Query result.
     * @throws  PDOException                executing error
     ************************************************************************/
    private function executeQueryStatement(PDOStatement $preparedQuery, array $parameters): array
    {
        try {
            $executionResult = $preparedQuery->execute($parameters);
        } catch (PDOException $exception) {
            throw $exception;
        }

        if (!$executionResult) {
            throw new PDOException($preparedQuery->errorInfo()[2]);
        }

        try {
            return $preparedQuery->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $exception) {
            return [];
        }
    }
}