<?php
class Controller_Admin_Crown extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array(
			'where' => Filter::getWhere(array('type', 'CROWN')), 'order_by' => 'sort_order'));
		$data['elementType'] = 'crown';
		$this->template->title = "Crowns";
		$this->template->content = View::forge('admin/element/index', $data);
	}
}
