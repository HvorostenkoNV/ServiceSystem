<?php
declare(strict_types=1);

namespace ServiceSystem\Client;

use RuntimeException;
use ServiceSystem\{
    DataBase\DataBase,
    DataFormat\DecodingException,
    DataFormat\Json,
    Http\RequestException,
    Http\CurlRequest
};

use function usleep;
/** ***********************************************************************************************
 * Client abstract class.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
abstract class AbstractClient implements ClientInterface
{
    private const READING_REQUEST_DELAY = 200000;

    private $database   = null;
    private $chanel     = '';
    /** **********************************************************************
     * Set database.
     *
     * @param   DataBase $database          Database.
     *
     * @return  void
     ************************************************************************/
    public function setDataBase(DataBase $database): void
    {
        $this->database = $database;
    }
    /** **********************************************************************
     * Set client data chanel for reading.
     *
     * @param   string $chanel              Client data chanel for reading.
     *
     * @return  void
     ************************************************************************/
    public function setChanel(string $chanel): void
    {
        $this->chanel = $chanel;
    }
    /** **********************************************************************
     * Run data reading process.
     *
     * @return  void
     * @throws  RuntimeException            Data reading error.
     ************************************************************************/
    public function run(): void
    {
        while (true) {
            usleep(self::READING_REQUEST_DELAY);

            try {
                $data           = $this->readDataWithHttp($this->chanel);
                $value          = $data['value'] ?? null;
                $isLastValue    = isset($data['isLast']) && $data['isLast'] === true;
            } catch (RuntimeException $exception) {
                throw new RuntimeException(
                    "data reading failed, {$exception->getMessage()}",
                    0,
                    $exception
                );
            }

            try {
                $this->save($value);
            } catch (RuntimeException $exception) {
                throw new RuntimeException(
                    "data saving failed, {$exception->getMessage()}",
                    0,
                    $exception
                );
            }

            if ($isLastValue) {
                break;
            }
        }
    }
    /** **********************************************************************
     * Get database.
     *
     * @return  DataBase                    Database.
     ************************************************************************/
    protected function getDataBase(): DataBase
    {
        return $this->database;
    }
    /** **********************************************************************
     * Read data using HTTP protocol.
     *
     * @param   string $uri                 URI.
     *
     * @return  array                       Data.
     * @throws  RuntimeException            Reading failed.
     ************************************************************************/
    private function readDataWithHttp(string $uri): array
    {
        try {
            $response               = CurlRequest::make($uri);
            $responseDataDecoded    = Json::decode($response);
            $hasSuccessAnswer       =
                isset($responseDataDecoded['success']) &&
                $responseDataDecoded['success'] === true;
            $errorAnswer            = (string)  ($responseDataDecoded['error']  ?? 'unknown error');
            $responseData           = (array)   ($responseDataDecoded['data']   ?? []);
        } catch (DecodingException $exception) {
            throw new RuntimeException(
                "response data decoding failed with error \"{$exception->getMessage()}\"",
                0,
                $exception
            );
        } catch (RequestException $exception) {
            throw new RuntimeException(
                "request failed with error \"{$exception->getMessage()}\"",
                0,
                $exception
            );
        }

        if (!$hasSuccessAnswer) {
            throw new RuntimeException("response is not success and has error \"$errorAnswer\"");
        }

        return $responseData;
    }
    /** **********************************************************************
     * Save value.
     *
     * @param   mixed $value                Value.
     *
     * @return  void
     * @throws  RuntimeException            Saving process error.
     ************************************************************************/
    abstract protected function save($value): void;
}