<?php

namespace Fuel\Migrations;

class Add_fields_to_element
{
	public function up()
	{
		\DBUtil::add_fields('elements', array(
		    'weight' => array('constraint' => 100, 'type' => 'varchar'),
		    'column_category_material' => array('constraint' => 11, 'type' => 'int', 'default' => 0, 'null' => true),
		));
	}

	public function down()
	{
		\DBUtil::drop_fields('elements', array('weight', 'column_category_material'));
	}
}
