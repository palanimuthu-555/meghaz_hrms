<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Smartgoal extends CI_Model
{

	private static $db;


	function __construct(){
		parent::__construct();
		self::$db = &get_instance()->db;
	}

	/**
	* Insert records to performance_360 table and return INSERT ID
	*/
	static function save_golas($data = array()) {
		self::$db->insert('Smartgoal',$data);
		return self::$db -> insert_id();
	}

	/**
	* Update performance_360 information
	*/
	static function update_golas($id, $data = array()) {
		self::$db->where('id',$id)->update('Smartgoal',$data);
		return self::$db->affected_rows();
	}

	static function update_competencies($competencies, $data = array()) {
		self::$db->where('competencies',$competencies)->update('competencies',$data);
		return self::$db->affected_rows();
	}

	// Get 360_performance
	static function get_smartgoal($user_id)
	{
		return self::$db->where('user_id',$user_id)->order_by('id','ASC')->get('Smartgoal')->result_array();
	}
	static function get_competencies($user_id)
	{
		return self::$db->where('user_id',$user_id)->order_by('id','ASC')->get('competencies')->result_array();
	}


	static function get_smartgoal_manager($user_id)
	{
		return self::$db->where('teamlead_id',$user_id)->group_by('user_id')->order_by('id','ASC')->get('Smartgoal')->result_array();
	}
	// static function get_competencies_manager($user_id)
	// {
	// 	return self::$db->where('teamlead_id',$user_id)->order_by('id','ASC')->get('competencies')->result_array();
	// }


	static function delete_competencies($user_id)
	{
		self::$db->where('user_id',$user_id)->delete('competencies');
	}

	static function save_competencies($data = array()) {
		self::$db->insert('competencies',$data);
		return self::$db -> insert_id();
	}
	static function save_feedback($data = array()) {
		self::$db->insert('three_sixty_feedback',$data);
		return self::$db -> insert_id();
	}
	static function save_competencies_feedback($data = array()) {
		self::$db->insert('competencies_feedback',$data);
		return self::$db -> insert_id();
	}
	// Get all departments
	static function get_all_departments()
	{
		return self::$db->order_by('depthidden','ASC')->get('departments')->result();
	}



}

/* End of file model.php */
