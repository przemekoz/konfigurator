<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Title', 'title', array('class'=>'control-label')); ?>

				<?php echo Form::input('title', Input::post('title', isset($column_category) ? $column_category->title : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Title')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('From', 'from', array('class'=>'control-label')); ?>

				<?php echo Form::input('from', Input::post('from', isset($column_category) ? $column_category->from : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'From')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('To', 'to', array('class'=>'control-label')); ?>

				<?php echo Form::input('to', Input::post('to', isset($column_category) ? $column_category->to : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'To')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>