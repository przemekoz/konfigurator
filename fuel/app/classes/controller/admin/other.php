<?php
class Controller_Admin_Other extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array(
			'where' => Filter::getWhere(array('type', 'OTHER')), 'order_by' => 'sort_order'));
		$data['elementType'] = 'other';
		$this->template->title = "Others";
		$this->template->content = View::forge('admin/element/index', $data);
	}
}
