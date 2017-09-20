<h2>Editing Column scategory</h2>
<br>

<?php echo render('admin/column/category/_form'); ?>
<p>
	<?php echo Html::anchor('admin/column/category/view/'.$column_category->id, 'View'); ?> |
	<?php echo Html::anchor('admin/column/category', 'Back'); ?></p>
