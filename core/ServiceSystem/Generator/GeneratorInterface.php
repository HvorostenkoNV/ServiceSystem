<?php
declare(strict_types=1);

namespace ServiceSystem\Generator;

use RangeException;
use RuntimeException;
/** ***********************************************************************************************
 * Generator interface.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
interface GeneratorInterface
{
    /** **********************************************************************
     * Set generated data sequence size.
     *
     * @param   int $size                   Generated data sequence size.
     *
     * @return  void
     * @throws  RangeException              Sequence size value is invalid.
     ************************************************************************/
    public function setSize(int $size): void;
    /** **********************************************************************
     * Set generation data delay.
     *
     * @param   int $delay                  Generation data delay.
     *
     * @return  void
     * @throws  RangeException              Delay value is invalid.
     ************************************************************************/
    public function setDelay(int $delay): void;
    /** **********************************************************************
     * Set generation data chanel for sending.
     *
     * @param   string $chanel              Generation data chanel for sending.
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