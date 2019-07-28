<?php
declare(strict_types=1);

namespace ServiceSystem\Generator;

use RuntimeException;
/** ***********************************************************************************************
 * Fibonacci numeric sequence generator.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class GeneratorFibonacci extends AbstractGenerator
{
    private const VALUE_START_1 = 0;
    private const VALUE_START_2 = 1;

    private $beforePrevious     = 0;
    private $previous           = 0;
    private $currentIteration   = 0;
    /** **********************************************************************
     * Run data generation and sending process.
     *
     * @return  void
     * @throws  RuntimeException            Data generation or sending error.
     ************************************************************************/
    public function run(): void
    {
        $this->rewind();

        try {
            parent::run();
        } catch (RuntimeException $exception) {
            throw $exception;
        }
    }
    /** **********************************************************************
     * Generate value.
     *
     * @return  mixed                       Value.
     ************************************************************************/
    protected function generate()
    {
        $iteration = $this->currentIteration;
        $this->currentIteration++;

        switch ($iteration) {
            case 0:
                return self::VALUE_START_1;
            case 1:
                return self::VALUE_START_2;
            default:
                $summery = $this->beforePrevious + $this->previous;

                $this->beforePrevious   = $this->previous;
                $this->previous         = $summery;

                return $summery;
        }
    }
    /** **********************************************************************
     * Rewind generator.
     *
     * @return  void
     ************************************************************************/
    private function rewind(): void
    {
        $this->beforePrevious   = self::VALUE_START_1;
        $this->previous         = self::VALUE_START_2;
        $this->currentIteration = 0;
    }
}