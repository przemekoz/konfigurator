<h2>Listing Dictionaries</h2>
<br>
<p>
	<?php echo Html::anchor('admin/dictionary/create', 'Add new Dictionary', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($dictionaries): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Lang code</th>
			<th>Key</th>
			<th>Message</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($dictionaries as $item): ?>		<tr>

			<td><?php echo $item->lang_code; ?></td>
			<td><?php echo $item->key; ?></td>
			<td><?php echo $item->message; ?></td>
			<td>
				<?php echo Html::anchor('admin/dictionary/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/dictionary/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Dictionaries.</p>

<?php endif; ?>
