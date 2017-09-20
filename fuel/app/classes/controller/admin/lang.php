<?php
class Controller_Admin_Lang extends Controller_Admin
{

	public function action_index()
	{
		$data['langs'] = Model_Lang::find('all');
		$this->template->title = "Langs";
		$this->template->content = View::forge('admin/lang/index', $data);

	}

	public function action_view($id = null)
	{
		$data['lang'] = Model_Lang::find($id);

		$this->template->title = "Lang";
		$this->template->content = View::forge('admin/lang/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Lang::validate('create');

			if ($val->run())
			{
				$lang = Model_Lang::forge(array(
					'code' => Input::post('code'),
					'label' => Input::post('label'),
				));

				if ($lang and $lang->save())
				{
					Session::set_flash('success', e('Added lang #'.$lang->id.'.'));

					Response::redirect('admin/lang');
				}

				else
				{
					Session::set_flash('error', e('Could not save lang.'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Langs";
		$this->template->content = View::forge('admin/lang/create');

	}

	public function action_edit($id = null)
	{
		$lang = Model_Lang::find($id);
		$val = Model_Lang::validate('edit');

		if ($val->run())
		{
			$lang->code = Input::post('code');
			$lang->label = Input::post('label');

			if ($lang->save())
			{
				Session::set_flash('success', e('Updated lang #' . $id));

				Response::redirect('admin/lang');
			}

			else
			{
				Session::set_flash('error', e('Could not update lang #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$lang->code = $val->validated('code');
				$lang->label = $val->validated('label');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('lang', $lang, false);
		}

		$this->template->title = "Langs";
		$this->template->content = View::forge('admin/lang/edit');

	}

	public function action_delete($id = null)
	{
		if ($lang = Model_Lang::find($id))
		{
			$lang->delete();

			Session::set_flash('success', e('Deleted lang #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete lang #'.$id));
		}

		Response::redirect('admin/lang');

	}

}
