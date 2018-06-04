<?php

namespace API\Repository;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Collaborators extends GitBase implements IActions
{

    public function all($owner,$repo,$access_token)
    {
       return Request::get($this->api_url . 'repos/' . rawurlencode($owner) . '/' . rawurlencode($repo) . '/collaborators?access_token='.$access_token.'&client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}