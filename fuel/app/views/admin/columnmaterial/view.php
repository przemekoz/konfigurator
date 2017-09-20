<h2>Viewing #<?php echo $columnmaterial->id; ?></h2>

<p>
	<strong>Title:</strong>
	<?php echo $columnmaterial->title; ?></p>

<?php echo Html::anchor('admin/columnmaterial/edit/'.$columnmaterial->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/columnmaterial', 'Back'); ?>