<?php

namespace API\Repository;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Commits extends GitBase implements IActions
{

    public function single($username,$repo_name,$commit_sha)
    {
        return Request::get($this->api_url . 'repos/'.rawurlencode($username).'/'.rawurlencode($repo_name).'/commits/'.rawurlencode($commit_sha).'?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function all($username,$repo_name)
    {
        return Request::get($this->api_url . 'repos/'.rawurlencode($username).'/'.rawurlencode($repo_name).'/commits?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}