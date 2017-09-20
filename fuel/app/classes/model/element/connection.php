<?php

class Model_Element_Connection extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'element_id',
		'element_type',
		'element_id_ref',
		'element_ref_type',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);

	protected static $_table_name = 'element_connections';

}
