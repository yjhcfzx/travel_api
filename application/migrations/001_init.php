<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

	public function up()
	{
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
                       'travle_time' => array(
				'type' => 'DATE',
			),
                       'created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('posts');
                
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
		$this->dbforge->create_table('users');
                
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
                $this->dbforge->drop_table('users');
                $this->dbforge->drop_table('user_role');
                $this->dbforge->drop_table('events');
                 $this->dbforge->drop_table('post_event');
                 $this->dbforge->drop_table('post_destination');
                 $this->dbforge->drop_table('resources');
                 $this->dbforge->drop_table('resource_destination');
	}
}