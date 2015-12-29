<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Main extends CI_Migration {

	public function up()
	{
		$fields = array(
                        'main_image' => array('type' => 'VARCHAR',
                            'constraint' => '100',
                            'null' => TRUE,)
);
                $this->dbforge->add_column('posts', $fields);
	}

	public function down()
	{
            $this->dbforge->drop_column('posts', 'main_image');
	}
}