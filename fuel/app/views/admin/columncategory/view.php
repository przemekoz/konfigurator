<h2>Viewing #<?php echo $columncategory->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $columncategory->title; ?></p>
<p>
	<strong>From:</strong>
	<?php echo $columncategory->from; ?></p>
<p>
	<strong>To:</strong>
	<?php echo $columncategory->to; ?></p>

<?php echo Html::anchor('admin/columncategory/edit/'.$columncategory->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/columncategory', 'Back'); ?>