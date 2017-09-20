<?php



///////////////////////////////////////////////////////////////////////////////
// use case

// $column = Model_Element::find(30);
// // UP
// $lamp = Model_Element::find(4);
// $crown = Model_Element::find(31);
// //
// $filename = Images::prepareFirstSecondElement($column, $lamp);
// Images::resize(DOCROOT . 'generated/', $filename, array('midi', 'maxi'));
// $filename = Images::prepareColumnCrownLamp($column, $crown, $lamp);
// Images::resize(DOCROOT . 'generated/', $filename, array('midi', 'maxi'));
// $filenameWithBck = prepareBacgroundElement($filename);
//
// // DOWN
// $lamp1 = Model_Element::find(8);
// $crown1 = Model_Element::find(16);
// $filename = Images::prepareColumnCrownLamp($column, $crown, $lamp);
// Images::resize(DOCROOT . 'generated/', $filename, array('midi', 'maxi'));
// $filenameWithBck = prepareBacgroundElement($filename);
//
// $filename = Images::prepareFirstSecondElement($column, $lamp);
// Images::resize(DOCROOT . 'generated/', $filename, array('midi', 'maxi'));
// $filenameWithBck = Images::prepareBacgroundElement($filename);
// Images::resize(DOCROOT . 'generated/', $filenameWithBck, array('maxi'));

// http://localhost/api/preview.php?firstId=30&secondId=31&thirdId=2

// use case
///////////////////////////////////////////////////////////////////////////////

ini_set('memory_limit','512M');
class Images
{
	public static $mainPath = 'upload/';
	public static $generatedPath = 'generated/';

	public static $sizes = array(
		'mini' => array(493, 370),
		// 'midi' => array(400, 600),
		'maxi' => array(1600, 1200)
	);

	public static function fileExists($path) {
		return File::exists(DOCROOT . $path);
	}

	public static function getImage($path) {
		if (Images::fileExists($path)) {
			return $path;
		}
		return 'assets/img/notfound.png';
	}

	// param like [[1, 'COLUMN'], [2, 'LAMP']]
	public static function getTwoOrThree($sizeName, $params) {
		$prefix = strlen($sizeName) ? $sizeName . '_' : '';
		return Images::$generatedPath . $prefix . Images::getFilename($params);
	}

	public static function getTwoOrThreeApi($sizeName, $params, $reGenerate = false) {

		$file = Images::getTwoOrThree($sizeName, $params);
		if (Images::fileExists($file) == false || $reGenerate) {
			if (count($params) == 1) {
				Images::prepareOne($params[0][0]);
			}
			else if (count($params) == 2) {
				Images::prepareTwo($params[0][0], $params[1][0]);
			}
			else if (count($params) == 3) {
				Images::prepareThree($params[0][0], $params[1][0], $params[2][0]);
			}
		}
		return $file;
	}

	// param like [[1, 'COLUMN'], [2, 'LAMP']]
	public static function getFilename($params) {
		$result = array();
		foreach ($params as $param) {
			list($id, $type) = $param;
			$result[] = $id . '-' . $type;
		}
		return implode('_', $result) . '.png';
	}

	public static function getImageSrc($path) {
		return Helper::getBaseUrl() . Images::getImage($path);
	}

	public static function prepareFilename($params) {
		$result = [];
		foreach ($params as $element) {
			$result[] = array($element->id, $element->type);
		}
		return $result;
	}

	public static function prepareOne($firstId) {
		$first = Model_Element::find($firstId);
		$filenameWithBck = Images::prepareBacgroundElement($first->getImageName(), 'normal', Images::$mainPath, Images::getFilename(Images::prepareFilename(array($first))));
		Images::resize(DOCROOT . 'generated/', $filenameWithBck, array('maxi'));
	}

	public static function prepareTwo($firstId, $secondId) {
		$first = Model_Element::find($firstId);
		$second = Model_Element::find($secondId);
		$filename = Images::prepareFirstSecondElement($first, $second);
		Images::resize(DOCROOT . 'generated/', $filename, array('maxi'));
		$filenameWithBck = Images::prepareBacgroundElement($filename);
		Images::resize(DOCROOT . 'generated/', $filenameWithBck, array('maxi'));
	}

