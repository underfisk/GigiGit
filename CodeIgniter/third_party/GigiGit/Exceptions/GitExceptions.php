<?php

namespace GitCore\Exceptions;

/**
 * Abstract class which filter the git exception if it exists
 */
//TODO: When we reach the API user limit we'll have to figure out a way 
//Also see any alternative to don't block at 60 but yes at 5000 per hour per user
abstract class GitExceptions
{
    /**
     * Receives the message key from json response
     * TODO: Find the rest of possible exceptions
     */
    static private $github_exceptions = array(
        "Requires authentication", // The client_id and/or client_secret passed are incorrect.
        "Bad credentials", //Authenticating with invalid credentials will return 401 Unauthorized
        "Problems parsing JSON", //Throw 400 error code
        "Body should be a JSON object", //Throw 400 error code
        "Validation Failed", // Throw 422 Unprocessable Entity
        "bad_verification_code", //The code passed is incorrect or expired.
        "incorrect_client_credential", // The client_id and/or client_secret passed are incorrect,
        "You have triggered an abuse detection mechanism and have been temporarily blocked from content creation. Please retry your request again later.",
        "Maximum number of login attempts exceeded. Please try again later.",
        "Problems parsing JSON",
        "Body should be a JSON object",
        "Validation Failed", //some field is badly sent
        "redirect_uri_mismatch", //he redirect_uri MUST match the registered callback URL for this application.,
        "Not Found", //Invalid api controller or params
        "Git Repository is empty." //We have nothing inside it, no commits, no files etc
    );

    /**
     * Returns if is a github exception or not
     */
    public static function isException($message)
    {
        //Special case because they generate our IP dynamicly
        if (strpos($message,"API rate limit exceeded") !== false)
            return true;

        if (in_array($message,self::$github_exceptions))
            return true;
        else
            return false;
    }
}