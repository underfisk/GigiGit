<?php

namespace GitCore;

//Load required files only once
require_once APPPATH . 'third_party/GigiGit/GitBase.php';

//API Files
require_once APPPATH . 'third_party/GigiGit/API/User/Users.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Repos.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Comments.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Commits.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Branches.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Collaborators.php';
require_once APPPATH . 'third_party/GigiGit/API/Repository/Contents.php';

//Exception Files
require_once APPPATH . 'third_party/GigiGit/Exceptions/RuntimeException.php';
require_once APPPATH . 'third_party/GigiGit/Exceptions/ValidationFailedException.php';
require_once APPPATH . 'third_party/GigiGit/Exceptions/ApiLimitExceedException.php';
require_once APPPATH . 'third_party/GigiGit/Exceptions/InvalidDataFormatException.php';

//Http Classes
use API\Repository\Branches as API_Branches;

//API Classes
use API\Repository\Collaborators as API_Collaborators;
use API\Repository\Comments as API_Comments;
use API\Repository\Commits as API_Commits;
use API\Repository\Contents as API_Contents;
use API\Repository\Repos as API_Repos;
use API\User\Users as API_Users;
use GitCore\Exceptions\InvalidDataFormatException as InvalidDataFormatException;

//Exception Classes
use HttpRequest\Request as Request;

/**
 * Class will be used to stand for example when we want to retrieve the listOfCommits, this class will be responsible of creating the array of commit objects and return it
 * The user access token will be set here because its separated of gitbase (core), also git access token will be stored in a session var, cookie or database
 *  Class will also be responsible for set git base data such as core access
 */
class GitClient extends GitBase
{

    /**
     * Singleton debug level to be accessed in another classes without instance
     *
     * @var int
     */
    public static $_debug = 0;

    /**
     * Instance of API_profile object
     *
     * @var API_Profile
     */
    public $users = null;

    /**
     * Instance of API_Commit
     *
     * @var API_Commits
     */
    public $commits = null;

    /**
     * Instance of API_Repos
     *
     * @var API_Repos
     */
    public $repos = null;

    /**
     * Instance of API_Branches
     *
     * @var API_Branches
     */
    public $branches = null;

    /**
     * Instance of API_Comments
     *
     * @var API_Comments
     */
    public $comments = null;

    /**
     * Instance of API_Collaborators
     *
     * @var API_Collaborators
     */
    public $collaborators = null;

    /**
     * Instance of API_Contents
     *
     * @var API_Contents
     */
    public $contents = null;

    /**
     * Protected Instance of Codeigniter
     *
     * @var CI_Instance
     */
    protected $CI = null;

    /**
     * Initializes the configuration data here and in the parent construtor and also
     * creates an instance for the functionallitys
     */
    public function __construct($configData = null)
    {
        $this->CI = &get_instance(); //test this after
        parent::__construct($configData); //Initialize the data from gitbase because we need it
        self::$_debug = $this->debug_level; //Initialize after being readed from config.json in GitBase

        //API Instances
        $this->users = new API_Users();
        $this->commits = new API_Commits();
        $this->repos = new API_Repos();
        $this->comments = new API_Comments();
        $this->branches = new API_Branches();
        $this->collaborators = new API_Collaborators();
        $this->contents = new API_Contents();
    }

    /**
     * Returns the user token from database if already exists or we add a row in github_tokens
     * 
     * @param mixed $code
     * @param int uid
     * 
     * @return String
     */
    public function Authenticate($uid, $code, $force_code_clean = false)
    {
        if (!$user_id)
            return;

        $dbtoken = $this->CI->db->query('SELECT * FROM github_tokens WHERE user_id = ? ',$uid);
        if (!$dbtoken)
        {
            $requestMessage = Request::Post($this->token_url, array(
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'code' => $code,
            ));
            
            //Also make sure our content isset and the type is what we are exception, json
            if ($requestMessage->getContent() != null && $requestMessage->getType() == "Json") 
            {
                $requestJson = json_decode($requestMessage->getContent());

                if (array_key_exists('access_token', $requestJson)) 
                {
                    $this->CI->session->set_userdata('gigi_user_token', $requestJson->access_token);
                    return $requestJson->access_token;
                }
            } 
            else 
            {
                throw new InvalidDataFormatException("API information is not in JSON Format!");
                return;
            } 
        }
        else
            return $dbtoken;
    }

    /**
     * Returns the profile information according to the given access_token
     * 
     * @param mixed $access_token
     * 
     * @return Profile
     */
    public function RetrieveProfile($access_token)
    {
        $userData = Request::Get($this->api_url . 'user?access_token=' . $access_token . '&client_id=' . $this->client_id . '&client_secret=' . $this->client_secret);
        if ($userData->getContent() != null && $userData->getType() == "Json") 
        {
            return $userData;
        } 
        else 
        {
            throw new InvalidDataFormatException("API information is not in JSON Format!");
            return;
        }

        return null;
    }

    /**
     * Returns the github authorization URL stored in GitBase
     * 
     * @return string
     */
    public function GetAuthorizationURL()
    {
        return $this->AuthorizeLink();
    }

    /**
     * Returns the github revoke URL stored in GitBase
     * 
     * @return string
     */
    public function GetRevokeURL()
    {
        return $this->RevokeLink();
    }

}
