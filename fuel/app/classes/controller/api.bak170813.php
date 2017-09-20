<?php
require(PKGPATH . 'fpdf/fpdf.php');

class PDF extends FPDF {
  // Simple table
  function BasicTable($pdf, $header, $data, $filename)
  {
    // Header
    $this->SetX(15);
    $this->Cell(40, 28, '', 1);
    $X = 55;
    $this->SetX($X);
    $pdf->SetFont('Arial','',9);
    $this->Cell(50, 23, $filename, 1);
    $pdf->SetFont('Arial','',7);

    $width = array();
    foreach($header as $col) {
      $this->Cell($col[0], 8, $col[1], 1);
      $width[] = $col[0];
    }
    $this->Ln();
    // Data
    for ($i = 0; $i < 3; $i++) {
      $row = isset($data[$i]) ? $data[$i] : array('', '', '');
      $this->SetX($X);
      $this->Cell(50, 5, '', 0);
      foreach($row as $key => $text) {
        // width cell, height cell, content, border
        $this->Cell($width[$key], 5, $text, 1);
      }
      $this->Ln();
    }

    $this->SetX($X);
    $this->Cell(array_sum($width) + 50, 5, 'PROMAR A. Zaranski Sp. j.  |  ul. Gruntowa 114 44-210 Rybnik[PL]  |  T/F +48 32 4237111  |  email: biuro@promar-sj.com.pl', 1);

  }
};

class Controller_Api extends Controller_Rest
{
    public function get_pdf() {
      getPdf(intval(Input::get('firstId')), intval(Input::get('secondId')), intval(Input::get('thirdId')));
    }

    public function get_sendmail() {
        $content = getPdf(intval(Input::get('firstId')), intval(Input::get('secondId')), intval(Input::get('thirdId')), 'S');

        $mailto = 'biuro@promar-sj.com.pl';
        // $mailto = 'przemekkozinski@gmail.com';

        $subject = 'Zapytanie - konfigurator';
        $message = Input::get('message');

        $content = chunk_split(base64_encode($content));

        // a random hash will be necessary to send mixed content
        $separator = md5(time());

        // carriage return type (RFC)
        $eol = "\r\n";

        // main header (multipart mandatory)
        $headers = "From: " . (Input::get('name') ? Input::get('name') : Input::get('email')) . " <" . Input::get('email') .">" . $eol;
        $headers .= "MIME-Version: 1.0" . $eol;
        $headers .= "Content-Type: multipart/mixed; boundary=\"" . $separator . "\"" . $eol;
        $headers .= "Content-Transfer-Encoding: 7bit" . $eol;
        $headers .= "This is a MIME encoded message." . $eol;

        // message
        $body = "--" . $separator . $eol;
        $body .= "Content-Type: text/plain; charset=\"utf-8\"" . $eol;
        $body .= "Content-Transfer-Encoding: 8bit" . $eol;
        $body .= $message . $eol;

        // attachment
        $body .= "--" . $separator . $eol;
        $body .= "Content-Type: application/octet-stream; name=\"promar.pdf\"" . $eol;
        $body .= "Content-Transfer-Encoding: base64" . $eol;
        $body .= "Content-Disposition: attachment" . $eol;
        $body .= $content . $eol;
        $body .= "--" . $separator . "--";

        //SEND Mail
        if (mail($mailto, $subject, $body, $headers)) {
            $result = 'ok';
        } else {
            $result = 'fail';
        }

        return $this->response(array(
          'result' => $result
        ));
    }

    public function get_imagecache() {
      // get imagecache where updated_at == null, first 2 elements
      $list = Model_Imagecache::find('all', array(
  			'where' => array(array('updated_at', NULL), array('lamp_id', 0)),
        'limit' => '2'
      ));

      // then 3 elements
      if (empty($list)) {
        $list = Model_Imagecache::find('all', array(
    			'where' => array(array('updated_at', NULL)),
          'limit' => '1'
        ));
      }

      foreach ($list as $item) {
          $result = getPreview($item->main_id, $item->sub_id, $item->lamp_id, true);
          if ($result != 'NO_PREVIEW') {
            $item->updated_at = 'NOW()';
            if ($item->save()) {
              echo '<br>generated: ' . $item->main_id .' - '. $item->sub_id .' - '. $item->lamp_id;
            }
            else {
              echo '<br>no generated: ' . $item->main_id .' - '. $item->sub_id .' - '. $item->lamp_id;
            }
          }
      }
    }

