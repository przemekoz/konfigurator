<?php
class Controller_Admin_Element extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array('order_by' => 'sort_order'));
		$this->template->title = "Elements";
		$this->template->content = View::forge('admin/element/index', $data);
	}

	public function action_view($id = null)
	{
		$data['element'] = Model_Element::find($id);

		$this->template->title = "Element";
		$this->template->content = View::forge('admin/element/view', $data);
		$this->template->set_global('columnCategories', Helper::getColumnCategories(), false);
	}

	public function action_default($id = null)
	{
		$element = Model_Element::find($id);
		$element->default = $element->default ? 0 : 1;

		if ($element->save()) {
				Session::set_flash('success', e('Default is now #' . $id));
		}
		Response::redirect('admin/' . $element->type);
	}

	public function action_change_order()
	{
			foreach (explode(',', Input::post('elem_order')) as $key => $id) {
				// Debug::dump($key, $id);
				$element = Model_Element::find($id);
				$element->sort_order = $key * 10;
				$element->save();
			}
			return true;
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Element::validate('create');

			if ($val->run())
			{
				// process the uploaded files in $_FILES
				Upload::process(array(
					'auto_rename' => false,
					'overwrite' => true,
					'new_name' => 'TEMP_IMAGE.png'));

				$image_size_x = 0;
				$image_size_y = 0;
				$column_category = 0;
				// if there are any valid files
				if (Upload::is_valid())
				{
						// save them according to the config
						Upload::save();

						// get the first uploaded file by index
						$file = Upload::get_files(0);
						$sizes = Image::sizes($file['saved_to'] . $file['saved_as']);
						$image_size_x = $sizes->width;
						$image_size_y = $sizes->height;
						$column_category = getColumnCategory($image_size_y);
				}

				$element = Model_Element::forge(array(
					'name' => Input::post('name'),
					'type' => Input::post('type'),
					'category' => Input::post('category'),
					'connection' => Input::post('connection'),
					'weight' => Input::post('weight'),
					'default' => 0,
					'column_category_material' => Input::post('column_category_material'),
					'column_category' => $column_category,
					'image_size_x' => $image_size_x,
					'image_size_y' => $image_size_y,
					'sort_order' => 0
				));

				if ($element and $element->save())
				{

          $tempImage = DOCROOT.'upload/TEMP_IMAGE.png';
          if (File::exists($tempImage)) {
            // move and rename between two restrictive areas
            File::rename($tempImage, DOCROOT . $element->getImageSrc());
						Images::resize(DOCROOT . $element->getImagePath(), $element->getImageName(), array('mini'));
          }
					if (Input::post('submit') == 'Save and edit connection points') {
						Response::redirect('admin/element/edit_points/' . $element->id);
					}
					else {
						Session::set_flash('success', e('Added '.strtolower(Input::post('type')).' #'.$element->id.'.'));
						Response::redirect('admin/' . strtolower(Input::post('type')));
					}
				}

				else
				{
					Session::set_flash('error', e('Could not save element.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->set_global('columnMaterials', Helper::getColumnMaterials(), false);
		$this->template->set_global('columnCategories', Helper::getColumnCategories(), false);
		$this->template->set_global('elementType', strtoupper(Uri::segment(4)), false);
		$this->template->title = "Elements";
		$this->template->content = View::forge('admin/element/create');
	}

	public function action_edit($id = null)
	{
		$element = Model_Element::find($id);
		$val = Model_Element::validate('edit');

		if ($val->run())
		{
      // process the uploaded files in $_FILES
      Upload::process(array(
        'auto_rename' => false,
        'overwrite' => true,
        'new_name' => $element->getImageName()));

      $image_size_x = Input::post('image_size_x');
      $image_size_y = Input::post('image_size_y');
      // if there are any valid files
      if (Upload::is_valid())
      {
          // save them according to the config
          Upload::save();

          // get the first uploaded file by index
          $file = Upload::get_files(0);
          $sizes = Image::sizes($file['saved_to'] . $file['saved_as']);
          $image_size_x = $sizes->width;
          $image_size_y = $sizes->height;
					Images::resize(DOCROOT . $element->getImagePath(), $element->getImageName(), array('mini'));
      }

			$element->name = Input::post('name');
			$element->type = Input::post('type');
			$element->category = Input::post('category');
			$element->connection = Input::post('connection');
			$element->weight = Input::post('weight');
			$element->column_category_material = Input::post('column_category_material');
			$element->column_category = getColumnCategory($image_size_y);
      $element->image_size_x = $image_size_x;
      $element->image_size_y = $image_size_y;

			if ($element->save())
			{
				if (Input::post('submit') == 'Save and edit connection points') {
					Response::redirect('admin/element/edit_points/' . $id);
				}
				else {
					Session::set_flash('success', e('Updated '.strtolower(Input::post('type')).' #' . $id));
					Response::redirect('admin/' . strtolower(Input::post('type')));
				}
			}
			else {
				Session::set_flash('error', e('Could not update '.strtolower(Input::post('type')).' #' . $id));
			}
		}
		else
		{
			if (Input::method() == 'POST')
			{
				$element->name = $val->validated('name');
				$element->type = $val->validated('type');
				$element->category = $val->validated('category');
				$element->connection = $val->validated('connection');
				$element->column_category = $val->validated('column_category');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('columnMaterials', Helper::getColumnMaterials(), false);
			$this->template->set_global('columnCategories', Helper::getColumnCategories(), false);
			$this->template->set_global('element', $element, false);
			$this->template->set_global('elementType', $element->type, false);
		}

		$this->template->title = "Elements";
		$this->template->content = View::forge('admin/element/edit');
	}

  public function action_edit_points($id = null)
	{
		$element = Model_Element::find($id);
		$val = Model_Element::validate('edit_points');

		if ($val->run())
		{
			$points = Input::post('points');

			Model_Point::query()
				->where('elements_id', '=', $id)
				->delete();

				foreach (json_decode($points) as $point) {
					$newPoint = Model_Point::forge(array(
						'X' => $point->x,
						'Y' => $point->y,
						'elements_id' => $id,
						'type' => $point->type
					));

					if ($newPoint->save()){
						Session::set_flash('success', e('Inserted points'));
					}
					else {
						Session::set_flash('error', e('Could not indert point for:' . $id));
					}
				}
			Response::redirect('admin/' . strtolower($element->type));
		}
		else
		{
			if (Input::method() == 'POST')
			{
				$element->name = $val->validated('name');
				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('points', $this->getPoints($id), false);
			$this->template->set_global('element', $element, false);
		}

		$this->template->title = "Editing Points of Element";
		$this->template->content = View::forge('admin/element/edit_points');

	}

	public function action_edit_connections($id = null)
  	{
  		$element = Model_Element::find($id);
  		$val = Model_Element::validate('edit_connections');

  		if ($val->run())
  		{
  			$connections = Input::post('connections');

  			Model_Element_Connection::query()
				->where('element_id', '=', $id)
  				->delete();

					if ($connections) {
						foreach ($connections as $ref) {
							list($element_id_ref, $element_ref_type) = explode(',', $ref);
							$newConnection = Model_Element_Connection::forge(array(
								'element_id' => $id,
								'element_type' => $element->type,
								'element_id_ref' => $element_id_ref,
								'element_ref_type' => $element_ref_type
							));

							if ($newConnection->save()){
								Session::set_flash('success', e('Inserted connections'));
							}
							else {
								Session::set_flash('error', e('Could not indert connection for:' . $id));
							}
						}
					}

  			Response::redirect('admin/' . strtolower($element->type));
  		}
  		else
  		{
  			if (Input::method() == 'POST')
  			{
  				$element->name = $val->validated('name');
  				Session::set_flash('error', $val->error());
  			}

				$lists = array();
				foreach (array('COLUMN', 'CROWN', 'KINKIET') as $type) {
					$where = array(array('type', $type));
					if ($type != 'COLUMN') {
						$where[] = array('connection', $element->connection);
					}
					$lists[$type] = Model_Element::find('all', array(
						'where' => $where, 'order_by' => 'name'));
				}

				$checked = Model_Element_Connection::find('all', array(
					'where' => array(
						array('element_id', '=', $id)
					)));

					$connected = array();
					foreach ($checked as $item) {
						$connected[] = $item->element_id_ref;
					}

				$this->template->set_global('connected', $connected, false);
				$this->template->set_global('lists', $lists, false);
  			$this->template->set_global('element', $element, false);
  		}

  		$this->template->title = "Editing Connections with Element";
  		$this->template->content = View::forge('admin/element/edit_connections');
  	}

	public function action_delete($id = null)
	{
		if ($element = Model_Element::find($id))
		{
			if (File::exists(DOCROOT . $element->getImageSrc())) {
				File::delete(DOCROOT . $element->getImageSrc());
			}
			foreach(Images::$sizes as $sizeName => $values) {
				if (File::exists(DOCROOT . $element->getImagePath() . $sizeName . '_' . $element->getImageName())) {
					File::delete(DOCROOT . $element->getImagePath() . $sizeName . '_' . $element->getImageName());
				}
			}
			Model_Point::query()
				->where('elements_id', '=', $id)
				->delete();
				Model_Element_Connection::query()
					->where('element_id', '=', $id)
					->delete();
			$element->delete();
			Session::set_flash('success', e('Deleted element #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete element #'.$id));
		}

		Response::redirect('admin/' . strtolower($element->type));
	}

	private function getPoints($id) {
		$points = array();
		if ($id != '') {
			$items = Model_Point::find('all', array(
				'where' => array(
						array('elements_id', $id)
				)));
			foreach ($items as $item) {
				$points[] = array('x' => $item->X, 'y' => $item->Y, 'type' => $item->type);
			}
		}
		return json_encode($points);
	}
}

function getColumnCategory($sizeY) {
	$items = Model_Columncategory::find('all');
	$category = 0;
	foreach ($items as $item) {
		if ($sizeY > $item->from && $sizeY <= $item->to) {
			$category = $item->id;
			break;
		}
	}
	return $category;
}

// copyTransparent($png, DOCROOT . '/generated/png.png');
