<?php
declare(strict_types=1);

namespace ServiceSystem\Http;

use function strlen;
use function function_exists;
use function curl_init;
use function curl_setopt_array;
use function curl_exec;
use function curl_error;

use const CURL_HTTP_VERSION_1_1;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_HTTP_VERSION;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_FORBID_REUSE;
use const CURLOPT_TIMEOUT;
use const CURLOPT_URL;
/** ***********************************************************************************************
 * CURL request class.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class CurlRequest implements RequestInterface
{
    /** **********************************************************************
     * Make HTTP request.
     *
     * @param   string  $uri                URI.
     * @param   string  $method             Method.
     * @param   string  $data               Post data.
     * @param   bool    $wait               Wait for response.
     *
     * @return  string                      HTTP response.
     * @throws  RequestException            Request error.
     ************************************************************************/
    public static function make(
        string  $uri,
        string  $method = 'GET',
        string  $data   = '',
        bool    $wait   = true
    ): string {
        if (strlen($uri) <= 0) {
            throw new RequestException('request URI is empty');
        }
        if (!function_exists('curl_version')) {
            throw new RequestException('curl PHP module is not exist');
        }

        $curl       = curl_init();
        $curlParams = [
            CURLOPT_URL             => $uri,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER  => true
        ];

        if (!$wait) {
            $curlParams[CURLOPT_TIMEOUT]        = 2;
            $curlParams[CURLOPT_FORBID_REUSE]   = true;
        }

        if ($method !== 'GET') {
            $curlParams[CURLOPT_CUSTOMREQUEST] = $method;

            if (strlen($data) > 0) {
                $curlParams[CURLOPT_POSTFIELDS] = [$data];
            }
        }

        if ($curl === false) {
            throw new RequestException('curl init failed with unknown error');
        }

        curl_setopt_array($curl, $curlParams);

        $response = curl_exec($curl);
        if ($response === false) {
            $curlError  = curl_error($curl);
            $message    = strlen($curlError) > 0 ? $curlError : 'curl executing failed with unknown error';

            throw new RequestException($message);
        }

        return (string) $response;
    }
}