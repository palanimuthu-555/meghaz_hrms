<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Permission extends CI_Model
{

	private static $db;

	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}
	
	

	
}

/* End of file model.php */
