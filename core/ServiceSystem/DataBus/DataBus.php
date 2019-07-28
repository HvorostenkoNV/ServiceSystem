<?php
declare(strict_types=1);

namespace ServiceSystem\DataBus;

use Throwable;
use RuntimeException;
use Predis\Client as RedisClient;
use ServiceSystem\DataFormat\{
    EncodingException,
    DecodingException,
    Json
};

use function is_array;
use function strlen;
/** ***********************************************************************************************
 * DataBus class.
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class DataBus
{
    private $redis          = null;
    private $fullRequest    = [];
    private $requestType    = '';
    private $chanel         = '';
    private $postData       = [];
    /** **********************************************************************
     * Set request.
     *
     * @param   array $request              Request.
     *                                      In ordinary must be a PSR-7 Request instance!
     *
     * @return  void
     ************************************************************************/
    public function setRequest(array $request): void
    {
        try {
            $postDataReceived   = (string) ($request['POST'][0] ?? '');
            $postDataDecoded    = Json::decode($postDataReceived);
            $postData           = is_array($postDataDecoded) ? $postDataDecoded : [];
        } catch (DecodingException $exception) {
            $postData           = [];
        }

        $this->fullRequest  = $request;
        $this->requestType  = (string) ($request['SERVER']['REQUEST_METHOD']    ?? '');
        $this->chanel       = (string) ($request['GET']['chanel']               ?? '');
        $this->postData     = $postData;
    }
    /** **********************************************************************
     * Run data-bus working.
     *
     * @return  void
     ************************************************************************/
    public function run(): void
    {
        $answer = [
            'success' => true
        ];

        if (strlen($this->chanel) <= 0) {
            $answer['success']  = false;
            $answer['error']    = 'chanel name is empty';
        } else {
            try {
                switch ($this->requestType) {
                    case 'POST':
                        $this->pushData($this->chanel, $this->postData);
                        break;
                    case 'GET':
                        $answer['data'] = $this->extractData($this->chanel);
                }
            } catch (Throwable $exception) {
                $message    = $exception->getMessage();
                $file       = $exception->getFile();
                $line       = $exception->getLine();

                $answer['success']  = false;
                $answer['error']    = "system failed, $message in $file on $line";
            }
        }

        try {
            echo Json::encode($answer);
        } catch (EncodingException $exception) {

        }
    }
    /** **********************************************************************
     * Push data to chanel.
     *
     * @param   string  $chanel             Chanel.
     * @param   array   $data               Data.
     *
     * @return  void
     * @throws  RuntimeException            Process error.
     ************************************************************************/
    private function pushData(string $chanel, array $data): void
    {
        if (strlen($chanel) <= 0) {
            return;
        }

        try {
            $dataEncoded = Json::encode($data);

            $this->getRedis()->rpush($chanel, [$dataEncoded]);
        } catch (EncodingException $exception) {

        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Extract data from chanel.
     *
     * @param   string  $chanel             Chanel.
     *
     * @return  array                       Data.
     * @throws  RuntimeException            Process error.
     ************************************************************************/
    private function extractData(string $chanel): array
    {
        if (strlen($chanel) <= 0) {
            throw new RuntimeException('no chanel value caught');
        }

        try {
            $data           = (string) $this->getRedis()->lpop($chanel);
            $dataDecoded    = Json::decode($data);

            return $dataDecoded;
        } catch (DecodingException $exception) {
            return [];
        } catch (Throwable $exception) {
            throw new RuntimeException($exception->getMessage(), 0, $exception);
        }
    }
    /** **********************************************************************
     * Get redis client.
     *
     * @return  RedisClient                 Redis client.
     * @throws  RuntimeException            Redis client unreachable.
     ************************************************************************/
    private function getRedis(): RedisClient
    {
        if (!$this->redis) {
            try {
                $this->redis = new RedisClient([
                    'scheme'    => 'tcp',
                    'host'      => '127.0.0.1',
                    'port'      => 6379
                ]);
            } catch (Throwable $exception) {
                throw new RuntimeException($exception->getMessage(), 0, $exception);
            }
        }

        return $this->redis;
    }
}