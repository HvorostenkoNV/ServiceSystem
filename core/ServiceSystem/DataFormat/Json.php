<?php
declare(strict_types=1);

namespace ServiceSystem\DataFormat;

use function strlen;
use function json_encode;
use function json_decode;
use function json_last_error;
use function json_last_error_msg;

use const JSON_ERROR_NONE;
/** ***********************************************************************************************
 * Json data encoding/decoding class.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
class Json implements DataFormatInterface
{
    /** **********************************************************************
     * Encode data.
     *
     * @param   mixed $data                 Data.
     *
     * @return  string                      Encoded data.
     * @throws  EncodingException           Encoding process error.
     ************************************************************************/
    public static function encode($data): string
    {
        $dataEncoded = json_encode($data);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonError  = json_last_error_msg();
            $message    = strlen($jsonError) > 0 ? $jsonError : 'unknown error';

            throw new EncodingException($message);
        }

        return $dataEncoded;
    }
    /** **********************************************************************
     * Decode data.
     *
     * @param   string $data                Encoded data.
     *
     * @return  mixed                       Decoded data.
     * @throws  DecodingException           Decoding process error.
     ************************************************************************/
    public static function decode(string $data)
    {
        $dataDecoded = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $jsonError  = json_last_error_msg();
            $message    = strlen($jsonError) > 0 ? $jsonError : 'unknown error';

            throw new DecodingException($message);
        }

        return $dataDecoded;
    }
}