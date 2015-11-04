<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Init extends CI_Migration {

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'constraint' => 5,
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
		));
                $this->dbforge->add_key('id',true);
		$this->dbforge->create_table('posts');
	}

	public function down()
	{
		$this->dbforge->drop_table('posts');
	}
}