<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Smart_goals extends CI_Model
{

	private static $db;


	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	/**
	* Insert records to smartgoal table and return INSERT ID
	*/
	static function save_golas($data = array()) {
		self::$db->insert('smartgoal',$data);
		return self::$db -> insert_id();
	}

	/**
	* Update smartgoal information
	*/
	static function update_golas($id, $data = array()) {
		self::$db->where('id',$id)->update('smartgoal',$data);
		return self::$db->affected_rows();
	}

	
	static function get_smartgoal($user_id)
	{
		return self::$db->where('user_id',$user_id)->order_by('id','ASC')->get('smartgoal')->result_array();
	}
	
	static function get_smartgoal_manager($user_id)
	{
		return self::$db->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('smartgoal')->result_array();
	}
		
	static function save_feedback($data = array()) {
		self::$db->insert('smartgoal_feedback',$data);
		return self::$db -> insert_id();
	}
	
	// Get all departments
	static function get_all_departments()
	{
		return self::$db->order_by('depthidden','ASC')->get('departments')->result();
	}



}

/* End of file model.php */
