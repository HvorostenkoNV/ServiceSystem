<?php
declare(strict_types=1);

namespace ServiceSystem\Client;

use RuntimeException;
use ServiceSystem\DataBase\DataBase;
/** ***********************************************************************************************
 * Client interface.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
interface ClientInterface
{
    /** **********************************************************************
     * Set database.
     *
     * @param   DataBase $database          Database.
     *
     * @return  void
     ************************************************************************/
    public function setDataBase(DataBase $database): void;
    /** **********************************************************************
     * Set client data chanel for reading.
     *
     * @param   string $chanel              Client data chanel for reading.
     *
     * @return  void
     ************************************************************************/
    public function setChanel(string $chanel): void;
    /** **********************************************************************
     * Run data generation and sending process.
     *
     * @return  void
     * @throws  RuntimeException            Data generation or sending error.
     ************************************************************************/
    public function run(): void;
}