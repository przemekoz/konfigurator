<?php

namespace Fuel\Migrations;

class Create_imagecaches
{
	public function up()
	{
		\DBUtil::create_table('imagecaches', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'main_id' => array('constraint' => 11, 'type' => 'int'),
			'sub_id' => array('constraint' => 11, 'type' => 'int'),
			'lamp_id' => array('constraint' => 11, 'type' => 'int'),
			'main_type' => array('constraint' => 20, 'type' => 'varchar'),
			'sub_type' => array('constraint' => 20, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('imagecaches');
	}
}
