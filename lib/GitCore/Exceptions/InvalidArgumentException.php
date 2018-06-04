<?php

namespace GitCore\Exceptions;

require_once (APPPATH . 'third_party/GigiGit/Exceptions/GitExceptionInterface.php');

use GitCore\Exceptions\GitExceptionInterface as IException;
use GitCore\GitClient as GitClient;

//TODO: refactor all of this and format the errors
class InvalidArgumentException extends \RuntimeException implements IException
{
    //Enumerator of data types
    const _STRING = 0;
    const _INT = 1;
    const _FLOAT = 2;
    const _DOUBLE = 3;
    const _LONG = 4;
    const _BYTE = 5;

    /**
     * Calls the parent constructor (\RuntimeException) and updates every data
     */
    public function __construct($field, $typeRequired ,$code = 0, \RuntimeException $previous = null)
    {
        if (GitClient::_debug == 1)
            parent::__construct(sprintf('(%s) field has to be of type (%s)',$field,$this->getDataType($typeRequired)),$code,$previous);
        else
            exit();
    }

    private function getDataType($dt)
    {
        switch($dt)
        {
            case _STRING:
                return "String";
            case _INT:
                return "Integer";
            case _FLOAT:
                return "Float";
            case _DOUBLE:
                return "Double";
            case _LONG:
                return "Long";
            case _BYTE:
                return "Byte";
        }
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