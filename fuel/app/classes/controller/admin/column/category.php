<?php
class Controller_Admin_Column_Category extends Controller_Admin
{

	public function action_index()
	{
		$data['column_categories'] = Model_Column_Category::find('all');
		$this->template->title = "Column categories";
		$this->template->content = View::forge('admin/column/category/index', $data);

	}

	public function action_view($id = null)
	{
		$data['column_category'] = Model_Column_Category::find($id);

		$this->template->title = "Column category";
		$this->template->content = View::forge('admin/column/category/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Column_Category::validate('create');

			if ($val->run())
			{
				$column_category = Model_Column_Category::forge(array(
					'title' => Input::post('title'),
					'from' => Input::post('from'),
					'to' => Input::post('to'),
				));

				if ($column_category and $column_category->save())
				{
					Session::set_flash('success', e('Added column category #'.$column_category->id.'.'));

					Response::redirect('admin/column/category');
				}

				else
				{
					Session::set_flash('error', e('Could not save column category.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Column Categories";
		$this->template->content = View::forge('admin/column/category/create');

	}

	public function action_edit($id = null)
	{
		$column_category = Model_Column_Category::find($id);
		$val = Model_Column_Category::validate('edit');

		if ($val->run())
		{
			$column_category->title = Input::post('title');
			$column_category->from = Input::post('from');
			$column_category->to = Input::post('to');

			if ($column_category->save())
			{
				Session::set_flash('success', e('Updated column category #' . $id));

				Response::redirect('admin/column/category');
			}

			else
			{
				Session::set_flash('error', e('Could not update column category #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$column_category->title = $val->validated('title');
				$column_category->from = $val->validated('from');
				$column_category->to = $val->validated('to');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('column_category', $column_category, false);
		}

		$this->template->title = "Column categories";
		$this->template->content = View::forge('admin/column/category/edit');

	}

	public function action_delete($id = null)
	{
		if ($column_category = Model_Column_Category::find($id))
		{
			$column_category->delete();

			Session::set_flash('success', e('Deleted column category #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete column category #'.$id));
		}

		Response::redirect('admin/column/category');

	}

}
