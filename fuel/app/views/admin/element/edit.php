<h2>Editing <?php echo ucfirst(strtolower($elementType)) . ' #'.$element->id; ?></h2>
<br>

<?php echo render('admin/element/_form'); ?>
<p>
	<!-- <?php echo Html::anchor('admin/element/view/'.$element->id, 'View'); ?> | -->
	<!-- <?php echo Html::anchor('admin/element', 'Back'); ?> -->
	<a href="#back" onclick="javascript:history.back()">Back</a>
</p>
