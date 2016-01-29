<?php

require(APPPATH . '/models/My_model.php');

class group_model extends My_Model {

    var $main_table = 'groups';
    var $content = '';
    var $data = array(
        'id' => array(
            'default' => 0,
            //'required'=>true,
            'type' => 'int'
        ),
        'uid' => array(
            'default' => 0,
            //'required'=>true,
            'type' => 'int'
        ),
        'title' => array(
            'required' => true,
            'type' => 'string'
        ),
        'content' => array(
            'required' => true,
            'type' => 'string'
        ),
        'description' => array(
            'required' => true,
            'type' => 'string'
        ),
       
        'travle_start_time' => array(
            'required' => true,
            'type' => 'date'
        ),
        'travle_end_time' => array(
            'required' => true,
            'type' => 'date'
        ),
         'main_image' => array(
            'type' => 'string',
        ),
        'status' => array(
            'type' => 'int',
            'default' => 1
        )
    );

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getList($param = null) {
        $user_id = isset($param['user_id']) ? $param['user_id'] : 0;
        $keyword = isset($param['keyword']) ? $param['keyword'] : NULL;
        $str = "SELECT g.*, u.name as author, GROUP_CONCAT(d.name SEPARATOR ', ') as destination FROM groups g LEFT JOIN users u ON g.uid = u.id "
                . " LEFT JOIN group_destination gd ON g.id = gd.group_id LEFT JOIN destination d ON d.id = gd.destination_id WHERE g.status = 1 "
                . " AND gd.`status` = 1 ";
        /* " p LEFT JOIN user_role r
          ON p.entity_id = r.entity_id AND r.entity_type = 'entity' AND r.is_deleted = 0 AND
          p.is_deleted = 0
          LEFT JOIN entity e ON p.entity_id = e.id
          WHERE 1=1 "; */
        if ($user_id) {
            //$str .= "AND r.user_id = ?";
        }
        $args = array();  
        $str .= ' GROUP BY g.id ORDER BY g.id DESC';
         if ($keyword) {
           $keyword = mysql_real_escape_string($keyword);
           $str =  "SELECT * FROM ( " . $str . " ) t WHERE t.destination like '%{$keyword}%'"; 
            
            // $str =  "SELECT * FROM ( " . $str . " ) t WHERE t.destination like '%����%'";          //   var_dump($str);die;
            //$args[] = $keyword;
        }
               
        $args = count($args) ? $args : NULL;
        if($args){
             $query = $this->db->query($str,$args);
        }
        else{
             $query = $this->db->query($str);
        }
     
        $resp = array();
        foreach ($query->result() as $row) {
            $resp[] = $row;
        }
        return $resp;
    }

    function getRecent($param = null) {
        $user_id = isset($param['user_id']) ? $param['user_id'] : 0;
        $current = date("Y-m-d");
        $str = "SELECT p.* FROM groups p  WHERE p.status = 1 ";
      
        if ($user_id) {
            //$str .= "AND r.user_id = ?";
        }
        $args = array();  
        $str .= "  ORDER BY ABS(DATEDIFF(p.travle_start_time, '{$current}')) ASC LIMIT 10";
       
               
        $args = count($args) ? $args : NULL;
        if($args){
             $query = $this->db->query($str,$args);
        }
        else{
             $query = $this->db->query($str);
        }
     
        $resp = array();
        foreach ($query->result() as $row) {
            $resp[] = $row;
        }

        return $resp;
    }

    function getDetail($id) {
        /* if($user_id)
          {
          //$str .= "AND r.user_id = ?";
          } */

        $rst = array();
        $is_found = false;
        $str = "SELECT g.*, u.name as author FROM groups g LEFT JOIN users u ON g.uid = u.id WHERE g.status = 1 AND g.id = ?";
        //$query = $this->db->get_where($this->main_table, array('id' => $id,'status'=>1));
        $query = $this->db->query($str, array($id));
        foreach ($query->result() as $row) {
            $rst = (array) $row;
            //$rst['name'] = $row->name;
            //$rst['id'] = $id;
            $is_found = true;
            break;
        }
        if (!$is_found) {
            return null;
        }
        $rst['special_events'] = array();
        $rst['destination'] = array();
        $rst['itinerary'] = array();
        $str = "SELECT d.* FROM destination d
		 LEFT JOIN group_destination g
		 ON d.id = g.destination_id AND g.status = 1 
		
		 WHERE g.group_id = ?";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['destination'][] = $row;
        }

        $str = "SELECT e.* FROM events e
		 LEFT JOIN group_event g
		 ON e.id = g.event_id AND g.status = 1 
		
		 WHERE e.status = 1  AND g.group_id = ?";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['special_events'][] = $row;
        }

        $str = "SELECT i.*, GROUP_CONCAT(d.name SEPARATOR ', ')  as dname FROM 
