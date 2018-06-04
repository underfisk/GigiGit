<?php
namespace GitCore;

/**
 * This will be the base for actions and commands where it will validate, have common configurations etc
 * Also this will save the information such as client_id, client_secret and client will be able to set it or not
 */
class GitBase
{
    /**
     * Initializes the our api user data if it has something
     * 
     * @return void
     */
    protected function __construct($data = null)
    {
        if (isset($data))
        {
            $this->client_id = $data['client_id'];
            $this->client_secret = $data['client_secret'];
            $this->scope = $data['scope'];
            $this->debug_level = $data['debug_level'] == 1 ? 1 : 0;
        }
    }

    /**
     * Returns the github api url with basic user actions
     * 
     * @var string
     */
    protected $api_url = "https://api.github.com/";

    /**
     * Returns the github api rate_limit url
     * 
     * @var string
     */
    protected $rate_url = "https://api.github.com/rate_limit";

    /**
     * Returns the url to retrieve access token via Post
     * 
     * @var string
     */
    protected $token_url = "https://github.com/login/oauth/access_token";

    /**
     * Returns the github oauth client_id given when the app is created
     * Backlink - https://github.com/settings/applications/7
     * 
     * @var string
     */
    protected $client_id = null;
    
    /**
     * Returns the github oauth client_secret(As the name says we should be careful revealing this!) when the app is created
     * Backlink - https://github.com/settings/applications/
     * 
     * @var string
     */
    protected $client_secret = null;

    /**
     * Returns the app scope, this can be changed according to the official documentation (Be careful when you change this!)
     * Documentation - https://developer.github.com/apps/building-oauth-apps/scopes-for-oauth-apps/
     * Tip: If you want to add more than 1 scope you have to add a escaped space meaning this: %20 so it will be scopes=scope1%20scope2%20scope3..
     * 
     * @var string
     */
    protected $scope = null;

    /**
     * Returns the debug level, 0 - debug off release environment, 1- debug on development environment
     * e.g in level 0 it never shows an error but the script execution dies
     * e.g in level 1 it shows the exception/error
     * 
     * @var int
     */
    protected $debug_level = 0;

    /**
     * Returns the link used for authorization like login
     * 
     * @return void
     */
    public function AuthorizeLink()
    {
        if (empty($this->scope) || empty($this->client_id))
            die('Please verify your application configs, scope or client_id are missing');

        return 'https://github.com/login/oauth/authorize?scope='.$this->scope.'&client_id='.$this->client_id;
    }

    /**
     * Returns the revoke URL where the user can choose whether he want's to continue allowing us using github
     * 
     * @return string
     */
    public function RevokeLink()
    {
        return 'https://github.com/settings/connections/applications/';
    }

}