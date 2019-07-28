<?php
declare(strict_types=1);

namespace ServiceSystem\Http;
/** ***********************************************************************************************
 * HTTP request interface.
 *
 * @package ServiceSystem
 * @author  Hvorostenko
 *************************************************************************************************/
interface RequestInterface
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
    ): string;
}