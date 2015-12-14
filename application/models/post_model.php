<?php

require(APPPATH . '/models/My_model.php');

class post_model extends My_Model {

    var $main_table = 'posts';
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
        'prepare_content' => array(
            'required' => true,
            'type' => 'string'
        ),
        'travle_tip' => array(
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
        $str = "SELECT p.*, u.name as author, GROUP_CONCAT(d.name SEPARATOR ', ') as destination FROM posts p LEFT JOIN users u ON p.uid = u.id "
                . " LEFT JOIN post_destination pd ON p.id = pd.post_id LEFT JOIN destination d ON d.id = pd.destination_id WHERE p.status = 1 GROUP BY p.id ORDER BY p.id DESC";
        /* " p LEFT JOIN user_role r
          ON p.entity_id = r.entity_id AND r.entity_type = 'entity' AND r.is_deleted = 0 AND
          p.is_deleted = 0
          LEFT JOIN entity e ON p.entity_id = e.id
          WHERE 1=1 "; */
        if ($user_id) {
            //$str .= "AND r.user_id = ?";
        }
        $query = $this->db->query($str);
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
        $str = "SELECT p.*, u.name as author FROM posts p LEFT JOIN users u ON p.uid = u.id WHERE p.status = 1 AND p.id = ?";
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
		 LEFT JOIN post_destination p
		 ON d.id = p.destination_id AND p.status = 1 
		
		 WHERE p.post_id = ?";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['destination'][] = $row;
        }

        $str = "SELECT e.* FROM events e
		 LEFT JOIN post_event p
		 ON e.id = p.event_id AND p.status = 1 
		
		 WHERE e.status = 1  AND p.post_id = ?";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['special_events'][] = $row;
        }

        $str = "SELECT i.*,GROUP_CONCAT(d.id SEPARATOR ', ')  as did, GROUP_CONCAT(d.name SEPARATOR ', ')  as dname FROM post_itinerary i
		 LEFT JOIN itinerary_destination dm
		 ON i.id = dm.itinerary_id AND dm.status = 1 
		 LEFT JOIN destination d
		 ON dm.destination_id = d.id 
		 WHERE i.status = 1  AND i.post_id =  ? GROUP BY travle_date";

        $query = $this->db->query($str, array($id));

        foreach ($query->result() as $row) {
            $rst['itinerary'][] = $row;
        }

        return $rst;
    }

    function updateDetail($obj) {

        $request = my_process_db_request($obj, $this->data, false);


        $id = $request['id'];
        $remove_request = array('status' => 2);
        $this->db->update('post_destination', $remove_request, array('post_id' => $id));

        $this->db->update('post_event', $remove_request, array('post_id' => $id));

        if ($obj['destination']) {
            $destination = explode(',', $obj['destination']);
            $destinations = array();
            foreach ($destination as $item) {
                if (is_numeric($item)) {
                    $destinations[] = array(
                        'post_id' => $id,
                        'destination_id' => $item
                    );
                } else {
                    $this->db->insert('destination', array('name' => $item));
                    $item_id = $this->db->insert_id();
                    $destinations[] = array(
                        'post_id' => $id,
                        'destination_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('post_destination', $destinations);
        }

        if ($obj['special_event']) {
            $event = explode(',', $obj['special_event']);
            $events = array();
            foreach ($event as $item) {
                if (is_numeric($item)) {
                    $events[] = array(
                        'post_id' => $id,
                        'event_id' => $item
                    );
                } else {
                    $this->db->insert('events', array('name' => $item));
                    $item_id = $this->db->insert_id();
                    $events[] = array(
                        'post_id' => $id,
                        'event_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('post_event', $events);
        }




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

        $this->db->insert('posts', $request);
        $id = $this->db->insert_id();


        if ($obj['destination']) {
            $destination = explode(',', $obj['destination']);
            $destinations = array();
            foreach ($destination as $item) {
                if (is_numeric($item)) {
                    $destinations[] = array(
                        'post_id' => $id,
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
                        'post_id' => $id,
                        'destination_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('post_destination', $destinations);
        }

        if ($obj['special_event']) {
            $event = explode(',', $obj['special_event']);
            $events = array();
            foreach ($event as $item) {
                if (is_numeric($item)) {
                    $events[] = array(
                        'post_id' => $id,
                        'event_id' => $item
                    );
                } else {
                    $this->db->insert('events', array('name' => $item));
                    $item_id = $this->db->insert_id();
                    $events[] = array(
                        'post_id' => $id,
                        'event_id' => $item_id
                    );
                }
            }
            $this->db->insert_batch('post_event', $events);
        }

        if ($obj['itinerary']) {
            $itineraries = explode('#', $obj['itinerary']);
            $itinerary_request = array();
            $start_time = strtotime($obj['travle_start_time']);
            foreach ($itineraries as $index => $itinerary) {
                $travle_date = date('Y-m-d', strtotime("+{$index} day", $start_time));
                $itinerary_request = array(
                    'post_id' => $id,
                    'index' => $index + 1,
                    'travle_date' => $travle_date
                );
                $this->db->insert('post_itinerary', $itinerary_request);
                $itinerary_id = $this->db->insert_id();
                $destination = explode(',', $itinerary);
                $destinations = array();
                foreach ($destination as $d_item) {
                    if (is_numeric($d_item)) {
                        $destinations[] = array(
                            'itinerary_id' => $itinerary_id,
                            'destination_id' => $d_item
                        );
                    } else {
                        if (isset($destination_mapping[$d_item])) {
                            $item_id = $destination_mapping[$d_item];
                        } else {
                            $this->db->insert('destination', array('name' => $d_item));
                            $item_id = $this->db->insert_id();
                            $destination_mapping[$d_item] = $item_id;
                        }
                        $destinations[] = array(
                            'itinerary_id' => $itinerary_id,
                            'destination_id' => $item_id
                        );
                    }
                }
                $this->db->insert_batch('itinerary_destination', $destinations);
            }
        }
        return $id;
        //return $obj;
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