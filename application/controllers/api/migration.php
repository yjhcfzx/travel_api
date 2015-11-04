<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Keys Controller
 *
 * This is a basic Key Management REST controller to make and delete keys.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/



class Migration extends CI_Controller
{
	
    
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->library('migration');
	}
	
	function index()
	{

            if ( ! $this->migration->current())
            {
                    show_error($this->migration->error_string());
            }
	}
	
	
}
