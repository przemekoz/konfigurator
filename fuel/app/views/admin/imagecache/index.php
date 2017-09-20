<h2>Listing imagecache</h2>
<br>
<p>
	<?php echo Html::anchor('admin/imagecache/cache/', 'Create new cache', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($elements): ?>
<table class="table table-striped sortable">
	<thead>
		<tr>
			<th width="1%"></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php $iter = 1; foreach ($elements as $item): ?>
	<tr>
		<td><?php echo $iter++  ?>.</td>
		<?php if ($item->lamp_id > 0): ?>
			<?php $src = Images::getImageSrc(Images::getTwoOrThree('maxi', array(array($item->main_id, $item->main_type), array($item->sub_id, $item->sub_type), array($item->lamp_id, 'LAMP')))); ?>
			<td><?php echo '<a href="'.$src.'"><img style="max-height:100px; max-width: 100px" src="'.$src.'"/></a>'; ?></td>
			<td><?php echo Images::getFilename(array(array($item->main_id, $item->main_type), array($item->sub_id, $item->sub_type), array($item->lamp_id, 'LAMP'))); ?></td>
		<?php else: ?>
			<?php $src = Images::getImageSrc(Images::getTwoOrThree('maxi', array(array($item->main_id, $item->main_type), array($item->sub_id, $item->sub_type)))); ?>
			<td><?php echo '<a href="'.$src.'"><img style="max-height:100px; max-width: 100px" src="'.$src.'"/></a>'; ?></td>
			<td><?php echo Images::getFilename(array(array($item->main_id, $item->main_type), array($item->sub_id, $item->sub_type))); ?></td>
		<?php endif; ?>

		</tr>
<?php endforeach; ?>
</tbody>
</table>
<?php else: ?>
	<p>No imagecache.</p>

<?php endif; ?>
