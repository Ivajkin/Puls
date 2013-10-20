<?php	
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache");
	//header("Expires: Sat, 26 Jul 1997 00:00:00 GMT"); 
	//require "../AuthFiles/auth.php";
	$pathTojs = '/js/';
	if ( !preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]) ) {
		$enc = 'UTF-8';
		header('Content-type: text/html; charset='.$enc);
		mb_internal_encoding($enc);
	} else {
		$enc = 'windows-1251';
		header('Content-type: text/html; charset='.$enc);
		mb_internal_encoding($enc);
	}
	function rus2translit($string)
	{
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'yo',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'kh',   'ц' => 'ts',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => "'",  'ы' => 'y',   'ъ' => '"',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
	 
			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'Yo',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'Kh',   'Ц' => 'Ts',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => "'",  'Ы' => 'Y',   'Ъ' => '"',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		return strtr($string, $converter);
	}
	function utf8($value)
	{
		return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
	}

	//For car delete
	function correction($carId, $carArray){
		foreach($carArray as $key => $value){
			foreach($value->model as $mKey => $mValue){
				if($mValue > $carId){
					echo 'Correction from model '.$mValue.' to '.intval($mValue - 1).'<br />';
					$carArray[$key]->model[$mKey] = intval($mValue) - 1;
				}
			}
		}
		return $carArray;
	}
	function deleteFromArray($carId, $array, $arrIndex){
		if(count($array[$arrIndex]->model) > 0){
			foreach($array[$arrIndex]->model as $key => $value){
				if($value == $carId){
					echo 'Current model for delete: '.$value.' on '.$key.'<br />';
					unset($array[$arrIndex]->model[$key]);
					$array[$arrIndex]->model = array_values($array[$arrIndex]->model);
					break;
				}
			}
			return correction($carId, $array);
		}
		return $array;
	}
	//Main function for correction after delete
	function FileCorrection($filename, $carId, $key){
		$file = file_get_contents($filename);
		$array = json_decode($file);
		$array = deleteFromArray($carId,$array,$key);
		$jsonData = json_encode($array);
		file_put_contents($filename, $jsonData);
	}
	function FileCorrectionWFO(&$array, $carId, $key){
		$array = deleteFromArray($carId,$array,$key);
	}
	//Is a Member of this Array?
	function isMember($array, $var){
		foreach($array as $value){
			if($var == $value){
				return true;
			}	
		}
		return false;
	}
	//For Brand and Type ONLY
	function addToDatabase($filename, $id, $data) {
		$file = file_get_contents($filename);
		$array = json_decode($file);
		array_push($array, $data);
		$jsonData = json_encode($array);
		file_put_contents($filename, $jsonData);
	}
	function deleteImage($path) {
		if($path === 0){
			echo 'Image: no path:'.$path;
			return true;
		}
		if(!strcmp(mb_substr($path, 0, 7), "http://")) {
			echo 'Image: url';
			return true;
		}
		$path = '../'.$path;
		$path_parts = pathinfo($path);
		$dirName = $path_parts["dirname"];
		echo 'Deleting image '.$path_parts["filename"].' from '.$dirName.' <br />';
		
		unlink($path);
		$handle = opendir($dirName);
		$c = 0;
		while ($file = readdir($handle) && $c < 3) {
			$c++;
		}
		if ($c <= 2) {
			echo 'Directory removed<br />';
			rmdir($dirName);
		}


	}
?>