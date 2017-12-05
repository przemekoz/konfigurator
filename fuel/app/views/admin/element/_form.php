<?php echo Form::open(array("class"=>"form-horizontal", "enctype"=>"multipart/form-data"),
	array(
		'image_size_x' => isset($element) ? $element->image_size_x : 0,
		'image_size_y' => isset($element) ? $element->image_size_y : 0,
		'type' => $elementType
	)); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Name', 'name', array('class'=>'control-label')); ?>
			<?php echo Form::input('name', Input::post('name', isset($element) ? $element->name : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Name', 'autofocus')); ?>
		</div>
		<div class="form-group">
			<?php echo Form::label('Weight', 'weight', array('class'=>'control-label')); ?>
			<?php echo Form::input('weight', Input::post('weight', isset($element) ? $element->weight : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Weight', 'autofocus')); ?>
		</div>

		<?php if ($elementType == 'COLUMN' ) : ?>
			<div class="form-group">
				<?php echo Form::label('Material', 'column_category_material', array('class'=>'control-label')); ?>
				<?php echo Form::select('column_category_material', Input::post('column_category_material', isset($element) ? $element->column_category_material : 0), $columnMaterials, array('class' => 'col-md-4 form-control', 'placeholder'=>'Category')); ?>
			</div>
		<?php else : ?>
			<input type="hidden" name="column_category_material" value="0"/>
		<?php endif; ?>

		<?php if ($elementType == 'OTHER' || $elementType == 'LAMP' ) : ?>
			<div class="form-group">
				<?php echo Form::label('Category', 'category', array('class'=>'control-label')); ?>
				<?php echo Form::select('category', Input::post('category', isset($element) ? $element->category : 'CITY'), array('CITY'=>'City/street', 'HOME'=>'Home/garden'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Category')); ?>
			</div>
		<?php else : ?>
			<input type="hidden" name="category" value="-"/>
		<?php endif; ?>

		<?php if ($elementType == 'CROWN' || $elementType == 'LAMP'  || $elementType == 'KINKIET' ) : ?>
			<div class="form-group">
					<?php echo Form::label('Connection', 'connection', array('class'=>'control-label')); ?>
					<?php echo Form::select('connection', Input::post('connection', isset($element) ? $element->connection : 'UP'), array('UP'=>'Up', 'DOWN'=>'Down'), array('class' => 'col-md-4 form-control', 'placeholder'=>'Connection')); ?>
			</div>
		<?php else : ?>
			<input type="hidden" name="connection" value="UP"/>
		<?php endif; ?>

		<div class="form-group">
			<?php echo Form::label('Image', 'file', array('class'=>'control-label')); ?>
			<?php echo Form::input('file', Input::post('file', ''), array('type' => 'file')); ?>
		</div>

		<?php if (isset($element)) : ?>
			<div class="form-group">
				<img src="<?php echo $element->getImageSrc(); ?>" height="200"/>
			</div>
		<?php endif ?>

		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
			<?php if ($elementType != 'OTHER') : ?>
				<?php echo Form::submit('submit', 'Save and edit connection points', array('class' => 'btn btn-primary')); ?>
			<?php endif ?>
		</div>
	</fieldset>
<?php echo Form::close(); ?>


