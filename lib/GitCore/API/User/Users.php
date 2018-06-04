<?php

namespace API\User;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Users extends GitBase implements IActions
{

    public function find($nameOrToken,$byToken = false)
    {
        if (!$byToken)
            return Request::get($this->api_url . 'users/' . rawurlencode($nameOrToken) . '?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
        else
            return Request::get($this->api_url . 'user?access_token='. $nameOrToken. '&client_id='.$this->client_id.'&client_secret='.$this->client_secret); 
    }

    public function search($name)
    {
        return Request::get($this->api_url . 'search/users?q=' . rawurlencode($name) . '?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function all($perPage = 100)
    {
        return Request::get($this->api_url . 'users?per_page=?'.$perPage.'&client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}