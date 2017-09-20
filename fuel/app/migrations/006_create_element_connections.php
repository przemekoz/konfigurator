<?php

namespace Fuel\Migrations;

class Create_element_connections
{
	public function up()
	{
		\DBUtil::create_table('element_connections', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'element_id' => array('constraint' => 11, 'type' => 'int'),
			'element_type' => array('constraint' => 255, 'type' => 'varchar'),
			'element_id_ref' => array('constraint' => 11, 'type' => 'int'),
			'element_ref_type' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('element_connections');
	}
}