group_itinerary pi
                 LEFT JOIN itinerary_item i ON i.itinerary_id = pi.itinerary_id
		 LEFT JOIN destination d
		 ON  FIND_IN_SET(d.id, i.destination) 
		 WHERE pi.status = 1  AND pi.group_id =  ? GROUP BY i.id";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['itinerary'][] = $row;
        }

        return $rst;
    }

    function updateDetail($obj) {

        $request = my_process_db_request($obj, $this->data, false);
        $destination_mapping = array();

        $id = $request['id'];
        $remove_request = array('status' => 2);
        $this->db->update('group_destination', $remove_request, array('group_id' => $id));

        $this->db->update('group_event', $remove_request, array('group_id' => $id));
        
       

        $this->db->update('group_itinerary', $remove_request, array('group_id' => $id));
        
         $this->insertRelation($obj, $destination_mapping,$id);




        $this->db->update($this->main_table, $request, array('id' => $id));
        return true;
    }

    function createDetail($obj) {
        //$obj = parse_str($obj);
        $request = my_process_db_request($obj, $this->data, false);

        $request['id'] = null;
        $destination_mapping = array();
        /* $maxid = 0;
          $row = $this->db->query('SELECT MAX(id) AS `maxid` FROM ' . $this->main_table)->row();
          if ($row) {
          $maxid = $row->maxid;
          }
          $maxid++;

          $request['weight'] = $maxid; */

        $this->db->insert($this->main_table, $request);
        $id = $this->db->insert_id();
        $this->insertRelation($obj, $destination_mapping,$id);

       
        return $id;
        //return $obj;
    }

    protected function insertRelation($obj,$destination_mapping, $id){
         if ($obj['destination']) {
            $destination = explode(',', $obj['destination']);
            $destinations = array();
            foreach ($destination as $item) {
                if (is_numeric($item)) {
                    $destinations[] = array(
                        'group_id' => $id,
                        'destination_id' => $item
                    );
                } else {
                    if (isset($destination_mapping[$item])) {
                        $item_id = $destination_mapping[$item];
                    } else {
                        $this->db->insert('destination', array('name' => $item));
                        $item_id = $this->db->insert_id();
                        $destination_mapping[$item] = $item_id;
                    }

                    $destinations[] = array(
                        'group_id' => $id,
                        'destination_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('group_destination', $destinations);
        }

        if ($obj['special_event']) {
            $event = explode(',', $obj['special_event']);
            $events = array();
            foreach ($event as $item) {
                if (is_numeric($item)) {
                    $events[] = array(
                        'group_id' => $id,
                        'event_id' => $item
                    );
                } else {
                    $this->db->insert('events', array('name' => $item));
                    $item_id = $this->db->insert_id();
                    $events[] = array(
                        'group_id' => $id,
                        'event_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('group_event', $events);
        }

       if ($obj['itinerary']) {
            $itinerary_request = array('content'=>'');
            $this->db->insert('itinerary',$itinerary_request);
            //new itinerary
           
           $itinerary_id = $this->db->insert_id();
            //new itinerary mapping
            $group_itinerary_request = array(
                'group_id' => $id,
                'itinerary_id' => $itinerary_id,
                'travle_date'=> $obj['travle_start_time']
            );
            $this->db->insert('group_itinerary', $group_itinerary_request);
            
            $itineraries = explode('#', $obj['itinerary']);
            $itinerary_items_request = array();
            $start_time = strtotime($obj['travle_start_time']);
            foreach ($itineraries as $index => $itinerary) {
                $travle_date = date('Y-m-d', strtotime("+{$index} day", $start_time));
                $itinerary_item_request = array(
                     'itinerary_id' => $itinerary_id,
                    'index' => $index + 1,
                    'travle_date' => $travle_date
                );
                $itinerary_id = $this->db->insert_id();
                $destination = explode(',', $itinerary);
                $destinations = array();
                foreach ($destination as $d_item) {
                    if (is_numeric($d_item)) {
                        $destinations[] = $d_item;
                    } else {
                        if (isset($destination_mapping[$d_item])) {
                            $item_id = $destination_mapping[$d_item];
                        } else {
                            $this->db->insert('destination', array('name' => $d_item));
                            $item_id = $this->db->insert_id();
                            $destination_mapping[$d_item] = $item_id;
                        }
                        $destinations[] = $item_id;
                    }
                }
                $itinerary_item_request['destination'] = implode(',', $destinations);
                $itinerary_items_request[] = $itinerary_item_request; 
            }
            $this->db->insert_batch('itinerary_item', $itinerary_items_request);
        }
    }
    function deleteDetail($id) {
        $id = intval($id);
        $remove_request = array('status' => 2);
        $this->db->update('inquiry_greeting', $remove_request, array('inquiry_id' => $id));

        $this->db->update('inquiry_ending', $remove_request, array('inquiry_id' => $id));
        $this->db->update('inquiry_question', $remove_request, array('inquiry_id' => $id));
        $this->db->update('inquiry', $remove_request, array('id' => $id));
        return $id;
    }

}

?>