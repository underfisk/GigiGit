<?php
/**
 * This file is just a simple example
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);

//Requires session in order to save the token (temporary)
session_start();

require_once 'lib/GitCore/GitClient.php';

//Require file and define namespace class
//require_once 'GitCore/GitClient.php';
use GitCore\GitClient as GitClient;

//Create a new GitClient Object (Mainly for git operations)
$appClient = new GitClient();


//Render important links or just gather it
//TODO: fix revoke https://developer.github.com/v3/oauth_authorizations/#reset-an-authorization
//When we receive 202 or whatever ok from the headers we will unset the gigi_user_token
echo "<a href=".$appClient->GetAuthorizationURL()."> Authorizate our app </a></br>";
echo "<a href=".$appClient->GetRevokeURL()."> Revoke our app </a>"; 


/**
 * TODO: Per Page get parameter to do in some classes if needed and also Pagination headers / Make it work
 * User token = OK
 * Repository Class - OK
 * User Class - OK
 * Branches Class - OK
 * Comments Class - OK (Single not tested due to don't have a ref)
 * Collaborators Class - OK
 * Contents Class - OK base but the rest need to be implemented
 * 
 */
//use this way to make sure we are being redirected from github and to clean the url
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['code']))
{
    $code = $_GET['code'];

    //Retrieves the user token to be possible initialize the userObject
    $userToken = $appClient->Authenticate($code);
    echo '</br>Access Token: ' . $userToken;

    $test = $appClient->contents->files('underfisk','GigiGit');
    echo 'From the contents find: ' . $test->getContent();
}


