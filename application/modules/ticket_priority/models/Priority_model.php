<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Priority_model extends CI_Model
{
    private static $db;

    function __construct()
    {
		parent::__construct();
		self::$db = &get_instance()->db;
	}

    static function all()
	{	
		self::$db->select('*');
		self::$db->from('priorities'); 		 
		self::$db->order_by('id','DESC');
		return self::$db->get()->result();
    }
    
    static function find($priority_id)
	{
		return self::$db->where('id',$priority_id)->get('priorities')->row();
	}
	
	static function priority_exists($priority,$id='')
	{		 
		if($id!='')
		{
			return self::$db->where('priority',$priority)->where('id != ',$id)->get('priorities')->row();            
		}
		else 
		{
			return self::$db->where('priority',$priority)->get('priorities')->row();            
		}
		
	}

	static function delete($id)
	{
		self::$db->where('id',$id)->delete('priorities');
	}

}