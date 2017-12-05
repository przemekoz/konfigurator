<h2>Listing <?php echo ucfirst($elementType); ?></h2>
<!-- <br> -->

<hr>
<!-- filters -->
<?php echo Form::open(array("action" => "/admin/" . $elementType, 'method' => 'get')); ?>
<div class="row">
	<div class="col-md-3 ">
		<?php if ($elementType == 'other' || $elementType == 'lamp' ) : ?>
			<?php echo Form::select('category', Input::get('category', ''), array('' => '-category-', 'CITY'=>'City/street', 'HOME'=>'Home/garden'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Category')); ?>
		<?php endif; ?>
		<?php if ($elementType == 'crown' || $elementType == 'lamp') : ?>
			<?php echo Form::select('connection', Input::get('connection', ''), array(''=>'-connection-', 'UP'=>'Up', 'DOWN'=>'Down'), array('class' => 'col-md-4 form-control')); ?>
		<?php endif; ?>
	</div>
	<div class="col-md-3 ">
		<?php if ($elementType == 'column' ) : ?>
			<?php echo Form::select('size', Input::get('size', ''), $filterColumnCategories, array('class' => 'col-md-4 form-control')); ?>
		<?php endif; ?>
		<?php if ($elementType == 'kinkiet') : ?>
			<?php echo Form::select('connection', Input::get('connection', ''), array(''=>'-connection-', 'UP'=>'Up', 'DOWN'=>'Down'), array('class' => 'col-md-4 form-control')); ?>
		<?php endif; ?>
	</div>
	<div class="col-md-3 ">
		<?php if ($elementType == 'column' ) : ?>
			<?php echo Form::select('material', Input::get('material', ''), $filterColumnMaterials, array('class' => 'col-md-4 form-control')); ?>
		<?php endif; ?>
	</div>
	<div class="col-md-3 ">
		<?php echo Html::anchor("/admin/" . $elementType, 'Clear', array('class' => 'btn btn-secondary')); ?>
		<?php echo Form::submit('submit', 'Search', array('class' => 'btn btn-primary')); ?>
	</div>
</div>
<?php echo Form::close(); ?>
<hr>
<!-- filters -->

<p>
	<?php echo Html::anchor('admin/element/create/' . $elementType, 'Add new ' . ucfirst($elementType), array('class' => 'btn btn-success')); ?>
</p>
<?php if ($elements): ?>
<table class="table table-striped sortable">
	<thead>
		<tr>
			<th></th>
			<th>Name</th>
			<th>Weight</th>
			<?php if ($elementType != 'crown' && $elementType != 'lamp' ) : ?><th>Category</th><?php endif; ?>
			<?php if ($elementType == 'crown' || $elementType == 'lamp' ) : ?><th>Connection</th><?php endif; ?>
			<?php if ($elementType == 'column' ) : ?><th>Column category</th><?php endif; ?>
			<?php if ($elementType == 'column' ) : ?><th>Column material</th><?php endif; ?>
			<th></th>
		</tr>
	</thead>
	<tbody>
<?php foreach ($elements as $item): ?>
	<tr id="<?php echo $item->id; ?>" <?php echo $item->default ? 'style="font-weight:bold; color:green"' : '' ?> >
			<td><?php echo '<img style="max-height:100px; max-width: 100px" src="'.Images::getImageSrc($item->getImageSrc('mini')).'"/>'; ?></td>
			<td><?php echo $item->default ? '<strong>' : '' ?><?php echo $item->name . ' #' . $item->id; ?><?php echo $item->default ? '</strong>' : '' ?></td>
			<td><?php echo $item->weight ? $item->weight . 'kg' : '-'; ?></td>
			<?php if ($elementType != 'crown' && $elementType != 'lamp' ) : ?><td><?php echo $item->category; ?></td><?php endif; ?>
			<?php if ($item->type == 'CROWN' || $item->type == 'LAMP' || $item->type == 'KINKIET') : ?><td><?php echo $item->connection; ?></td><?php endif; ?>
			<?php if ($item->type == 'COLUMN' ) : ?><td><?php echo $columnCategories[$item->column_category]; ?></td><?php endif; ?>
			<?php if ($item->type == 'COLUMN' ) : ?><td><?php echo $columnMaterials[$item->column_category_material]; ?></td><?php endif; ?>
			<td style="font-weight:normal;">
				<?php echo Html::anchor('admin/element/default/'.$item->id, 'Default'); ?> |
				<!-- <?php echo Html::anchor('admin/element/view/'.$item->id, 'View'); ?> | -->
				<?php echo Html::anchor('admin/element/edit/'.$item->id, 'Edit'); ?> |
				<?php if ($item->type != 'OTHER') echo Html::anchor('admin/element/edit_points/'.$item->id, 'Points') . '|'; ?>
				<?php if ($item->type == 'CROWN' || $item->type == 'LAMP') echo Html::anchor('admin/element/edit_connections/'.$item->id, 'Connections') . '|'; ?>
				<?php echo Html::anchor('admin/element/delete/'.$item->id, 'Delete', array('onclick' => "return confirm('Are you sure?')")); ?>
			</td>
		</tr>
<?php endforeach; ?>	</tbody>
</table>

<?php else: ?>
<p>No <?php echo ucfirst($elementType); ?>.</p>

<?php endif; ?>
<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
	 $('tbody').sortable({
			update: function(event, ui) {
				 var itemsOrder = $(this).sortable('toArray').toString();
				 console.log(itemsOrder)
				 $.post('element/change_order', {elem_order: itemsOrder});
			}
	 });
});
</script>
