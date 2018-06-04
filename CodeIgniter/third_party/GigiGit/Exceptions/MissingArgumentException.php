<?php

namespace GitCore\Exceptions;

require_once (APPPATH . 'third_party/GigiGit/Exceptions/GitExceptionInterface.php');

use GitCore\Exceptions\GitExceptionInterface as IException;
use GitCore\GitClient as GitClient;

//TODO: refactor all of this and format the errors
class MissingArgumentException extends \RuntimeException implements IException
{
    /**
     * Calls the parent constructor (\RuntimeException) and updates every data
     */
    public function __construct($required,$code = 0, \RuntimeException $previous = null)
    {
        if (GitClient::$_debug == 1)
            parent::__construct(sprintf('One or more of required ("%s") parameters is missing!',$required),$code,$previous);
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
}