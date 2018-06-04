<?php

namespace API\Repository;
require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Repos extends GitBase implements IActions
{

    public function single($name,$repo_name)
    {
        return Request::get($this->api_url . 'repos/' . rawurlencode($name). '/' . rawurlencode($repo_name).'?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function search($repo_name)
    {
        return Request::get($this->api_url . 'search/repositories?q=' . rawurlencode($repo_name) . '&client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function all($nameOrToken,$byToken = false)
    {
        if (!$byToken)
            return Request::get($this->api_url . 'users/' . rawurlencode($nameOrToken). '/repos?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
        else
            return Request::get($this->api_url . 'user/repos?access_token=' . rawurlencode($nameOrToken). '&client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}