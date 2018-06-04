<?php

namespace HttpRequest;


require_once (APPPATH . 'third_party/GigiGit/HttpRequest/RequestCode.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/GitExceptions.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/RuntimeException.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/ErrorException.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/AuthenticationException.php');
require_once (APPPATH . 'third_party/GigiGit/Exceptions/ApiLimitExceedException.php');

use HttpRequest\RequestCode as rcode;
use GitCore\Exceptions\GitExceptions as _exception;
use GitCore\Exceptions\RuntimeException as RuntimeException;
use GitCore\Exceptions\ErrorException as ErrorException;
use GitCore\Exceptions\AuthenticationException as AuthenticationException;
use GitCore\Exceptions\ApiLimitExceedException as ApiLimitExceedException;

/**
* Request message returned when a request is made
*/
class RequestBaseMessage
{
    /**
     * Returns if the message type (json,raw,etc)
     */
    private $type = null;

    /**
     * Returns the content of httprequest or null if an error occur
     */
    private $content = null;

    /**
     * Returns the http status code translated or not
     */
    private $status_code = null;

    /**
     * Returns the exception message if some occur
     */
    private $exception = null;

    /**
     * Returns the headers 
     */
    private $headers = null;

    /**
     * Returns the header size
     */
    private $headers_size = null;

    /**
     * Define 0 if you want just the meaning of the error or 1 if you also want the code, 2 if you just want the code
     * Soon: change to our composer or a constant class
     */
    private $status_code_format = 2;

    /**
     * Defines 0 if you just want to show a non error message and 1 if you want the response directly
     * Soon: change to our composer or constant class
     */
    private $enable_error_response = 1;


    public function __construct($_response,$http_code,$_headers,$_headers_size)
    {
        if (isset($_response))
        {
            //Lets assign in our object this 
            if (array_key_exists((int)$http_code, rcode::$http_status_codes))
                if ($this->status_code_format == 1)
                    $this->status_code = "($http_code): " . rcode::$http_status_codes[(int)$http_code];
                else if ($this->status_code_format == 2)
                    $this->status_code = $http_code;
                else
                    $this->status_code = rcode::$http_status_codes[(int)$http_code];
            else
                $this->status_code = "Undefined";

            //Last thing to verify because if is a git exception we clean the content
            $this->headers = $_headers;
            $this->headers_size = $_headers_size; 
            $this->type = $this->ResponseType($_response);
            $this->handleResponse($_response,$this->status_code);

        }   
    }


