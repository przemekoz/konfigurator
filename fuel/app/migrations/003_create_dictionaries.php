<?php

namespace Fuel\Migrations;

class Create_dictionaries
{
	public function up()
	{
		\DBUtil::create_table('dictionaries', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'lang_code' => array('constraint' => 255, 'type' => 'varchar'),
			'key' => array('constraint' => 255, 'type' => 'varchar'),
			'message' => array('type' => 'text'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('dictionaries');
	}
}