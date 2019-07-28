<?php
declare(strict_types=1);

namespace ServiceSystem\Generator;

use RangeException;
use RuntimeException;
use ServiceSystem\{
    DataFormat\EncodingException,
    DataFormat\DecodingException,
    DataFormat\Json,
    Http\RequestException,
    Http\CurlRequest
};

use function usleep;
/** ***********************************************************************************************
 * Generator abstract class.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
abstract class AbstractGenerator implements GeneratorInterface
{
    private $sequenceSize   = 0;
    private $delay          = 1000000;
    private $chanel         = '';
    /** **********************************************************************
     * Set generated data sequence size.
     *
     * @param   int $size                   Generated data sequence size.
     *
     * @return  void
     * @throws  RangeException              Sequence size value is invalid.
     ************************************************************************/
    public function setSize(int $size): void
    {
        if ($size < 0) {
            throw new RangeException('value must be non negative number');
        }

        $this->sequenceSize = $size;
    }
    /** **********************************************************************
     * Set generation data delay.
     *
     * @param   int $delay                  Generation data delay.
     *
     * @return  void
     * @throws  RangeException              Delay value is invalid.
     ************************************************************************/
    public function setDelay(int $delay): void
    {
        if ($delay <= 0) {
            throw new RangeException('value must be greater than zero');
        }

        $this->delay = $delay;
    }
    /** **********************************************************************
     * Set generation data chanel for sending.
     *
     * @param   string $chanel              Generation data chanel for sending.
     *
     * @return  void
     ************************************************************************/
    public function setChanel(string $chanel): void
    {
        $this->chanel = $chanel;
    }
    /** **********************************************************************
     * Run data generation and sending process.
     *
     * @return  void
     * @throws  RuntimeException            Data generation or sending error.
     ************************************************************************/
    public function run(): void
    {
        $iterationsCount = $this->sequenceSize;

        while ($iterationsCount > 0) {
            usleep($this->delay);
            $iterationsCount--;

            $value          = $this->generate();
            $isLastValue    = $iterationsCount === 0;

            try {
                $this->sendDataWithHttp($this->chanel, [
                    'value'     => $value,
                    'isLast'    => $isLastValue
                ]);
            } catch (RuntimeException $exception) {
                throw new RuntimeException(
                    "data sending failed, {$exception->getMessage()}",
                    0,
                    $exception
                );
            }
        }
    }
    /** **********************************************************************
     * Send data using HTTP protocol.
     *
     * @param   string  $uri                URI.
     * @param   array   $data               Data.
     *
     * @return  void
     * @throws  RuntimeException            Sending failed.
     ************************************************************************/
    private function sendDataWithHttp(string $uri, array $data): void
    {
        try {
            $requestDataEncoded     = Json::encode($data);
            $response               = CurlRequest::make($uri, 'POST', $requestDataEncoded);
            $responseDataDecoded    = Json::decode($response);
            $hasSuccessAnswer       =
                isset($responseDataDecoded['success']) &&
                $responseDataDecoded['success'] === true;
            $errorAnswer            = (string) ($responseDataDecoded['error'] ?? 'unknown error');
        } catch (EncodingException $exception) {
            throw new RuntimeException(
                "request data encoding failed with error \"{$exception->getMessage()}\"",
                0,
                $exception
            );
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
    }
    /** **********************************************************************
     * Generate value.
     *
     * @return  mixed                       Value.
     ************************************************************************/
    abstract protected function generate();
}