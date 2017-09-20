<?php echo Form::open(array("class"=>"form-horizontal")); ?>

	<fieldset>

		<div class="form-group">
			<?php echo Form::label('Key', 'key', array('class'=>'control-label')); ?>

				<?php echo Form::input('key', Input::post('key', isset($dictionary) ? $dictionary->key : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Key', 'autofocus')); ?>

		</div>
		<?php foreach ($langs as $lang): ?>

			<!-- <div class="form-group"> -->
				<!-- <?php echo Form::label('Lang code', 'lang_code', array('class'=>'control-label')); ?> -->

				<!-- <?php echo Form::select('lang_code', Input::post('lang_code', isset($dictionary) ? $dictionary->lang_code : 'PL'), $langs, array('class' => 'col-md-4 form-control', 'placeholder'=>'Lang code')); ?> -->

			<!-- </div> -->
			<div class="form-group">
				<?php echo Form::label($lang, 'message', array('class'=>'control-label')); ?>

				<?php echo Form::input('message_' . $lang, Input::post('message_' . $lang, isset($messages) ? $messages[$lang] : ''), array('class' => 'col-md-4 form-control', 'placeholder'=>'Message')); ?>

			</div>

		<?php endforeach; ?>


		<div class="form-group">
			<label class='control-label'>&nbsp;</label>
			<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>		</div>
	</fieldset>
<?php echo Form::close(); ?>
