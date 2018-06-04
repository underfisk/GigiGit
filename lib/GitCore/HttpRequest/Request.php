<?php

namespace HttpRequest;

require_once (APPPATH . 'third_party/GigiGit/API/GigiHeader.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/RuntimeException.php');
require_once (APPPATH . 'third_party/GigiGit/HttpRequest/RequestBaseMessage.php');


use API\GigiHeader as GigiHeader;
use GitCore\Exceptions\RuntimeException as RuntimeException;
use HttpRequest\RequestBaseMessage as RequestBaseMessage;

//Constant user agent
define('USER_AGENT','GigiGit');

/**
 * @link    http://php.net/manual/en/book.curl.php
 * 
 * @author  Enigma
 */
abstract class Request extends RequestBaseMessage
{
    private static function ShowHeaders($title,$data,$hasZeroPos = false)
    {
        echo '<hr>';
        echo '<h3>' . $title . '</h3>';
        foreach($data as $key => $value)
        {
            echo '</br>' .$key . ': ' . ($hasZeroPos ? $value[0] : $value);
        }
        
        echo '</hr>';
      
    }


    /**
     * TODO: Refactor this and maybe we'll get all headers and instantiate them all
     * Resends a request to a specific URL and just gahters the headers and not the body
     */
    private static function CreateHeaders($headers)
    {
        $data = [];
        //self::ShowHeaders('Headers from Request',$headers,true);
        foreach($headers as $key => $value)
        {
            if ($key == 'x-ratelimit-remaining')
                $data['rate_remaining'] = $value[0];
            else if ($key == "x-ratelimit-limit")
                $data['rate_limit'] = $value[0];
            else if ($key == 'x-ratelimit-reset')
                $data['rate_reset'] = $value[0];
            else if ($key == 'server')
                $data['server_host'] = $value[0];
            else if ($key == 'date')
                $data['date'] = $value[0];
            else if ($key == 'content-type')
                $data['content_type'] = $value[0];
            else if ($key == 'content-length')
                $data['content_length'] = $value[0];
            else if ($key == 'status')
                $data['http_status'] = $value[0];
            else if ($key == 'cache-control')
                $data['cache_control'] = $value[0];
            else if ($key == "etag")
                $data['etag'] = $value[0];
            else if ($key == "last-modified")
                $data['last_modified'] = $value[0];
        }

       // echo '</hr>';

        //self::ShowHeaders('Headers for GigiHeader Object',$data);
        return new GigiHeader($data);
    }


    public static function Get($url)
    {
        if (empty($url))
            return;

        //Initialize a curl object
        $curl = curl_init();
        $headers = [];
        //Accept JSON header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_VERBOSE => 1,
            CURLOPT_USERAGENT => USER_AGENT,
            CURLOPT_HEADERFUNCTION => function($curl, $header) use (&$headers)
            {
              $len = strlen($header);
              $header = explode(':', $header, 2);
              if (count($header) < 2) // ignore invalid headers
                return $len;
          
              $name = strtolower(trim($header[0]));
              if (!array_key_exists($name, $headers))
                $headers[$name] = [trim($header[1])];
              else
                $headers[$name][] = trim($header[1]);
          
              return $len;
            }
        ));
        
        // Send the request & save response to $resp
        $response =  utf8_decode(curl_exec($curl));

        //curl error?
        if(!curl_exec($curl)){
            throw new RuntimeException('Error: "' . curl_error($curl),curl_errno($curl));
        }

        //Status code
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Get headers and size from this request
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        // Close request to clear up some resources
        curl_close($curl);
        return new RequestBaseMessage($response,$http_code,self::CreateHeaders($headers),$header_size);
    }

    /**
     * Returns a response of type RequestBaseMessage
     */
    public static function Post($url,$params = array())
    {
        if (empty($url))
            return;

        if (!isset($params) || sizeof($params) <= 0)
            return false;
     
        //Initialize a curl object
        $curl = curl_init();
        $headers = [];
        
        //Accept header
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));

        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_VERBOSE => 1,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => USER_AGENT,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HEADERFUNCTION => function($curl, $header) use (&$headers)
            {
              $len = strlen($header);
              $header = explode(':', $header,2);
              if (count($header) < 2) // ignore invalid headers
                return $len;
          
              $name = strtolower(trim($header[0]));

              if (!array_key_exists($name, $headers))
                $headers[$name] = [trim($header[1])];
              else
                $headers[$name][] = trim($header[1]);
          
              return $len;
            }
        ));

        // Send the request & save response to $resp
        $response =  utf8_decode(curl_exec($curl));


        //curl error?
        if(!curl_exec($curl)){
            throw new RuntimeException('Error: "' . curl_error($curl),curl_errno($curl));
        }

        //Status code
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        // Get headers and size from this request
        $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        // Close request to clear up some resources
        curl_close($curl);
        
        return new RequestBaseMessage($response,$http_code,self::CreateHeaders($headers),$header_size);

    }

}