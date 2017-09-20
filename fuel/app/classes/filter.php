<?php

class Filter
{
	public static $url = '';

	public static function getWhere($defaults) {

		$where = array();
		if (isset($defaults)) {
			$where = array($defaults);
		}

		if (strlen(Input::get('category'))) {
			$where[] = array('category', Input::get('category'));
		}

		if (strlen(Input::get('connection'))) {
			$where[] = array('connection', Input::get('connection'));
		}

		if (!empty(Input::get('size'))) {
			$where[] = array('column_category', Input::get('size'));
		}

		if (!empty(Input::get('material'))) {
			$where[] = array('column_category_material', Input::get('material'));
		}

		return $where;
	}
}
