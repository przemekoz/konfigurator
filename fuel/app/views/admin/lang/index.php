<h2>Listing Langs</h2>
<br>
<p>
	<?php echo Html::anchor('admin/lang/create', 'Add new Lang', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($langs): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Code</th>
			<th>Label</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($langs as $item): ?>		<tr>

			<td><?php echo $item->code; ?></td>
			<td><?php echo $item->label; ?></td>
			<td>
				<?php echo Html::anchor('admin/lang/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/lang/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Langs.</p>

<?php endif; ?>
