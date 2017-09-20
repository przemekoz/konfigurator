<?php

namespace Fuel\Migrations;

class Create_elements
{
	public function up()
	{
		\DBUtil::create_table('elements', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 255, 'type' => 'varchar'),
			'type' => array('constraint' => 255, 'type' => 'varchar'),
			'category' => array('constraint' => 255, 'type' => 'varchar'),
			'connection' => array('constraint' => 255, 'type' => 'varchar'),
			'column_category' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'sort_order' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('elements');
	}
}
