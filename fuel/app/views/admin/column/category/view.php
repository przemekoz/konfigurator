<h2>Viewing #<?php echo $column_category->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $column_category->title; ?></p>
<p>
	<strong>From:</strong>
	<?php echo $column_category->from; ?></p>
<p>
	<strong>To:</strong>
	<?php echo $column_category->to; ?></p>

<?php echo Html::anchor('admin/column/category/edit/'.$column_category->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/column/category', 'Back'); ?>