	public static function prepareThree($firstId, $secondId, $thirdId) {
		$first = Model_Element::find($firstId);
		$second = Model_Element::find($secondId);
		$third = Model_Element::find($thirdId);
		$filename = Images::prepareColumnCrownLamp($first, $second, $third);
		Images::resize(DOCROOT . 'generated/', $filename, array('maxi'));
		$filenameWithBck = Images::prepareBacgroundElement($filename);
		Images::resize(DOCROOT . 'generated/', $filenameWithBck, array('maxi'));
	}

	public static function prepareFirstSecondElement($first, $second) {
			$filename = Images::getFilename(Images::prepareFilename(array($first, $second)));

			$humanY = 1950;
			$startPointY = 0;
			$newImageSizeX = 2 * $second->image_size_x + $first->image_size_x;
			$newImageSizeY = $first->image_size_y + $second->image_size_y;
			$startPointX = $second->image_size_x;

			if ($second->type == 'CROWN') {
				$startPointX = 0;
				$newImageSizeX = $second->image_size_x;
			}
			else if ($first->type == 'KINKIET') {
				$startPointY = $first->connection == 'DOWN' ? $first->image_size_y : 0;
				$newImageSizeY = $first->image_size_y + $second->image_size_y + $humanY;
			}

			$srcFirst = imagecreatefrompng(getSrc($first));
			$srcSecond = imagecreatefrompng(getSrc($second));

			$firstPoint = Model_Point::query()
			->where('elements_id', '=', $first->id)
			->get_one();

			$secondPoint = Model_Point::query()
			->where('elements_id', '=', $second->id)
			->get_one();

			$im = Images::prepareImage($newImageSizeX, $newImageSizeY);

			// --- Copy second (lamp, crown) ---
			imagecopy($im, $srcSecond, $startPointX, $startPointY, 0, 0, $second->image_size_x, $second->image_size_y);
			// --- Copy first (column, kinkiet) ---
			$moveXY = getMoveXY($secondPoint, $firstPoint, $startPointX, $startPointY);
			imagecopy($im, $srcFirst, $moveXY[0], $moveXY[1], 0, 0, $first->image_size_x, $first->image_size_y);

			Images::saveImage($im, DOCROOT . Images::$generatedPath . $filename);

			return $filename;
	}

	public static function prepareColumnCrownLamp($column, $crown, $lamp) {
			$filename = Images::getFilename(Images::prepareFilename(array($column, $crown, $lamp)));

			$newImageSizeX = 2 * $lamp->image_size_x + $crown->image_size_x;
			$newImageSizeY = $lamp->image_size_y + $crown->image_size_y + $column->image_size_y;
			$startPointX = $lamp->image_size_x;
			$startPointY = $lamp->image_size_y;

			$srcColumn = imagecreatefrompng(getSrc($column));
			$srcCrown = imagecreatefrompng(getSrc($crown));
			$srcLamp = imagecreatefrompng(getSrc($lamp));

			$columnPoint = Model_Point::query()
			->where('elements_id', '=', $column->id)
			->get_one();

			$lampPoint = Model_Point::query()
			->where('elements_id', '=', $lamp->id)
			->get_one();

			$crownPoints = Model_Point::find('all', array(
				'where' => array(
						array('elements_id', $crown->id)
				)));
				$im = Images::prepareImage($newImageSizeX, $newImageSizeY);


			// --- Copy crown ---
			imagecopy($im, $srcCrown, $startPointX, $startPointY, 0, 0, $crown->image_size_x, $crown->image_size_y);

			foreach ($crownPoints as $crownPoint) {
				if ($crownPoint->type == 'LAMP') {
					// --- Copy lamp ---
					$moveXY = getMoveXY($crownPoint, $lampPoint, $startPointX, $startPointY);
					imagecopy($im, $srcLamp, $moveXY[0], $moveXY[1], 0, 0, $lamp->image_size_x, $lamp->image_size_y);
				}
				else {
					// --- Copy column ---
					$moveXY = getMoveXY($crownPoint, $columnPoint, $startPointX, $startPointY);
					imagecopy($im, $srcColumn, $moveXY[0], $moveXY[1], 0, 0, $column->image_size_x, $column->image_size_y);
				}
			}

			Images::saveImage($im, DOCROOT . Images::$generatedPath . $filename);
			return $filename;
	}


