<h2>Viewing #<?php echo $dictionary->id; ?></h2>

<p>
	<strong>Lang code:</strong>
	<?php echo $dictionary->lang_code; ?></p>
<p>
	<strong>Key:</strong>
	<?php echo $dictionary->key; ?></p>
<p>
	<strong>Message:</strong>
	<?php echo $dictionary->message; ?></p>

<?php echo Html::anchor('admin/dictionary/edit/'.$dictionary->id, 'Edit'); ?> |
<?php echo Html::anchor('admin/dictionary', 'Back'); ?>
