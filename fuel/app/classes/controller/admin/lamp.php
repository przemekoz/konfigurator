<?php
class Controller_Admin_Lamp extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array(
			'where' => Filter::getWhere(array('type', 'LAMP')), 'order_by' => 'sort_order'));
		$data['elementType'] = 'lamp';
		$this->template->title = "Lamps";
		$this->template->content = View::forge('admin/element/index', $data);
	}
}
