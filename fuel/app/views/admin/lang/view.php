<h2>Viewing #<?php echo $lang->id; ?></h2>

<p>
	<strong>Code:</strong>
	<?php echo $lang->code; ?></p>
<p>
	<strong>Label:</strong>
	<?php echo $lang->label; ?></p>

<?php echo Html::anchor('admin/lang/edit/'.$lang->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/lang', 'Back'); ?>