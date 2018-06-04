<?php

namespace API\Repository;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Comments extends GitBase implements IActions
{
    public function single($owner, $repo, $ref)
    {
        return Request::get($this->api_url . 'repos/' .rawurlencode($owner). '/'. rawurlencode($repo) . '/commits/' . rawurlencode($ref) . '/comments?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    public function all($owner,$repo)
    {
       return Request::get($this->api_url . 'repos/' .rawurlencode($owner). '/'. rawurlencode($repo) . '/comments?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

}