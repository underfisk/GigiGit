<?php

namespace API\Repository;

require_once (APPPATH . 'third_party/GigiGit/API/GigiActionsInterface.php');

use GitCore\GitBase as GitBase;
use API\GigiActionsInterface as IActions;
use HttpRequest\Request as Request;

class Contents extends GitBase implements IActions
{
    /**
     * Searches for the readme file inside the given repo and returns the readme if exists or returns Not Found 404
     * 
     * @param string $owner the user who owns the repository
     * @param string $repo  the name of the repository
     * @param string $ref   reference to a branch or commit
     *  
     * @return RequestBaseMessage  
     */
    public function readme($owner,$repo,$ref = null)
    {
        if (!$ref)
            return Request::get($this->api_url . 'repos/' . rawurlencode($owner) .'/'.rawurlencode($repo). '/readme?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
        else
            return Request::get($this->api_url . 'repos/' . rawurlencode($owner) .'/'.rawurlencode($repo). '/readme?ref='.rawurlencode($ref).'&client_id='.$this->client_id.'&client_secret='.$this->client_secret); 
    }

    /**
     * Request every file from the given repo or repo and path for it. If there are any file it will return the files by pagination
     * 
     * @param string $owner the user who owns the repository
     * @param string $repo  the name of the repository
     * @param string $path  the path where we want to retrieve the files from
     * @param string $ref   reference to a branch or commit
     * 
     * @return RequestBaseMessage
     */
    public function files($owner,$repo,$path = null,$ref = null)
    {
        //TODO: Refactor this to add the ref attr
        if (!$path)
            return Request::get($this->api_url . 'repos/' . rawurlencode($owner) . '/' . rawurlencode($repo) . '/contents?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
        else
            return Request::get($this->api_url . 'repos/' . rawurlencode($owner) . '/' . rawurlencode($repo) . '/contents/'. rawurlencode($path).'?client_id='.$this->client_id.'&client_secret='.$this->client_secret);
    }

    /**
     * Add a header to the request if needed to accept more media types
     * Still TODO!
     */
    public function configure($bodyType = null)
    {
        if (!in_array($bodyType, ['html', 'object'])) {
            $bodyType = 'raw';
        }

       // $this->acceptHeaderValue = sprintf('application/vnd.github.%s.%s', $this->client->getApiVersion(), $bodyType);
        //return $this;
    }

    /**
     * Creates a new file in a repository.
     *
     * @link http://developer.github.com/v3/repos/contents/#create-a-file
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $path       path to file
     * @param string      $content    contents of the new file
     * @param string      $message    the commit message
     * @param null|string $branch     name of a branch
     * @param null|array  $committer  information about the committer
     *
     * @throws MissingArgumentException
     *
     * @return RequestBaseMessage information about the new file
     */
    public function create($owner,$repo,$path,$content,$message,$branch = null, array $committer = null)
    {

    }

    /**
     * Checks that a given path exists in a repository.
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $path       path of file to check
     * @param null|string $reference  reference to a branch or commit
     *
     * @return bool
     */
    public function exists($owner, $repo, $path, $ref = null)
    {

    }

    /**
     * Updates the contents of a file in a repository.
     *
     * @link http://developer.github.com/v3/repos/contents/#update-a-file
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $path       path to file
     * @param string      $content    contents of the new file
     * @param string      $message    the commit message
     * @param string      $sha        blob SHA of the file being replaced
     * @param null|string $branch     name of a branch
     * @param null|array  $committer  information about the committer
     *
     * @throws MissingArgumentException
     *
     * @return array information about the updated file
     */
    public function update($owner,$repo,$path,$content, $message, $sha, $branch = null, array $committer = null)
    {

    }

    /**
     * Deletes a file from a repository.
     *
     * @link http://developer.github.com/v3/repos/contents/#delete-a-file
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $path       path to file
     * @param string      $message    the commit message
     * @param string      $sha        blob SHA of the file being deleted
     * @param null|string $branch     name of a branch
     * @param null|array  $committer  information about the committer
     *
     * @throws MissingArgumentException
     *
     * @return array information about the updated file
     */
    public function remove($owner,$repo,$path,$message,$sha,$branch = null,array $committer = null)
    {

    }

    /**
     * Get content of archives in a repository.
     *
     * @link http://developer.github.com/v3/repos/contents/
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $format     format of archive: tarball or zipball
     * @param null|string $reference  reference to a branch or commit
     *
     * @return string repository archive binary data
     */
    public function archive($owner,$repo,$format,$ref)
    {

    }

     /**
     * Get the contents of a file in a repository.
     *
     * @param string      $username   the user who owns the repository
     * @param string      $repository the name of the repository
     * @param string      $path       path to file
     * @param null|string $reference  reference to a branch or commit
     *
     * @throws InvalidArgumentException If $path is not a file or if its encoding is different from base64
     * @throws ErrorException           If $path doesn't include a 'content' index
     *
     * @return null|string content of file, or null in case of base64_decode failure
     */
    public function download($username, $repository, $path, $reference = null)
    {
        
    }
}