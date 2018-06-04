<?php

namespace API;
require_once (APPPATH . 'third_party/GigiGit/GitBase.php');
require_once (APPPATH . 'third_party/GigiGit/HttpRequest/Request.php');

interface GigiActionsInterface
{

    /**
     * Requests api.github.com with the given word and limits the results we return
     * @param string $word - keyword to find
     * @param int $limit - limits the results to return
     * @return RequestBaseMessage
     */
    //public function search($word,$limit);

    /**
     * Requests api.github.com with the word to find
     * @return RequestBaseMessage
     */
   // public function find($wordOrToken,$byToken);

    /**
     * Requests api.github.com for all the content according to the git object
     * @param int $limit - limits the results to return 
     * @return RequestBaseMessage
     */
  //  public function all($limit);


}