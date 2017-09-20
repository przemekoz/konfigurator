<?php
class Controller_Admin_Dictionary extends Controller_Admin
{

	public function action_index()
	{
		$data['dictionaries'] = Model_Dictionary::find('all', array(
			'group_by' => array('key'),
			'order_by' => array('key' => 'asc')));
		$this->template->title = "Dictionaries";
		$this->template->content = View::forge('admin/dictionary/index', $data);

	}

	public function action_view($id = null)
	{
		$data['dictionary'] = Model_Dictionary::find($id);

		$this->template->title = "Dictionary";
		$this->template->content = View::forge('admin/dictionary/view', $data);

	}

	public function action_create()
	{
		if (Input::method() == 'POST')
		{
			$val = Model_Dictionary::validate('create');

			if ($val->run())
			{
				foreach ($this->getLangs() as $lang) {
					$newDictionary = Model_Dictionary::forge(array(
						'lang_code' => $lang,
						'key' => Input::post('key'),
						'message' => Input::post('message_' . $lang)
					));

					if ($newDictionary->save())
					{
						Session::set_flash('success', e('Inserted dictionary '));
					}
					else
					{
						Session::set_flash('error', e('Could not update dictionary #' . $id));
					}
				}
				Response::redirect('admin/dictionary');
			}
			else
			{
				Session::set_flash('error', $val->error());

			}
		}

		$this->template->set_global('langs', $this->getLangs(), false);
		$this->template->set_global('messages', $this->getMessages(''), false);

		$this->template->title = "Dictionaries";
		$this->template->content = View::forge('admin/dictionary/create');

	}

	public function action_edit($id = null)
	{
		$dictionary = Model_Dictionary::find($id);
		$val = Model_Dictionary::validate('edit');

		if ($val->run())
		{
			Model_Dictionary::query()
        ->where('key', '=', $dictionary->key)
				->delete();

			foreach ($this->getLangs() as $lang) {
				$newDictionary = Model_Dictionary::forge(array(
					'lang_code' => $lang,
					'key' => $dictionary->key,
					'message' => Input::post('message_' . $lang)
				));

				if ($newDictionary->save())
				{
					Session::set_flash('success', e('Updated dictionary #' . $id));
				}
				else
				{
					Session::set_flash('error', e('Could not update dictionary #' . $id));
				}
			}
			Response::redirect('admin/dictionary');
		}
		else
		{
			if (Input::method() == 'POST')
			{
				$dictionary->lang_code = $val->validated('lang_code');
				$dictionary->key = $val->validated('key');
				$dictionary->message = $val->validated('message');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('dictionary', $dictionary, false);
			$this->template->set_global('langs', $this->getLangs(), false);
			$this->template->set_global('messages', $this->getMessages($dictionary->key), false);
		}

		$this->template->title = "Dictionaries";
		$this->template->content = View::forge('admin/dictionary/edit');

	}

	public function action_delete($id = null)
	{
		if ($dictionary = Model_Dictionary::find($id))
		{
			Model_Dictionary::query()
        ->where('key', '=', $dictionary->key)
				->delete();
			Session::set_flash('success', e('Deleted dictionary #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete dictionary #'.$id));
		}

		Response::redirect('admin/dictionary');

	}

	private function getLangs() {
		$langs = Model_Lang::find('all');
		$aLangs = array();
		foreach ($langs as $item) {
			$aLangs[ $item->code ] = $item->code;
		}
		return $aLangs;
	}

	private function getMessages($key) {
		$tpl = array();
		foreach ($this->getLangs() as $lang) {
			$tpl[$lang] = '';
		}
		if ($key != '') {
			$messages = Model_Dictionary::find('all', array(
		    'where' => array(
		        array('key', $key)
		    )));
			foreach ($messages as $item) {
				$tpl[$item->lang_code] = $item->message;
			}
		}
		return $tpl;
	}

}
