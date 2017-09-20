<h2>Listing Column categories</h2>
<br>
<?php if ($column_categories): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>From</th>
			<th>To</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($column_categories as $item): ?>		<tr>

			<td><?php echo $item->title; ?></td>
			<td><?php echo $item->from; ?></td>
			<td><?php echo $item->to; ?></td>
			<td>
				<?php echo Html::anchor('admin/column/category/view/'.$item->id, 'View'); ?> |
				<?php echo Html::anchor('admin/column/category/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/column/category/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Column categories.</p>

<?php endif; ?><p>
	<?php echo Html::anchor('admin/column/category/create', 'Add new Column category', array('class' => 'btn btn-success')); ?>

</p>
