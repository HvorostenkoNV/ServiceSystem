<?php
declare(strict_types=1);

namespace ServiceSystem\Parameters;

use SplFileInfo;

use function strlen;
use function explode;

use const PARAMETERS_FILE;
/** ***********************************************************************************************
 * Parameters provider class, provides system parameters reading access.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class Provider
{
    private static $instance = null;

    private $parameters = [];
    /** **********************************************************************
     * Singleton constructor.
     *
     * @return  self                        Self.
     ************************************************************************/
    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }
    /** **********************************************************************
     * Get parameter value.
     *
     * @param   string $name                Parameter name.
     *
     * @return  string                      Parameter value.
     ************************************************************************/
    public function get(string $name): string
    {
        return $this->parameters[$name] ?? '';
    }
    /** **********************************************************************
     * Constructor.
     ************************************************************************/
    private function __construct()
    {
        $parametersFile = new SplFileInfo(PARAMETERS_FILE);

        if ($parametersFile->isFile() && $parametersFile->isReadable()) {
            $this->parameters = $this->readParametersFromFile($parametersFile);
        }
    }
    /** **********************************************************************
     * Read parameters from file.
     *
     * @param   SplFileInfo $file           File.
     *
     * @return  array                       Parameters.
     ************************************************************************/
    private function readParametersFromFile(SplFileInfo $file): array
    {
        $fileObject             = $file->openFile('r');
        $fileContentRead        = $fileObject->fread($fileObject->getSize());
        $fileContent            = $fileContentRead !== false ? $fileContentRead : '';
        $fileContentExploded    = explode("\n", $fileContent);
        $data                   = [];

        foreach ($fileContentExploded as $string) {
            $stringExploded = explode('=', $string, 2);
            $key            = $stringExploded[0]    ?? '';
            $value          = $stringExploded[1]    ?? '';

            if (strlen($key) > 0) {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}