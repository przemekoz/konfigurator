<h2>Listing Column materials</h2>
<br>
<p>
	<?php echo Html::anchor('admin/columnmaterial/create', 'Add new Columnmaterial', array('class' => 'btn btn-success')); ?>
</p>
<?php if ($columnmaterials): ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($columnmaterials as $item): ?>		<tr <?php echo $item->default ? 'style="font-weight:bold; color:green"' : '' ?> >

			<td><?php echo $item->title; ?></td>
			<td style="font-weight:normal;">
				<?php echo Html::anchor('admin/columnmaterial/default/'.$item->id, 'Default'); ?> |
				<?php echo Html::anchor('admin/columnmaterial/edit/'.$item->id, 'Edit'); ?> |
				<?php echo Html::anchor('admin/columnmaterial/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>

			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No Columnmaterials.</p>

<?php endif; ?>
