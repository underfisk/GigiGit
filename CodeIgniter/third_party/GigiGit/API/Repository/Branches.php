<?php

namespace API\Repository;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use \GitCore\GitBase as GitBase;
use \API\GigiActionsInterface as IActions;
use \HttpRequest\Request as Request;

class Branches extends GitBase implements IActions
{
    public function single($owner,$repo,$branch_name)
    {
        return Request::get($this->api_url . 'repos/'.rawurlencode($owner).'/'.rawurlencode($repo).'/branches/'.rawurlencode($branch_name).'?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function all($owner,$repo)
    {
        return Request::get($this->api_url . 'repos/'.rawurlencode($owner).'/'.rawurlencode($repo).'/branches?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}