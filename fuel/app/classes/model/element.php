<?php
class Model_Element extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'type',
		'category',
		'connection',
		'column_category',
		'image_size_x',
		'image_size_y',
		'weight',
		'default',
		'column_category_material',
		'sort_order',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);

		if ($factory == 'edit_connections') {
			// do nothing
		}
		else if ($factory == 'edit_points') {
			$val->add_field('points', 'Points', 'required');
		}
		else {
			$val->add_field('name', 'Name', 'required|max_length[255]');
			$val->add_field('type', 'Type', 'required|max_length[255]');
			$val->add_field('category', 'Category', 'required|max_length[255]');
			$val->add_field('connection', 'Connection', 'required|max_length[255]');
			$val->add_field('column_category', 'Column Category', 'max_length[255]');
		}

		return $val;
	}

	public function getImageName($sizeName = '') {
		$name = $this->id . '_' . $this->type . '.png';
		return strlen($sizeName) ? $sizeName . '_' . $name : $name;
	}

	public function getImageSrc($sizeName = '') {
		return $this->getImagePath() . $this->getImageName($sizeName);
	}

	public function getImagePath() {
		return '/upload/';
	}
}
