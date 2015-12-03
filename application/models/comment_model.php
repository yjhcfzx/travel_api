<?php
require(APPPATH.'/models/My_model.php');
class comment_model extends My_Model {

	var $main_table   = 'comments';
	var $content = '';
	var $data    = array(
			'id' => array(
			'default'=> 0,
			//'required'=>true,
			'type'=>'int'		
			),
                     'uid' => array(
			'default'=> 0,
			//'required'=>true,
			'type'=>'int'		
			),
             'post_id' => array(
			'default'=> 0,
			'required'=>true,
			'type'=>'int'		
			),
//			'title' => array(
//			'required'=>true,
//			'type'=>'string'		
//			),
            
                        'content' => array(
			'required'=>true,
			'type'=>'string'		
			),
//			'travle_time' => array(
//			'required'=>true,
//			'type'=>'date'		
//			),
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
		$user_id = isset($param['user_id']) ? $param['user_id'] : 0;
                $post_id = isset($param['post_id']) ? $param['post_id'] : 0;
		$str = "SELECT c.*, u.name as author FROM comments c LEFT JOIN users u ON c.uid = u.id WHERE c.status = 1 ";
                $args = array();                
		/* " p LEFT JOIN user_role r
				ON p.entity_id = r.entity_id AND r.entity_type = 'entity' AND r.is_deleted = 0 AND
				p.is_deleted = 0
				LEFT JOIN entity e ON p.entity_id = e.id
				WHERE 1=1 "; */
		if($user_id)
		{
			//$str .= "AND r.user_id = ?";
		}
                if($post_id)
		{
			$str .= "AND c.post_id = ?";
                        $args[] = $post_id;
		}
                $str .= " ORDER BY c.id DESC" ;
		$query = $this->db->query($str,$args);
		$resp = array();
		foreach ($query->result() as $row)
		{
			$resp[] = $row;
		}
		
		return $resp;
	}
	

	function getDetail($id)
	{
		/* if($user_id)
		{
			//$str .= "AND r.user_id = ?";
		} */
		
		$rst = array();
		$is_found = false;
                $str = "SELECT p.*, u.name as author FROM posts p LEFT JOIN users u ON p.uid = u.id WHERE p.status = 1 AND p.id = ?" ; 
		//$query = $this->db->get_where($this->main_table, array('id' => $id,'status'=>1));
                $query = $this->db->query($str, array($id));
		foreach ($query->result() as $row)
		{   
                     $rst = (array) $row;
			//$rst['name'] = $row->name;
			//$rst['id'] = $id;
			$is_found = true;
			break;
		}
		if(!$is_found)
		{
			return null;
		}
		$rst['special_events'] = array();
		$rst['destination'] = array();
		
		$str = "SELECT d.* FROM destination d
		 LEFT JOIN post_destination p
		 ON d.id = p.destination_id AND p.status = 1 
		
		 WHERE p.post_id = ?"; 
		
		$query = $this->db->query($str, array($id));
		
		foreach ($query->result() as $row)
		{
			$rst['destination'][] = $row;
		}
		
		$str = "SELECT e.* FROM events e
		 LEFT JOIN post_event p
		 ON e.id = p.event_id AND p.status = 1 
		
		 WHERE e.status = 1  AND p.post_id = ?"; 
		
		$query = $this->db->query($str, array($id));
		
		foreach ($query->result() as $row)
		{
			$rst['special_events'][] = $row;
		}
               
		return $rst;
	}

	function updateDetail($obj)
	{
		
		$request = my_process_db_request($obj, $this->data, false);

		
		$id = $request['id'];
		$remove_request = array('status'=>2);
		$this->db->update('inquiry_greeting', $remove_request, array('inquiry_id' => $id));
		
		$this->db->update('inquiry_ending', $remove_request, array('inquiry_id' => $id));
		$this->db->update('inquiry_question', $remove_request, array('inquiry_id' => $id));
		
		if(isset($obj['questions']))
		{
			$ques_arr = array();
			$questions = explode('###', $obj['questions']);
			foreach ($questions as $question)
			{
				if(empty($question)){
					continue;
				}
				$ques_arr[] = array(
						'question'=>$question,
						'inquiry_id'=>$id,
						'status'=>1
		
				);
			}
		
			if(isset($obj['greetings']))
			{
				$greeting_arr = array();
				$greetings = explode('###', $obj['greetings']);
				foreach ($greetings as $greeting)
				{
					if(empty($greeting)){
					continue;
					}
					$greeting_arr[] = array(
							'content'=>$greeting,
							'inquiry_id'=>$id,
							'status'=>1
								
					);
				}
			}
			$this->db->insert_batch('inquiry_question', $ques_arr);
			$this->db->insert_batch('inquiry_greeting', $greeting_arr);
		
		}
		
		if(isset($obj['endings']))
		{
			$ending_arr = array();
			$endings = explode('###', $obj['endings']);
			foreach ($endings as $ending)
			{
				if(empty($ending)){
					continue;
				}	
				$ending_arr[] = array(
						'content'=>$ending,
						'inquiry_id'=>$id,
						'status'=>1
							
				);
			}
			$this->db->insert_batch('inquiry_ending', $ending_arr);
		}

		

		$this->db->update($this->main_table, $request, array('id' => $id));
		return true;
		
	}
	
	function createDetail($obj)
	{    
		//$obj = parse_str($obj);
		$request = my_process_db_request($obj, $this->data, false);
		$request['id'] = null;

		/* $maxid = 0;
		$row = $this->db->query('SELECT MAX(id) AS `maxid` FROM ' . $this->main_table)->row();
		if ($row) {
			$maxid = $row->maxid;
		}
		$maxid++;
		
		$request['weight'] = $maxid; */
		
		$this->db->insert($this->main_table, $request);
		$id = $this->db->insert_id();
               
                
		return $id;
		//return $obj;
	}
	
	function deleteDetail($id)
	{
		$id = intval($id);
		$remove_request = array('status'=>2);
		$this->db->update('inquiry_greeting', $remove_request, array('inquiry_id' => $id));
		
		$this->db->update('inquiry_ending', $remove_request, array('inquiry_id' => $id));
		$this->db->update('inquiry_question', $remove_request, array('inquiry_id' => $id));
		$this->db->update('inquiry', $remove_request, array('id' => $id));
		return $id;
		
	}
	
	
	
}
?>