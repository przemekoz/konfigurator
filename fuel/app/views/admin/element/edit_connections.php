<h2><?php echo $element->name . ' (' . strtolower($element->type) . ')'; ?></h2>
<br>

<?php echo Form::open(array("class"=>"form-horizontal")); ?>

<div class="row">

	<div class="col-md-4">

		<?php if ($lists['COLUMN'] && ($element->type == 'CROWN' || ($element->type == 'LAMP' && $element->connection == 'UP'))): ?>
		<table class="table table-striped sortable">
			<thead>
				<tr>
					<th><input type="checkbox" onClick="toggle(this, 'column')" /> All</th>
					<th>Columns</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($lists['COLUMN'] as $item): ?>
				<tr>
					<td>
						<input type="checkbox" name="connections[]" <?php if(in_array($item->id, $connected)) echo 'checked="checked"'; ?> class="column" value="<?php echo $item->id.','.$item->type; ?>"></td>
					<td><?php echo $item->id . ' - ' . $item->name; ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

		<?php else: ?>
		<p>No Column.</p>
		<?php endif; ?>

	</div>


	<div class="col-md-4" <?php if ($element->type == 'CROWN') echo 'style="display:none"' ?> >

		<?php if ($lists['CROWN']): ?>
		<table class="table table-striped sortable">
			<thead>
				<tr>
					<th><input type="checkbox" onClick="toggle(this, 'crown')" /> All</th>
					<th>Crowns</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($lists['CROWN'] as $item): ?>
				<tr>
					<td><input type="checkbox" name="connections[]" <?php if(in_array($item->id, $connected)) echo 'checked="checked"'; ?> class="crown" value="<?php echo $item->id.','.$item->type; ?>"></td>
					<td><?php echo $item->id . ' - ' . $item->name; ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

		<?php else: ?>
		<p>No Crown.</p>
		<?php endif; ?>

	</div>
	<div class="col-md-4" <?php if ($element->type == 'CROWN') echo 'style="display:none"' ?> >

		<?php if ($lists['KINKIET']): ?>

		<table class="table table-striped sortable">
			<thead>
				<tr>
					<th><input type="checkbox" onClick="toggle(this, 'kinkiet')" /> All</th>
					<th>Kinkiet</th>
				</tr>
			</thead>
			<tbody>
		<?php foreach ($lists['KINKIET'] as $item): ?>
				<tr>
					<td><input type="checkbox" name="connections[]" <?php if(in_array($item->id, $connected)) echo 'checked="checked"'; ?> class="kinkiet" value="<?php echo $item->id.','.$item->type; ?>"></td>
					<td><?php echo $item->id . ' - ' . $item->name; ?></td>
				</tr>
		<?php endforeach; ?>
			</tbody>
		</table>

		<?php else: ?>
		<p>No Kinkiet.</p>
		<?php endif; ?>

	</div>
</div>
<div class="form-group">
	<label class='control-label'>&nbsp;</label>
	<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
</fieldset>
<?php echo Form::close(); ?>

<script type="text/javascript">
function toggle(source, className) {
  checkboxes = document.getElementsByClassName(className);
  for(var i=0, n=checkboxes.length;i<n;i++) {
    checkboxes[i].checked = source.checked;
  }
}
</script>

<p>
	<!-- <?php echo Html::anchor('admin/element/view/'.$element->id, 'View'); ?> | -->
	<?php echo Html::anchor('admin/' . strtolower($element->type), 'Back'); ?></p>
