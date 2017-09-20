<?php
class Controller_Admin_Columnmaterial extends Controller_Admin
{

	public function action_index()
	{
		$data['columnmaterials'] = Model_Columnmaterial::find('all');
		$this->template->title = "Columnmaterials";
		$this->template->content = View::forge('admin/columnmaterial/index', $data);

	}

	public function action_view($id = null)
	{
		$data['columnmaterial'] = Model_Columnmaterial::find($id);

		$this->template->title = "Columnmaterial";
		$this->template->content = View::forge('admin/columnmaterial/view', $data);

	}

	public function action_default($id = null)
	{
		$element = Model_Columnmaterial::find($id);
		$element->default = $element->default ? 0 : 1;

		if ($element->save()) {
				Session::set_flash('success', e('Default is now #' . $id));
		}
		Response::redirect('admin/columnmaterial');
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Columnmaterial::validate('create');

			if ($val->run())
			{
				$columnmaterial = Model_Columnmaterial::forge(array(
					'title' => Input::post('title'),
				));

				if ($columnmaterial and $columnmaterial->save())
				{
					Session::set_flash('success', e('Added columnmaterial #'.$columnmaterial->id.'.'));

					Response::redirect('admin/columnmaterial');
				}

				else
				{
					Session::set_flash('error', e('Could not save columnmaterial.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Columnmaterials";
		$this->template->content = View::forge('admin/columnmaterial/create');

	}

	public function action_edit($id = null)
	{
		$columnmaterial = Model_Columnmaterial::find($id);
		$val = Model_Columnmaterial::validate('edit');

		if ($val->run())
		{
			$columnmaterial->title = Input::post('title');

			if ($columnmaterial->save())
			{
				Session::set_flash('success', e('Updated columnmaterial #' . $id));

				Response::redirect('admin/columnmaterial');
			}

			else
			{
				Session::set_flash('error', e('Could not update columnmaterial #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$columnmaterial->title = $val->validated('title');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('columnmaterial', $columnmaterial, false);
		}

		$this->template->title = "Columnmaterials";
		$this->template->content = View::forge('admin/columnmaterial/edit');

	}

	public function action_delete($id = null)
	{
		if ($columnmaterial = Model_Columnmaterial::find($id))
		{
			$columnmaterial->delete();

			Session::set_flash('success', e('Deleted columnmaterial #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete columnmaterial #'.$id));
		}

		Response::redirect('admin/columnmaterial');

	}

}
