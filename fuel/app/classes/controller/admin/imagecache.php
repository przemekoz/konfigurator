<?php
class Controller_Admin_Imagecache extends Controller_Admin
{

	public function action_index() {
		$data['elements'] = Model_Imagecache::find('all');
		$this->template->title = "List of imagecache";
		$this->template->content = View::forge('admin/imagecache/index', $data);
	}

	public function action_cache()
	{

		$resultData = array();

		$elementType = array(
			array('CROWN', 'COLUMN'),
			array('LAMP', 'COLUMN'),
			array('LAMP', 'KINKIET'),
			array('LAMP', 'CROWN')
		);

		// prepare data
		// make pairs ex COLUMN-CROWN where crown is main id column sub id (group by main id)
		// like: COLUMN-CROWN - [3: [1,2,3,4], 3: [1,2,2]] etc.
		foreach ($elementType as $type) {
			list($element_type, $element_ref_type) = $type;
			$name = $element_ref_type . '-' . $element_type;

			$data[$name] = Model_Element_Connection::find('all', array(
				'where' => array(
					array('element_type', $element_type),
					array('element_ref_type', $element_ref_type)
				)
			));


			// for connection crown-lamp diffrent group by
			if ($name == 'CROWN-LAMP') {
				$resultData[$name] = prepareData($data[$name], 'element_id_ref', 'element_id');
			}
			else {
				$resultData[$name] = prepareData($data[$name], 'element_id', 'element_id_ref');
			}
		}

		// delete all cache
		Model_Imagecache::query()->delete();

		// generate inserts for 2 elements pairs
		foreach ($resultData as $name => $data) {
			if ($name == 'CROWN-LAMP' || $name == 'COLUMN-CROWN') {
				continue;
			}

			// for 2 elements pairs like column-lamp, kinkiet-lamp, column-crown
			foreach ($data as $sub_id => $mainIds) {
				foreach ($mainIds as $main_id) {
					// INSERT
					list($main_type, $sub_type) = explode('-', $name);
					saveImagecache($main_id, $sub_id, 0, $main_type, $sub_type);
				}
			}
		} /// end


		// generate elements column-crown-lamp
		foreach ($resultData['COLUMN-CROWN'] as $crown_id => $columnIds) {
			if (isset($resultData['CROWN-LAMP'][$crown_id]) == false) {
				continue;
			}
			// for all columnIds
			foreach ($columnIds as $column_id) {
				// for all lamps id
				foreach ($resultData['CROWN-LAMP'][$crown_id] as $lamp_id) {
					// INSERT
					saveImagecache($column_id, $crown_id, $lamp_id, 'COLUMN', 'CROWN');
				}
			}
		}//end

		// Debug::dump($resultData);
		//
		// $this->template->title = "Image cache";
		// $this->template->content = 'Image cache generate...';

		Response::redirect('admin/imagecache/');
	}
}

function prepareData($data, $mainKeyName, $subKeyName) {
	$result = array();
	foreach ($data as $item) {
		$itemArray = $item->to_array();
		$main = $itemArray[$mainKeyName];

		if (isset($result[$main]) == false) {
			$result[$main] = array();
		}
		$result[$main][] = $itemArray[$subKeyName];
	}
	return $result;
}


function saveImagecache($main_id, $sub_id, $lamp_id, $main_type, $sub_type) {
	$newImagecache = Model_Imagecache::forge(array(
		'main_id' => $main_id,
		'sub_id' => $sub_id,
		'main_type' => $main_type,
		'sub_type' => $sub_type,
		'lamp_id' => $lamp_id
	));

	if ($newImagecache->save()){
		Session::set_flash('success', e('Imagecache generated'));
	}
	else {
		Session::set_flash('error', e('Could not insert imagecache'));
	}
}