	public static function prepareBacgroundElement($filename, $horizontalMove = 'small', $customPath = '', $destFilename = '') {
		$newFilename = 'bck_' . $filename;

		$path = Images::$generatedPath;
		if (strlen($customPath)) {
			$newFilename = 'bck_' . $destFilename;
			$path = $customPath;
		}

		// get image size
		$size = getimagesize(DOCROOT . $path . $filename);
		$fileSizeX = $size[0];
		$fileSizeY = $size[1];

		$background = DOCROOT . '/assets/img/background.png';
		$bckWidth = 3983;
		$bckHeight = 8087;

		$image_ = imagecreatefrompng(DOCROOT . $path . $filename);
		$background_ = imagecreatefrompng($background);

		// if image is tall than human
		$humanHeight = 1750;
		$humanWidth = 680;
		if ($horizontalMove == 'small' && $fileSizeX > 600 && $fileSizeY > $humanHeight) {
			$humanWidth = 250;
		}

		if ($fileSizeY > $humanHeight) {
			$elemHeight = 0;
			$bHeight = $fileSizeY;
			$imageHeight = $bckHeight - $fileSizeY;
		}
		else {
			$bHeight = $humanHeight;
			$elemHeight = $humanHeight - $fileSizeY;
			$imageHeight = $bckHeight - $humanHeight;
		}

		$im = Images::prepareImage($fileSizeX + $humanWidth, $bHeight);
		// background
		imagecopy($im, $background_, 0, 0, 0, $imageHeight, $bckWidth, $bckHeight);
		// Copy image
		imagecopy($im, $image_, $humanWidth, $elemHeight, 0, 0, $fileSizeX, $fileSizeY);

		Images::saveImage($im, DOCROOT . Images::$generatedPath . $newFilename);
		return $newFilename;
	}
	// ---------------------------------------------------------------------------

	public static function resize($path, $filename, $sizes) {
		$filenames = [];
		foreach ($sizes as $sizeName) {
			list($x, $y) = Images::$sizes[$sizeName];
			$filenames[] = Images::resizeImage($path, $filename, $sizeName, $x, $y);
		}
		return $filenames;
	}

	public static function resizeImage($path, $filename, $sizeName, $max_width, $max_height) {
		$orgFile = $path . $filename;
		$newFilename = $sizeName . '_'  . $filename;
		list($orig_width, $orig_height) = getimagesize($orgFile);

		$width = $orig_width;
		$height = $orig_height;

		# taller
		if ($height > $max_height) {
				$width = ($max_height / $height) * $width;
				$height = $max_height;
		}

		# wider
		if ($width > $max_width) {
				$height = ($max_width / $width) * $height;
				$width = $max_width;
		}

		$im = Images::prepareImage($width, $height);
		$image = imagecreatefrompng($orgFile);
		imagecopyresampled($im, $image, 0, 0, 0, 0, $width, $height, $orig_width, $orig_height);

		Images::saveImage($im, $path . $newFilename);
		return $newFilename;
	}

	protected static function saveImage($im, $output) {
		// Save transparency
		imagesavealpha($im, true);
		// Save PNG
		imagepng($im, $output, 9);
		imagedestroy($im);
	}

	protected static function prepareImage($newImageSizeX, $newImageSizeY) {
		$im = imagecreatetruecolor($newImageSizeX, $newImageSizeY);
		// Prepare alpha channel for transparent background
		$alpha_channel = imagecolorallocatealpha($im, 0, 0, 0, 127);
		imagecolortransparent($im, $alpha_channel);
		// Fill image
		imagefill($im, 0, 0, $alpha_channel);
		return $im;
	}
}

function getSrc($element) {
	return DOCROOT . '/upload/' . $element->id . '_' . $element->type . '.png';
}

function getMoveXY($mainPoint, $subPoint, $startPointX, $startPointY) {
	return array(
		$startPointX + $mainPoint->X - $subPoint->X,
	 	$startPointY + $mainPoint->Y - $subPoint->Y
	);
}
