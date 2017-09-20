<h2>Listing Column categories</h2>
<br>
<p>
	<?php echo Html::anchor('admin/columncategory/create', 'Add new Columncategory', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($columncategories): ?>
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
<?php foreach ($columncategories as $item): ?>		<tr <?php echo $item->default ? 'style="font-weight:bold; color:green"' : '' ?> >

			<td><?php echo $item->title; ?></td>
			<td><?php echo $item->from; ?></td>
			<td><?php echo $item->to; ?></td>
			<td style="font-weight:normal;">
				<?php echo Html::anchor('admin/columncategory/default/'.$item->id, 'Default'); ?> |
				<?php echo Html::anchor('admin/columncategory/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/columncategory/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Columncategories.</p>

<?php endif; ?>
