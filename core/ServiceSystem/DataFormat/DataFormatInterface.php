<?php
declare(strict_types=1);

namespace ServiceSystem\DataFormat;
/** ***********************************************************************************************
 * Data encoding/decoding interface.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
interface DataFormatInterface
{
    /** **********************************************************************
     * Encode data.
     *
     * @param   mixed $data                 Data.
     *
     * @return  string                      Encoded data.
     * @throws  EncodingException           Encoding process error.
     ************************************************************************/
    public static function encode($data): string;
    /** **********************************************************************
     * Decode data.
     *
     * @param   string $data                Encoded data.
     *
     * @return  mixed                       Decoded data.
     * @throws  DecodingException           Decoding process error.
     ************************************************************************/
    public static function decode(string $data);
}