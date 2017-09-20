<?php
class Controller_Admin_Kinkiet extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array(
			'where' => Filter::getWhere(array('type', 'KINKIET')), 'order_by' => 'sort_order'));
		$data['elementType'] = 'kinkiet';
		$this->template->title = "Kinkiety";
		$this->template->content = View::forge('admin/element/index', $data);
	}
}
