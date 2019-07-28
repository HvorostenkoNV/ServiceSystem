<?php
declare(strict_types=1);

namespace ServiceSystem\Generator;

use RuntimeException;
/** ***********************************************************************************************
 * Prime numbers sequence generator.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class GeneratorPrimeNumber extends AbstractGenerator
{
    private const FIRST_VALUE = 2;

    private $currentValue = 0;
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
        while (true) {
            $divisionsCount = 0;
            $currentValue   = $this->currentValue;
            $this->currentValue++;

            for ($iteration = 1; $iteration <= $currentValue; $iteration++) {
                if ($currentValue % $iteration === 0) {
                    $divisionsCount++;
                }
            }

            if ($divisionsCount === 2) {
                return $currentValue;
            }
        }

        return null;
    }
    /** **********************************************************************
     * Rewind generator.
     *
     * @return  void
     ************************************************************************/
    private function rewind(): void
    {
        $this->currentValue = self::FIRST_VALUE;
    }
}