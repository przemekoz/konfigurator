<?php
class Controller_Admin_Column extends Controller_Admin
{

	public function action_index()
	{
		$data['elements'] = Model_Element::find('all', array(
			'where' => Filter::getWhere(array('type', 'COLUMN')), 'order_by' => 'sort_order'));

		$data['elementType'] = 'column';
		$data['filterColumnCategories'] = Helper::getColumnCategories('filter');
		$data['filterColumnMaterials'] = Helper::getColumnMaterials('filter');
		$data['columnCategories'] = Helper::getColumnCategories();
		$data['columnMaterials'] = Helper::getColumnMaterials();
		$this->template->title = "Columns";
		$this->template->content = View::forge('admin/element/index', $data);
	}
}