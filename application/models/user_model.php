<?php
require(APPPATH.'/models/My_model.php');
class user_model extends My_Model {

	var $main_table   = 'users';
	var $content = '';
	var $data    = array(
			'id' => array(
			'default'=> 0,
			//'required'=>true,
			'type'=>'int'		
			),
			'name' => array(
			'required'=>true,
			'type'=>'string'		
			),
			'phone' => array(
					'required'=>true,
					'type'=>'string'
			),
                       'email'=> array(
					'type'=>'string'
			),
			'password'=> array(
					'type'=>'string'
			),
			
			'status'=> array(
					'type'=>'int',
					'default' => 1
			)
	);

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}
	
	function getList($param = null)
	{
		$query = $this->db->get_where('product', array('is_deleted'=>0));
		$resp = array();
		foreach ($query->result() as $row)
		{
			$resp[] = $row;
		}
		
		return $resp;
	}
	
  
	function login($username, $password)
	{

		$string = "SELECT * FROM users u 
		WHERE u.password= ? AND u.status = 1 AND (u.phone = ? OR u.email=?)  LIMIT 1";
		//$query = $this->db->get_where('user', array('phone' => $username,'password' => $password));
		/* $query = $this->db->select("*")
		->from("user")
		->where($where); */
	//var_dump($string,$password,$username, $client_id);die;
		$query = $this->db->query($string, array($password,$username,$username));
		
		if($query && $query->result()){
			foreach ($query->result() as $row)
			{
				$row->roles = array();
				if($roles = $this->getUserRoles($row->id))
				{
					$row->roles = $roles;
				}
				return $row;
			}
		}
		return false;
	}
	
	function register($obj)
	{
		$client_id = $this->client_id;
		$request = my_process_db_request($obj, $this->data, false);
		unset($request['id']) ;
          
            $this->db->insert('users', $request);
                               
		$uid =  $this->db->insert_id();
		
		return $uid;
	
		if($uid){
			foreach ($query->result() as $row)
			{
				$row->roles = array();
				if($roles = $this->getUserRoles($row->id))
				{
					$row->roles = $roles;
				}
				return $row;
			}
		}
		return false;
	}
	
	function getDetail($id)
	{
		$query = $this->db->get_where($this->main_table, array('id' => $id,'status'=>1));
		foreach ($query->result() as $row)
		{
			return $row;
		}
	}

	function updateDetail($obj)
	{
		$request = my_process_db_request($obj, $this->data);
		return $request;
		

		$this->db->update('entity', $request, array('id' => $_POST['id']));
	}
	
	function createDetail($obj)
	{

		$request = my_process_db_request($obj, $this->data, false);

		$request['id'] = null;
		$this->db->insert('product', $request);
		return $this->db->insert_id();
		//return $obj;
	}
	
	function deleteDetail($id)
	{
		return $id;
		$this->title   = $_POST['title'];
		$this->content = $_POST['content'];
		$this->date    = time();
	
		$this->db->update('entries', $this, array('id' => $_POST['id']));
	}
	
}
?>