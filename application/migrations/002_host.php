<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Host extends CI_Migration {

	public function up()
	{

                 //hosts
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
		$this->dbforge->create_table('hosts');
                
                
                $fields = array(
                         'host' => array(
				'type' => 'VARCHAR',
				'constraint' => 100,		
			),
                        'transport' => array(
				'type' => 'TEXT',		
			),
                );
                
                //add host to itinerary item
               $this->dbforge->add_column('itinerary_item', $fields);
               
              
                
               
	}

	public function down()
	{
		$this->dbforge->drop_table('hosts');
                $this->dbforge->drop_column('itinerary_item', 'host');
                 $this->dbforge->drop_column('itinerary_item', 'transport');
	}
}