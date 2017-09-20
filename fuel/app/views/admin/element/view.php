<p>
	<strong>Id:</strong>
	<?php echo $element->id; ?>
</p>
<p>
	<strong>Name:</strong>
	<?php echo $element->name; ?>
</p>
<p>
	<?php if (isset($element)) : ?>
		<img src="<?php echo $element->getImageSrc(); ?>" height="200"/>
	<?php endif ?>
</p>
<p>
	<strong>Type:</strong>
	<?php echo $element->type; ?></p>
<p>
	<strong>Category:</strong>
	<?php echo $element->category; ?></p>

<?php if ($element->type == 'CROWN' || $element->type == 'LAMP' ) : ?>
	<p>
		<strong>Connection:</strong>
		<?php echo $element->connection; ?>
	</p>
<?php endif; ?>

<?php if ($element->type == 'COLUMN') : ?>
	<p>
		<strong>Column category:</strong>
		<?php echo $columnCategories[$element->column_category]; ?>
	</p>
<?php endif; ?>

<?php echo Html::anchor('admin/element/edit/'.$element->id, 'Edit'); ?> |
<!-- <?php echo Html::anchor('admin/element', 'Back'); ?> -->
<a href="#back" onclick="javascript:history.back()">Back</a>
