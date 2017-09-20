<?php

namespace Fuel\Migrations;

class Add_fields_to_element_and_categories
{
	public function up()
	{
		\DBUtil::add_fields('elements', array(
		    'default' => array('constraint' => 1, 'type' => 'int', 'default' => 0, 'null' => true)
		));
		\DBUtil::add_fields('columnmaterials', array(
		    'default' => array('constraint' => 1, 'type' => 'int', 'default' => 0, 'null' => true)
		));
		\DBUtil::add_fields('columncategories', array(
		    'default' => array('constraint' => 1, 'type' => 'int', 'default' => 0, 'null' => true)
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('elements', 'default');
		\DBUtil::drop_fields('columnmaterials', 'default');
		\DBUtil::drop_fields('columncategories', 'default');
	}
}
