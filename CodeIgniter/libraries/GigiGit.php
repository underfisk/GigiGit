<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * GigiGit Class
 *
 * @package		Track on Performance
 * @subpackage	Libraries
 * @category	GigiGit
 * @author		Rodrigo Rodrigues
 * @link		http://github.com/underfisk/gigigit
 */
require_once( APPPATH .'third_party/GigiGit/GitClient.php' );

use GitCore\GitClient as GitClient;
class GigiGit
{
    /**
     * Codeigniter Instance
     * 
     * @var object
     */
    private $CI;

    /**
     * GigiGit Client Instance
     * 
     * @var GigiGit
     */
    private $client;

    /**
     * Initializes the main variables data and client
     * 
     * @return void
     */
    public function __construct()
    {
        if (!isset($this->CI))
            $this->CI = &get_instance();

        if (!isset($this->client))
            $this->client = new GitClient($this->CI->config->item('ggg_settings'));
    }

    /**
     * Returns the singleton instance to access git things
     * 
     * @return GitClient
     */
    public function &get_client()
    {
        return $this->client ?: new GitClient($this->CI->config->item('ggg_settings'));
    }


}