<?php

namespace GitCore\Exceptions;
require_once (APPPATH . 'third_party/GigiGit/Exceptions/GitExceptionInterface.php');

use GitCore\Exceptions\GitExceptionInterface as IException;
use GitCore\GitClient as GitClient;

//TODO: refactor all of this and format the errors
class ApiLimitExceedException extends \RuntimeException implements IException
{
    private $limit = null;
    private $reset = null;

    /**
     * Calls the parent constructor (\RuntimeException) and updates every data
     */
    public function __construct($limit = 5000, $reset = 1800, $code = 0, \RuntimeException $previous = null)
    {
        $this->limit = (int) $limit;
        $this->reset = (int) $reset;
        
        if (GitClient::$_debug == 1)
            parent::__construct(sprintf('You have reached GitHub hourly limit! Actual limit is: %d and the reset time is: %s', $limit,gmdate('Y-m-d H:i:s',$reset)), $code, $previous);
        else
            exit();
    }

    /**
     * Displays the error according to the format we want
     */
    public function __toString() 
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     * Returns the exception only
     * 
     * @return String
     */
    public function getException()
    {
        return $this->message;
    }

    /**
     * Returns the limit of request
     * 
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * Returns the reset time in UTC
     * 
     * @return UnixTime
     */
    public function getResetTime()
    {
        return $this->reset;
    }
}