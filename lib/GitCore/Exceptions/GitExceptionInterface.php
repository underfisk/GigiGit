<?php

namespace GitCore\Exceptions;
require_once (APPPATH . 'third_party/GigiGit/GitClient.php');
interface GitExceptionInterface
{
    /**
     * Override display function to format as pretended
     */
    public function __toString();

    /**
     * Displays the exception error message only
     */
    public function getException();


}