    private function handleResponse($content,$status_code)
    {

        /**
         * TODO: Before we set the content we check headers if the item has pagination
         * and if so we make every pagination content in 1 only json
         * OR
         * Add attr with the page count
         */

        //echo '</br><hr>';
       // echo '<h3> The Handle Response Content </h3>';
       // var_dump($content);
       // echo '</hr>';

        //means not modified since the last request so
        if ($status_code == 304)
        {
            //not done yet
        }

        //Valid status code without error?
        if ($status_code < 400 || $status_code > 600) 
        {
            $c = json_decode($content,true);
            if (json_last_error() === JSON_ERROR_NONE)
            {
                //Make sure in the correct http_codes we already make sure it's not an error
                if (isset($c['error']))
                {
                    //Okay we got an error because something is set in error 
                    throw new ErrorException('('. $c['error']. ') ' . $c['error_description'],$status_code);
                    return;
                }
                else
                {
                    $this->content = $content;
                    return;
                }
            }
            else
            {
                throw new InvalidDataFormatException("API information is not in JSON Format!");
                return;
            }
        }
        
        //$this->getHeaderKey('X-GitHub-OTP');
        //2nd factor not on for now
        //To be tested soon!
        /*if ($status_code === 401)
        {
            if ($this->getHeaderKey('X-GitHub-OTP'))
            {
                throw new TwoFactorAuthenticationRequiredException('type here');
                return;
            }
        }*/


        //Force to be returned array
        $data = json_decode($content,true);

        if (is_array($data))
        {
            if ($status_code == 400 && isset($data['message']))
            {
                throw new ErrorException($data['message'],$status_code);
                return;
            }
            else if ($status_code == 401 && isset($data['message']))
            {
                if ($data['message'] == 'Bad credentials')
                {
                    //delete now from session but soon from db because means it's expired or wrong token to be able to gather a new one
                    if (isset($_SESSION['gigi_user_token']))
                        unset($_SESSION['gigi_user_token']);
                }
                throw new AuthenticationException($data['message'],$status_code);
                return;
            }
            else if ($status_code == 404 && isset($data['message']))
            {
                throw new AuthenticationException($data['message'],$status_code);
                return;
            }
            else if ($status_code == 404)
            {
                //make sure it is a error
                if (isset($data['error']))
                {
                    throw new ErrorException($data['error'],404);
                    return;
                }
            }
            else if ($status_code == 422 && isset($data['errors']))
            {
                $errors = [];
                
                foreach ($data['errors'] as $error) 
                {
                    switch ($error['code']) 
                    {
                        case 'missing':
                            $errors[] = sprintf('The %s %s does not exist, for resource "%s"', $error['field'], $error['value'], $error['resource']);
                            break;
                        case 'missing_field':
                            $errors[] = sprintf('Field "%s" is missing, for resource "%s"', $error['field'], $error['resource']);
                            break;
                        case 'invalid':
                            if (isset($error['message'])) {
                                $errors[] = sprintf('Field "%s" is invalid, for resource "%s": "%s"', $error['field'], $error['resource'], $error['message']);
                            } else {
                                $errors[] = sprintf('Field "%s" is invalid, for resource "%s"', $error['field'], $error['resource']);
                            }
                            break;
                        case 'already_exists':
                            $errors[] = sprintf('Field "%s" already exists, for resource "%s"', $error['field'], $error['resource']);
                            break;
                        default:
                            $errors[] = $error['message'];
                            break;
                        }
                }

                throw new ValidationFailedException('Validation Failed: '. implode(',',$errors),422);
                return;
            }
        }

        throw new RuntimeException('GitHub Response: ' . (isset($data['message']) ? $data['message'] : 'Undefined'),404);
        return;
    }

    /**
     * Returns the response type by receiving the response
     */
    private function ResponseType($r)
    {
        //Is the response null or blank ? 
        if (empty($r))
        {
            if ($this->enable_error_response == 1)
                return $r;
            else
                return "Blank Response";
        }
        
        //Is it json ? lets check
        $j = json_decode($r);
        if (json_last_error() === JSON_ERROR_NONE)
            return "Json";
        else
            return "RawText";

    }
    
    public function getStatusCode()
    {
        return isset($this->status_code) ? $this->status_code : null;
    }

    public function getContent()
    {
        return isset($this->content) ? $this->content : null;
    }

    public function getType()
    {
        return isset($this->type) ? $this->type : null;
    }

    public function getException()
    {
        return isset($this->exception) ? $this->exception : null;
    }

    public function getHeaders()
    {
        return isset($this->headers) ? $this->headers : null;
    }

    public function __toArray()
    {
        if (sizeof(get_object_vars($this)) >= 1)
        {
            $arr = json_decode(get_object_vars($this));
            if (json_last_error() === JSON_ERROR_NONE)
                return $arr;
        }

        return null;
    }

    public function __toJSON()
    {
        if (sizeof(get_object_vars($this)) >= 1)
        {
            $js = json_encode(get_object_vars($this));
            if (json_last_error() === JSON_ERROR_NONE)
                return $js;
        }

        return null;
    }

    public function getHeaderKey($key)
    {
        foreach($headers_array as $k => $v)
        {
            if(isset($k[$key]))
            {
                return $v;
            }
        }
    }
    
}