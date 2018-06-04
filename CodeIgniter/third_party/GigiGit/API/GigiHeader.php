<?php

namespace API;
class GigiHeader
{
    private $http_status = null;
    private $server_host = null;
    private $date = null;
    private $content_type = null;
    private $content_length = null;
    private $status_code = null;
    private $rate_limit = null;
    private $rate_remaining = null;
    private $rate_reset = null;
    private $cache_control = null;
    private $last_modified = null;
    private $etag = null;

    public function __construct($attrs)
    {
        if (isset($attrs) && sizeof($attrs) >= 1)
        {
            foreach($attrs as $key => $value)
            {
                $this->$key = $value; 
            }
        }
    }
    

    public function getHttpStatus()
    {
        return isset($this->http_status) ? $this->http_status : null;
    }

    public function getServerHost()
    {
        return isset($this->server_host) ? $this->server_host : null;
    }

    public function getRateLimit()
    {
        return isset($this->rate_limit) ? $this->rate_limit : null;
    }
    
    public function getRateRemaining()
    {
        return isset($this->rate_remaining) ? $this->rate_remaining : null;
    }

    public function getRateReset()
    {
        return isset($this->rate_reset) ? $this->rate_reset : null;
    }

    public function getETAG()
    {
        return isset($this->etag) ? $this->etag : null;    
    }

    public function getLastModified()
    {
        return isset($this->last_modified) ? $this->last_modified : null;    
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
}