		// http://localhost/api/list.php?type=COLUMN&category=CITY&columnCategory=2
    public function get_list()
    {
			$reqType = in_array(Input::get('type'), array('COLUMN', 'OTHER', 'KINKIET')) ?
				Input::get('type') : '-';
			$reqCategory = Input::get('category') == 'CITY' ? 'CITY' : 'HOME';
			$reqColumnCategory = intval(Input::get('columnCategory'));

			$where = array(
					array('type', $reqType),
					array('category', $reqCategory)
			);

			if ($reqColumnCategory) {
				$where[] = array('column_category', $reqColumnCategory);
			}

			$list = Model_Element::find('all', array(
				'where' => $where, 'order_by' => 'sort_order'));


			$response = array();
			foreach ($list as $item) {
				$response[] = $item->to_array();
			}

			return $this->response($response);
    }

		// http://localhost/api/`preview`.php?firstId=1
		// http://localhost/api/preview.php?firstId=1&secondId=2
		// http://localhost/api/preview.php?firstId=1&secondId=2&thirdId=3
		public function get_preview()
		{
			$reqFirstId = intval(Input::get('firstId'));
			$reqSecondId = intval(Input::get('secondId'));
			$reqThirdId = intval(Input::get('thirdId'));

      return $this->response(array(
        'preview' => getPreview($reqFirstId, $reqSecondId, $reqThirdId)
      ));
		}

    public function get_config()
		{
      $sizes = getSizesDefault();
      $materials = getMaterialsDefault();

      return $this->response(array(
        'dictionary' => getDictionary(),
        'sizes' => $sizes[0],
        'materials' => $materials[0],
        'defaultSize' => $sizes[1],
        'defaultMaterial' => $materials[1]
      ));
		}

    public function get_others() {
      $result = Model_Element::find('all', array(
        'where' => Filter::getWhere(array('type', 'OTHER')), 'order_by' => array(array('default', 'desc'), array('sort_order', 'asc'))));

        $response = array();
  			foreach ($result as $item) {
          $arr = $item->to_array();
          $arr['src'] = Images::getImageSrc($item->getImageSrc('mini'));
  				$response[] = $arr;
  			}

        $preview = 'NO_PREVIEW';
        if (!empty($result)) {
          $preview = getPreview(array_values($result)[0]->id);
        }
        return $this->response(array(
  				'others' => $response,
          'preview' => $preview
  			));
    }

