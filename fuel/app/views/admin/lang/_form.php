<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>
		<div class="form-group">
			<?php echo Form::label('Code', 'code', array('class'=>'control-label')); ?>

				<?php echo Form::input('code', Input::post('code', isset($lang) ? $lang->code : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Code', 'autofocus')); ?>

		</div>
		<div class="form-group">
			<?php echo Form::label('Label', 'label', array('class'=>'control-label')); ?>

				<?php echo Form::input('label', Input::post('label', isset($lang) ? $lang->label : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Label')); ?>

		</div>
		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>
