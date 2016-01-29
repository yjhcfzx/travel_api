<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

	public function up()
	{
            //post
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'content' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
                        'prepare_content' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
                        'travle_tip' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
                        'uid' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                       'travle_start_time' => array(
				'type' => 'DATE',
			),
                        'travle_end_time' => array(
				'type' => 'DATE',
			),
                    'main_image' => array('type' => 'VARCHAR',
                            'constraint' => '100',
                            'null' => TRUE,),
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('posts');
                
                //comment
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
			'content' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
                        'uid' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                        'post_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('comments');
                //users
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'gender' => array(
				'type' => 'CHAR',
                                'constraint' => '10',
				'null' => TRUE,
			),
                       'email' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
                                'default'=>''
			),
                        'phone' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
                                'default'=>''
			),
                        'password' => array(
				'type' => 'VARCHAR',
				'constraint' => '60',
			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                       
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('users',TRUE);
                
                //user role
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'uid' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'role_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('user_role');
                
                 //events
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'name' => array(
				'type' => 'VARCHAR',
				'constraint' => 500,
				'default' => '',

			),
                    
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('events');
                
                //post event
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'post_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'event_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('post_event');
                
                //post destination mapping
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'destination_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'post_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('post_destination');
                
                // itinerary
                 $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),

                        'content' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
                        
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published 2 deleted

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('itinerary');
                
                // itinerary item
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
                        'itinerary_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,			
			),
                        'index' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,			
			),
                        'destination' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,		
			),
                     'travle_date' => array(
				'type' => 'DATE',
			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published 2 deleted

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('itinerary_item');
                
                
               //post itinerary mapping
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'post_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                        'itinerary_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,			
			),
                         'travle_date' => array(
				'type' => 'DATE',
			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published 2 deleted

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('post_itinerary');
                
//                // itinerary destination
//                $this->dbforge->add_field(array(
//			'id' => array(
//				'type' => 'INT',
//				'constraint' => 10,
//				'unsigned' => TRUE,
//				'auto_increment' => TRUE
//			),
//			
//                        'destination_id' => array(
//				'type' => 'INT',
//				'constraint' => 10,
//				'unsigned' => TRUE,
//
//			),
//                        'itinerary_id' => array(
//				'type' => 'INT',
//				'constraint' => 10,
//				'unsigned' => TRUE,
//			),
//                       
//                         'status' => array(
//				'type' => 'TINYINT',
//				'constraint' => 1,
//				'unsigned' => TRUE,
//                                'default'=>1, //0 pending 1 published 2 deleted
//
//			),
//                      
//                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
//		));
//                $this->dbforge->add_key('id',true);
//		$this->dbforge->create_table('itinerary_destination');
                
                //resources
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'content' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
                        'uid' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                       
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('resources');
                
                 //resource destination mapping
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'destination_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'resource_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('resource_destination');
                
                //groups
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
			'content' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
                        'description' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
                       
                        'uid' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                        'main_image' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '100',
                            'null' => TRUE,
                         ),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                       'travle_start_time' => array(
				'type' => 'DATE',
			),
                        'travle_end_time' => array(
				'type' => 'DATE',
			),
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('groups');
                
                //group itenerary
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'group_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                       'itinerary_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,			
			),
                         'travle_date' => array(
				'type' => 'DATE',
			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published 2 deleted

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('group_itinerary');
                
                //group event
                 $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'group_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'event_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('group_event');
                
                
                //group destination mapping
                $this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			
                        'destination_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                     'group_id' => array(
				'type' => 'INT',
				'constraint' => 10,
				'unsigned' => TRUE,

			),
                         'status' => array(
				'type' => 'TINYINT',
				'constraint' => 1,
				'unsigned' => TRUE,
                                'default'=>1, //0 pending 1 published

			),
                      
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('group_destination');
                
                //sample data
                //user
                $user = array(
                    'phone' => '18817209480' ,
                    'name' => 'tester' ,
                    'password' => 'abc'
                );

                $this->db->insert('users', $user); 
                $user_id = $this->db->insert_id();
	}

	public function down()
	{
		$this->dbforge->drop_table('posts');
                //$this->dbforge->drop_table('users');
                $this->dbforge->drop_table('user_role');
                $this->dbforge->drop_table('events');
                 $this->dbforge->drop_table('post_event');
                 $this->dbforge->drop_table('post_destination');
                 $this->dbforge->drop_table('resources');
                 $this->dbforge->drop_table('resource_destination');
                  $this->dbforge->drop_table('comments');
                  $this->dbforge->drop_table('itinerary_destination');
                   $this->dbforge->drop_table('post_itinerary');
                   $this->dbforge->drop_table('groups');
          $this->dbforge->drop_table('group_itinerary');
           $this->dbforge->drop_table('group_event');
           $this->dbforge->drop_table('group_destination');
              $this->dbforge->drop_table('itinerary');      
                $this->dbforge->drop_table('itinerary_item');
	}
}