    //http://localhost/api/lamps.php?firstId=1
    public function get_lamps() {
      $query = DB::query("
          SELECT
          " . getSelectElements() . "

          FROM
          (select * from elements where type = 'COLUMN') as col,
          (select * from elements where type = 'LAMP') as lam,
          (select * from elements where type = 'CROWN') as cro,
          columncategories as size,
          columnmaterials as material,
          element_connections as lamp_crown,
          element_connections as crown_column

          WHERE
          col.column_category = size.id AND
          col.column_category_material = material.id AND

          #connection between lamp and crown
          lamp_crown.element_id = lam.id AND lamp_crown.element_type = 'LAMP' AND
          lamp_crown.element_id_ref = cro.id AND lamp_crown.element_ref_type = 'CROWN' AND

          #connection between crown and column
          crown_column.element_id = lamp_crown.element_id_ref AND crown_column.element_type = 'CROWN' AND
          crown_column.element_id_ref = col.id AND crown_column.element_ref_type = 'COLUMN' AND

          " . getWhere() . "

          ORDER BY
          lam.default desc, lam.sort_order, cro.default desc, cro.sort_order, col.default desc, col.sort_order, size.default desc, material.default desc");

      $result = $query->execute();
      $lamps = getElems($result, 'lam.id', array('lam.name', 'lam.image_size_x', 'lam.image_size_y'));
      if (empty($lamps)) {
        return $this->response(array(
            'lamps' => array(),
            'crowns' => array(),
            'columns' => array(),
            'preview' => 'NO_PREVIEW'
        ));
      }
      else {
        //Set to first element of array
        reset($lamps);

        $crowns = getElems($result, 'cro.id', array('cro.name', 'cro.image_size_x', 'cro.image_size_y'), $lamps[0]['lam.id']);
        $columns = getElems($result, 'col.id', array('col.name', 'col.image_size_x', 'col.image_size_y'), $lamps[0]['lam.id']);

        //Set to first element of array
        reset($crowns);
        reset($columns);

        return $this->response(array(
            'lamps' => $lamps,
            'crowns' => $crowns,
            'columns' => $columns,
            'preview' => getPreview(current($columns)['col.id'], current($crowns)['cro.id'], current($lamps)['lam.id'])
        ));
      }
    }

    //http://localhost/api/lampcolumn.php?firstId=1
    public function get_lampcolumn() {
      $query = DB::query("
          SELECT
          " . getSelectElements('withoutCrowns') . "

          FROM
          (select * from elements where type = 'COLUMN') as col,
          (select * from elements where type = 'LAMP') as lam,
          columncategories as size,
          columnmaterials as material,
          element_connections as lamp_column

          WHERE
          col.column_category = size.id AND
          col.column_category_material = material.id AND

          #connection between lamp and column
          lamp_column.element_id = lam.id AND lamp_column.element_type = 'LAMP' AND
          lamp_column.element_id_ref = col.id AND lamp_column.element_ref_type = 'COLUMN' AND

          " . getWhere() . "

          ORDER BY
          lam.default desc, lam.sort_order, col.default desc, col.sort_order, size.default desc, material.default desc");

        $result = $query->execute();
        $lamps = getElems($result, 'lam.id', array('lam.name', 'lam.image_size_x', 'lam.image_size_y'));
        if (empty($lamps)) {
            return $this->response(array(
                'lamps' => array(),
                'columns' => array(),
                'preview' => 'NO_PREVIEW'
            ));
        }
        else {
            //Set to first element of array
            reset($lamps);

            $columns = getElems($result, 'col.id', array('col.name', 'col.image_size_x', 'col.image_size_y'), $lamps[0]['lam.id']);
            //Set to first element of array
            reset($columns);

            return $this->response(array(
                'lamps' => $lamps,
                'columns' => $columns,
                'preview' => getPreview(current($columns)['col.id'], current($lamps)['lam.id'], 0)
            ));
        }
        
    }

    //http://localhost/api/kinkiet.php?firstId=1
    public function get_kinkiet() {
      $query = DB::query("
          SELECT
          " . getSelectElements('kinkiet') . "

          FROM
          (select * from elements where type = 'KINKIET') as kin,
          (select * from elements where type = 'LAMP') as lam,
          element_connections as lamp_kinkiet

          WHERE

          #connection between lamp and kinkiet
          lamp_kinkiet.element_id = lam.id AND lamp_kinkiet.element_type = 'LAMP' AND
          lamp_kinkiet.element_id_ref = kin.id AND lamp_kinkiet.element_ref_type = 'KINKIET' AND

          " . getKinkietWhere() . "

          ORDER BY
          lam.default desc, lam.sort_order, kin.default desc, kin.sort_order");

      $result = $query->execute();
      $lamps = getElems($result, 'lam.id', array('lam.name', 'lam.image_size_x', 'lam.image_size_y'));
      
      if (empty($lamps)) {
        return $this->response(array(
            'lamps' => array(),
            'kinkiet' => array(),
            'preview' => 'NO_PREVIEW'
        ));
      }
      else {
        //Set to first element of array
        reset($lamps);

        $kinkiet = getElems($result, 'kin.id', array('kin.name', 'kin.image_size_x', 'kin.image_size_y'), $lamps[0]['lam.id']);
        //Set to first element of array
        reset($kinkiet);

            return $this->response(array(
                'lamps' => $lamps,
                'kinkiet' => $kinkiet,
                'preview' => getPreview(current($kinkiet)['kin.id'], current($lamps)['lam.id'], 0)
            ));
      }
    }
}

// -----------------------------------------------------------------------------

function preview($reqFirstId, $reqSecondId, $reqThirdId, $reGenerate = false, $prefix = 'maxi') {
  $allSrc = '';
  $first = null;
  $second = null;
  $third = null;

  if ($reqFirstId) {
    $first = Model_Element::find($reqFirstId);
  }

  if ($reqSecondId) {
    $second = Model_Element::find($reqSecondId);
  }

  if ($reqThirdId) {
    $third = Model_Element::find($reqThirdId);
  }

  if ($reqFirstId && $reqSecondId == 0) {
    $allSrc = Images::getImageSrc(Images::getTwoOrThreeApi($prefix, array(array($first->id, $first->type)), $reGenerate));
  }
  else if ($reqFirstId && $reqSecondId && $reqThirdId == 0) {
    $allSrc = Images::getImageSrc(Images::getTwoOrThreeApi($prefix, array(array($first->id, $first->type), array($second->id, $second->type)), $reGenerate));
  }
  else if ($reqFirstId && $reqSecondId && $reqThirdId) {
    $allSrc = Images::getImageSrc(Images::getTwoOrThreeApi($prefix, array(array($first->id, $first->type), array($second->id, $second->type), array($third->id, $third->type)), $reGenerate));
  }

  return array(
    'first' => $first,
    'second' => $second,
    'third' => $third,
    'img' => empty($allSrc) ? 'NO_PREVIEW' : $allSrc
  );
}

function getPreview($reqFirstId, $reqSecondId = 0, $reqThirdId = 0, $reGenerate = false, $prefix = 'maxi_bck') {
  return preview($reqFirstId, $reqSecondId, $reqThirdId, $reGenerate, $prefix)['img'];
}

function getElems($arr, $id, $fields, $onlyForId = 0) {
    $result = [];

    $mapTypes = array('lam.id' => 'LAMP', 'cro.id' => 'CROWN', 'col.id' => 'COLUMN', 'kin.id' => 'KINKIET');

    foreach ($arr as $item) {
        $elems = array_column($result, $id);
        if (!in_array($item[$id], $elems) && ($onlyForId == 0 || $item['lam.id'] == $onlyForId)) {
            $res = array($id => $item[$id]);
            foreach ($fields as $name) {
                $res[getName($name)] = $item[$name];
            }
            $res['src'] = Helper::getBaseUrl() . 'upload/mini_' . $item[$id] . '_' . $mapTypes[$id] . '.png';
            $result[] = $res;
        }
    }
    return $result;
}

function getName($name) {
  $result = str_replace('lam.', '', $name);
  $result = str_replace('cro.', '', $result);
  $result = str_replace('col.', '', $result);
  $result = str_replace('oth.', '', $result);
  $result = str_replace('kin.', '', $result);
  return $result;
}

function getSelectElements($withoutCrowns = false) {
  $select = array();
  if ($withoutCrowns == 'kinkiet') {
    $types = array('lam', 'kin');
  }
  else if ($withoutCrowns) {
    $types = array('lam', 'col');
  }
  else {
    $types = array('lam', 'cro', 'col');
  }
  $fields = array('id', 'name', 'image_size_x', 'image_size_y');
  foreach ($types as $type) {

    foreach ($fields as $field) {
      $select[] = $type . '.' .$field . " as '" . $type . '.' .$field . "'";
    }

  }
  return implode(', ', $select);
}

function getWhere() {
  $where = array();

  if (strlen(Input::get('category'))) {
    $where[] = "col.category = '" . (Input::get('category') == 'CITY' ? 'CITY' : 'HOME') . "'";
  }
  else {
    $where[] = "col.category = 'CITY'";
  }

  if (strlen(Input::get('connection'))) {
    $where[] = "lam.connection = '" . (Input::get('connection') == 'UP' ? 'UP' : 'DOWN') . "'";
  }
  else {
    $where[] = "lam.connection = 'UP'";
  }

  if (!empty(Input::get('size'))) {
    $where[] = 'size.id = ' . intval(Input::get('size'));
  }
  else {
    $sizes = getSizesDefault();
    $where[] = 'size.id = ' . $sizes[1];
  }

  if (!empty(Input::get('material'))) {
    $where[] = 'material.id = ' . intval(Input::get('material'));
  }
  else {
    $materials = getMaterialsDefault();
    $where[] = 'material.id = ' . $materials[1];
  }

  if (strlen(Input::get('lampId'))) {
    $where[] = "lam.id = '" . intval(Input::get('lampId')) . "'";
  }
  else if (strlen(Input::get('crownId'))) {
    $where[] = "cro.id = '" . intval(Input::get('crownId')) . "'";
  }

  return implode(' AND ', $where);
}

function getKinkietWhere() {
  $where = array();

  if (strlen(Input::get('category'))) {
    $where[] = "kin.category = '" . (Input::get('category') == 'CITY' ? 'CITY' : 'HOME') . "'";
  }
  else {
    $where[] = "kin.category = 'CITY'";
  }

  if (strlen(Input::get('connection'))) {
    $where[] = "lam.connection = '" . (Input::get('connection') == 'UP' ? 'UP' : 'DOWN') . "'";
  }
  else {
    $where[] = "lam.connection = 'UP'";
  }

  return implode(' AND ', $where);
}

function getMaterialsDefault() {
  $result = array();
  $items = Model_Columnmaterial::find('all');
  foreach ($items as $item) {
    if (empty($result) || $item->default) {
      $default = $item->id;
    }
    $name = explode('#', $item->title);
    $result[] = array('id' => $item->id, 'text' => $name[1]);
  }
  return array($result, $default);
}

function getSizesDefault() {
  $result = array();
  $items = Model_Columncategory::find('all');
  foreach ($items as $item) {
    if (empty($result) || $item->default) {
      $default = $item->id;
    }
    $result[] = array('id' => $item->id, 'text' => $item->title);
  }
  return array($result, $default);
}

function getDictionary() {
  $result = array();
  $items = Model_Dictionary::find('all');
  foreach ($items as $item) {
    $result[strtolower($item->lang_code)][$item->key] = $item->message;
  }
  return $result;
}

function getPdf($firstId, $secondId, $thirdId, $output = 'D') {
  $result = preview($firstId, $secondId, $thirdId, false, 'maxi_bck');

  if ($result['img'] == 'NO_PREVIEW') {
    echo 'No image.';
    return;
  }

  $pdf = new PDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','',7);
  $pdf->Rect(10, 10, 190, 280);

  list($imageWidth, $imageHeight) = getimagesize($result['img']);

  if ($thirdId == 0) {
    $startX = $imageWidth > 720 ? 10 : 70;
    $dpi = $imageWidth > 720 ? -220 : -180;
    $pdf->Image($result['img'], $startX, 40, $dpi);
  }
  else {
    $startX = $imageWidth > 720 ? 10 : 45;
    $pdf->Image($result['img'], $startX, 10, -130);
  }

  $pdf->Image(DOCROOT . 'assets/img/logo300.png', 21.5, 253.5, -300);
  $pdf->SetY(248.5);
  $header = array();
  $header[] = array(10, 'poz.');
  $header[] = array(60, 'nazwa czesci');
  $header[] = array(20, 'waga[kg]');
  $data = array();

  foreach (array('first', 'second', 'third') as $key => $elem) {
    if ($result[$elem]) {
      $data[] = array($key + 1 .'.', $result[$elem]->name, $result[$elem]->weight . ' kg');
    }
  }


  $filename = $result['first']->name;
  if ($secondId) {
    $filename .= '  ' . $result['second']->name;
  }
  if ($thirdId) {
    $filename .= '  ' . $result['third']->name;
  }
  $pdf->BasicTable($pdf, $header, $data, $filename);
  return $pdf->Output($output, getFilename($filename) . '__' . date('Y-m-d') . '.pdf');
}

function getFilename($string) {
  return preg_replace('/[^a-zA-Z0-9\-\._]/','_', $string);
}