<h2><?php echo $element->name; ?></h2>
<br>

<div class="row">

	<div class="col-md-8">
		<?php echo $element->image_size_x; ?> x <?php echo $element->image_size_y; ?>
		<div id="canvas" onclick="pointAdd(event)" onmousemove="showCoords(event)" onmouseout="clearCoor()" style="border: 1px dashed gray; width: <?php echo $element->image_size_x; ?>px;
			height: <?php echo $element->image_size_y; ?>px; position: relative;
			background: url(<?php echo $element->getImageSrc(); ?>);">
			<div id="canvas_points" style="position: absolute; width: 100%; height: 100%;"></div>
			<div id="txt" style="padding: 1.3em; background-color:rgba(255, 255, 255, 0.8); text-align:center; color: black; display:none; position: absolute; left: 10px;"></div>
			<div id="ver" style="position: absolute; width:1px; height: <?php echo $element->image_size_y; ?>px; border:initial; border-right: 1px solid red"></div>
			<div id="hor" style="position: absolute; width: <?php echo $element->image_size_x; ?>px; height: 1px; border-bottom: 1px solid red"></div>
		</div>
		<?php echo $element->image_size_x; ?> x <?php echo $element->image_size_y; ?>
	</div>

	<div class="col-md-4">
		<div style="position:fixed; top:15%; background-color:rgba(255, 255, 255, 0.8);">
			Max count of points: <span id="maxPointsLength"></span>
			<div id="pointsManager"></div>
		</div>
	</div>

</div>


<?php echo Form::open(array("class"=>"form-horizontal")); ?>
<fieldset>
	<div class="form-group">
		<input type="hidden" name="points" id="points" value="" />
	</div>

	<div class="form-group">
		<label class='control-label'>&nbsp;</label>
		<?php echo Form::submit('submit', 'Save', array('class' => 'btn btn-primary')); ?>
	</div>
	</fieldset>
	<?php echo Form::close(); ?>


<script type="text/javascript">
var maxPointsLength = <?php echo ($element->type == 'CROWN') ? 4 : 1; ?>;
document.getElementById('maxPointsLength').innerHTML = maxPointsLength;
var points = <?php echo $points; ?>;
var connection = '<?php echo $element->connection; ?>';
var nextPointType = 'POINT';
init();

function setPointsInput () {
	var input = document.getElementById('points');
	input.value = JSON.stringify(points);
}

function init () {
	setPointsInput();
	renderPointsManager(points);
}

function getBackground (index) {
	var bg = ['red', 'green', 'blue', 'yellow'];
	var bgImg = bg[index] || 'red';
	return 'background: url(/assets/img/aim_' + bgImg + '_big.png);';
}

function renderPointsManager (points) {
	renderPointsImage(points);
	var html = '';
	_.each (points, function (item, index) {
		var isPoint = index === 0 ? 'selected="selected"' : '';
		var isLamp = index > 0 ? 'selected="selected"' : '';
		html += '<tr>';
		html += '<td><div style="' + getDotStyle(index) + '"></div></td>';
		html += '<td><input class="form-control" type="text" id="point_x_' + index + '" style="width:70px" value="' + item.x + '"></td>';
		html += '<td><input class="form-control" type="text" id="point_y_' + index + '" style="width:70px" value="' + item.y + '"></td>';
		html += '<td><select <?php echo ($element->type == 'CROWN') ? '' : 'disabled="disabled"'; ?> class="form-control" onchange="pointChangeType(' + index + ')" id="point_type_' + index + '"><option ' + isPoint + '>POINT</option><option ' + isLamp + '>LAMP</option></select></td>';
		html += '<td>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="pointMove(' + index + ')" title="move">move</a> | <a href="javascript:void(0)" onclick="pointDelete(' + index + ')" title="delete">delete</a></td>';
		html += '</tr>';
	});
	var result = '<table><tr><th></th><th style="text-align:center">X</th><th style="text-align:center">Y</th><th style="text-align:center">type</th><th></th></tr>' + html + '</table>';
	document.getElementById('pointsManager').innerHTML = result;
}

function renderPointsImage (points) {
	var myNode = document.getElementById('canvas_points');
	while (myNode.firstChild) {
	    myNode.removeChild(myNode.firstChild);
	}
	_.each (points, function (item, index) {
		var node = document.createElement('div');
		var x = item.x - 13;
		var y = item.y - 13;
		node.style = getDotStyle(index) + 'position: absolute; left: ' + x + 'px; top: ' + y + 'px;';
		document.getElementById('canvas_points').appendChild(node);
	});
}

function getDotStyle (index) {
	return 'width: 27px; height: 27px; ' + getBackground(index);
}

function pointAdd (event) {
	if (points.length < maxPointsLength) {
		var point = getXYPoint (event);
		if (points.length > 0) {
			nextPointType = 'LAMP';
		}
		points.push({x: point.x, y: point.y, type: nextPointType});
		renderPointsManager(points);
		setPointsInput();
	}
}

function pointMove (index) {
	points[index].x = parseInt(document.getElementById('point_x_' + index).value, 10);
	points[index].y = parseInt(document.getElementById('point_y_' + index).value, 10);
	renderPointsImage(points);
	setPointsInput();
}

function pointChangeType (index) {
	points[index].type = document.getElementById('point_type_' + index).value;
	setPointsInput();
}

function pointDelete (index) {
	if (index > -1) {
    points.splice(index, 1);
		renderPointsManager(points);
		setPointsInput();
	}
}

function getXYPoint (event) {
	var x = event.clientX - event.currentTarget.getBoundingClientRect().left;
	var y = event.clientY - event.currentTarget.getBoundingClientRect().top;
	return {x: Math.round(x), y: Math.round(y)};
}

function showCoords(event) {
		if (points.length >= maxPointsLength) {
			clearCoor();
		}
		else {
			var point = getXYPoint (event);
			document.getElementById('hor').style.top = point.y + 'px';
			document.getElementById('ver').style.left = point.x + 'px';
			document.getElementById('txt').innerHTML = point.x + ' x ' + point.y;
			document.getElementById('txt').style.top = (point.y + 10)  + 'px';
			document.getElementById('txt').style.display = 'block';
			document.getElementById('hor').style.display = 'block';
			document.getElementById('ver').style.display = 'block';
		}
}

function clearCoor() {
	document.getElementById('hor').style.display = 'none';
	document.getElementById('ver').style.display = 'none';
	document.getElementById('txt').style.display = 'none';
}
</script>

<p>
	<!-- <?php echo Html::anchor('admin/element/view/'.$element->id, 'View'); ?> | -->
	<?php echo Html::anchor('admin/' . strtolower($element->type), 'Back'); ?></p>
