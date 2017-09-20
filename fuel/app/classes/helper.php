<?php

class Helper
{
	public static function getBaseUrl() {
		return \Fuel::$env == 'development' ? 'http://localhost/' : 'http://konfigurator.promar-sj.com.pl/';
	}

	public static function getColumnCategories($isFilter = null) {
		$result = array(isset($isFilter) ? '-column category-' : '-');
		$items = Model_Columncategory::find('all');
		foreach ($items as $item) {
			$result[$item->id] = $item->title;
		}
		return $result;
	}

	public static function getColumnMaterials($isFilter = null) {
		$result = array(isset($isFilter) ? '-column material-' : '-');
		$items = Model_Columnmaterial::find('all');
		foreach ($items as $item) {
			$result[$item->id] = $item->title;
		}
		return $result;
	}
}
