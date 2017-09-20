<?php
class Controller_Admin_Columncategory extends Controller_Admin
{

	public function action_index()
	{
		$data['columncategories'] = Model_Columncategory::find('all');
		$this->template->title = "Columncategories";
		$this->template->content = View::forge('admin/columncategory/index', $data);

	}

	public function action_view($id = null)
	{
		$data['columncategory'] = Model_Columncategory::find($id);

		$this->template->title = "Columncategory";
		$this->template->content = View::forge('admin/columncategory/view', $data);

	}

	public function action_default($id = null)
	{
		$element = Model_Columncategory::find($id);
		$element->default = $element->default ? 0 : 1;

		if ($element->save()) {
				Session::set_flash('success', e('Default is now #' . $id));
		}
		Response::redirect('admin/columncategory');
	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Columncategory::validate('create');

			if ($val->run())
			{
				$columncategory = Model_Columncategory::forge(array(
					'title' => Input::post('title'),
					'from' => Input::post('from'),
					'to' => Input::post('to'),
				));

				if ($columncategory and $columncategory->save())
				{
					Session::set_flash('success', e('Added columncategory #'.$columncategory->id.'.'));

					Response::redirect('admin/columncategory');
				}

				else
				{
					Session::set_flash('error', e('Could not save columncategory.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Columncategories";
		$this->template->content = View::forge('admin/columncategory/create');

	}

	public function action_edit($id = null)
	{
		$columncategory = Model_Columncategory::find($id);
		$val = Model_Columncategory::validate('edit');

		if ($val->run())
		{
			$columncategory->title = Input::post('title');
			$columncategory->from = Input::post('from');
			$columncategory->to = Input::post('to');

			if ($columncategory->save())
			{
				Session::set_flash('success', e('Updated columncategory #' . $id));

				Response::redirect('admin/columncategory');
			}

			else
			{
				Session::set_flash('error', e('Could not update columncategory #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$columncategory->title = $val->validated('title');
				$columncategory->from = $val->validated('from');
				$columncategory->to = $val->validated('to');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('columncategory', $columncategory, false);
		}

		$this->template->title = "Columncategories";
		$this->template->content = View::forge('admin/columncategory/edit');

	}

	public function action_delete($id = null)
	{
		if ($columncategory = Model_Columncategory::find($id))
		{
			$columncategory->delete();

			Session::set_flash('success', e('Deleted columncategory #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete columncategory #'.$id));
		}

		Response::redirect('admin/columncategory');

	}

}
