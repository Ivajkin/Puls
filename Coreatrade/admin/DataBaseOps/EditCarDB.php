<?php

$carId = $_POST['edit'];
if(!is_numeric($carId)){
	echo $_POST['edit'];
	echo "false";
	return false;
}
require_once('DataBaseFunctions.php');

function updateInArray($filename,$carId,$index,$newIndex){
	if($index != $newIndex){
		$file= file_get_contents($filename);
		$DB = json_decode($file);
		if(count($DB[$index]->model) > 0){
			foreach($DB[$index]->model as $key => $value){
				if($value == $carId){
					echo $value.' deleted from '.$index.' on '.$key.'<br />';
					unset($DB[$index]->model[$key]);
					break;
				}
			}
			$DB[$index]->model = array_values($DB[$index]->model);
		}
		echo $carId.' added to '.$newIndex.'<br />';
		array_push($DB[$newIndex]->model, intval($carId));
		$jsonData = json_encode($DB);
		file_put_contents($filename, $jsonData);
	}
}

	// Define a destination
	$targetFolder = '/upload/img'; // Relative to the root
	
	//Elements
	$name= utf8($_POST['name']);
	$type = intval($_POST['type']);
	$mark = intval($_POST['mark']);
	$model = utf8($_POST['model']);
	$price = utf8($_POST['price']);
	$color = utf8($_POST['color']);
	$year = utf8($_POST['year']);
	$power = utf8($_POST['power']); 
	$vvv = utf8($_POST['vvv']);
	$fuel = utf8($_POST['fuel']);
	$kpp = utf8($_POST['kpp']);
	$drive = utf8($_POST['drive']);
	$door = utf8($_POST['door']); 
	$seat = utf8($_POST['seat']);
	$city = utf8($_POST['city']);
	$img = 0;
	$complect = utf8($_POST['complect']);
	

	$data = array(
		"name" => $name,
		"type" => $type,
		"mark" => $mark,  
		"model" => $model, 
		"price" => $price,
		"color" => $color,
		"year" => $year, 
		"power" => $power, 
		"vvv" => $vvv, 
		"fuel" => $fuel, 
		"kpp" => $kpp, 
		"drive" => $drive,
		"door" => $door,
		"seat" => $seat,
		"city" => $city,
		"img" => $img,
		"complect" => str_replace(">****",">",$complect));
	
	//to Ddata
	$inp = file_get_contents($pathTojs.'ddata.js');
	$carDB = json_decode($inp);    
	
	//some additions
		$imageList = $_POST['images'];
		$imageDelList = $_POST['imagesDel'];
		$data["img"] = array_unique($imageList);
		if(count($imageDelList) > 0){
			foreach($imageDelList as $value){
				deleteImage('../'.$value);
			}
		}
		//$data["img"] += $imageList;
		//$data["img"] = array_merge($data["img"], $imageList);
		$dbrandfile = file_get_contents($pathTojs.'dbrand.js');
		$dbrand = json_decode($dbrandfile );
		if(count($data["img"]) == 0)
			$data["img"] = 0;
		else
			for ($key=0; $key < count($data["img"]); $key++)
				if($carDB[$carId]->img[$key] != $data["img"][$key])  {
					$imgs = $data["img"][$key];
					$path = pathinfo($imgs);
					$tmp = $path['dirname'].'/'.rus2translit($data["model"]).' '.$dbrand[intval($data["mark"])]->mark.' '.mb_substr($path['filename'], rand(0, mb_strlen($path['filename'])-11), 10).$key.'.'.$path['extension'];
					$tmp = str_replace(" ", "-", $tmp);
					rename ('../../'.$imgs, '../../'.$tmp);
					$data["img"][$key]= $tmp;
				}
		if(strlen($data["vvv"])) preg_match('/[\d.,]+/', $data["vvv"], $data["vvv"]);
		if(strlen($data["power"])) preg_match('/[\d.,]+/', $data["power"], $data["power"]);
		
		if(strlen($data["price"])) {
			$data["price"] = str_replace(array(' ', ',', '.'), "", $data["price"]);
			preg_match('/\d+/', $data["price"], $tmp);
			if ($tmp) {
				$data["price"]= $tmp[0];
				$tmp2= strlen($data["price"]);
				if ($tmp2 > 5) $data["price"]= mb_substr($data["price"], 0, $tmp2-3);
			} else {
				preg_match('/[\S]+/', $data["price"], $data["price"]);
				if (mb_strtolower($data["price"][0]) != 'ask' ) 
					$data["price"] = mb_strtolower($data["price"][0]);
				else 
					$data["price"] = $data["price"][0];
			}
		}
		if(strlen($data["drive"])) {
			if (mb_strtolower(mb_substr($data["drive"], 0, 4)) == 'подк') $data["drive"]= mb_strtolower($data["drive"]);
			else {
				preg_match('/[^\s]+/', $data["drive"], $tmp);
				$data["drive"]= mb_strtolower($tmp[0]);
			}
		}
		if(strlen($data["door"])) preg_match('/\d+/', $data["door"], $data["door"]);
		if(strlen($data["seat"])) preg_match('/\d+/', $data["seat"], $data["seat"]);
		if(strlen($data["color"])) $data["color"]= mb_strtolower($data["color"]);
		
		if(strlen($data["fuel"])) {
			$tmp= mb_strtolower(mb_substr($data["fuel"], 0, 3));
			if ($tmp == 'бен') $data["fuel"]= 'бензин';
			else if($tmp == 'диз' || $tmp == 'д/т' || $tmp == 'д./' || $tmp == 'дт') $data["fuel"]= 'дизель';
		}
		
		$data["vvv"] = $data["vvv"][0];
		$data["power"] = $data["power"][0];
		$data["door"] = $data["door"][0];
		$data["seat"] = $data["seat"][0];
		$data["year"] = str_replace(array('/', '\\', '--'), "-", $data["year"]);
		$data["vvv"] = str_replace(array(',', ','), ".", $data["vvv"]);

	//End of additions       
	/*if($img == 0){
		echo 'Old Image<br />';
		$data["img"] = $carDB[$carId] -> img;
	}
	else{
		echo 'New Image<br />';
		foreach($carDB[$carId] -> img as $value){
			deleteImage('../'.$value);
		}
	}*/
	//to DType        
	updateInArray($pathTojs.'dtype.js',$carId,$carDB[$carId]->type,$type);
	//to DBrand
	updateInArray($pathTojs.'dbrand.js',$carId,$carDB[$carId]->mark,$mark);
	
	$carDB[$carId] = $data;
	$jsonData = json_encode($carDB);
	file_put_contents($pathTojs.'ddata.js', $jsonData);
	/*Delete images part
	imageList = $_POST['images'];
	imageDelList = $_POST['imagesDel'];
	if (file_exists('../img_temp.txt')) {
		$imgstr = utf8(file_get_contents('../img_temp.txt'));
		$imgstr = rtrim($imgstr);
		$img = explode( "\r\n" ,$imgstr);
		for(i = count(imageList)-1; i >= 0; --i){
			$id = array_search($imageList[i], $img);
			if($id !== false)
				unset($img[id]);
		}
		array_values($img);
		$imgstr = implode( "\r\n" ,$img);
		file_put_contents('../img_temp.txt',);
	} 

	$data["img"] = array_diff($carDB[$carId] -> img, imageDelList);
	foreach($imageDelList as $value){
		deleteImage('../'.$value);
	}
	$data["img"] += imageList;
	*/
	
	if (file_exists('../../rss.xml')) {
		$xml = simplexml_load_file('../../rss.xml');  
		
		//to Ddata
		$inp = file_get_contents($pathTojs.'ddata.js');
		$carDB = json_decode($inp); 
		
		foreach ($carDB as $key => $data) {
			
			$item = $xml->channel->addChild('item');
			$item->addChild('title', $data->name); //.'    | 127.0.0.1'
			$xmlstr = '<P style="CLEAR: both"><IMG style="WIDTH: auto; HEIGHT: 100px" border=0 hspace=0 alt="'.$data->name.'" title="'.$data->name.'" align="left" src="http://127.0.0.1/'.$data->img[0].'" width=auto height=100 />Модель: '.$data->model.', Год: '.$data->year.', Объём: '.$data->vvv.', Мощность: '.$data->power.', Топливо: '.$data->fuel.', Передача: '.$data->kpp.', Привод: '.$data->drive.', Двери: '.$data->door.', Сиденья: '.$data->seat.', Цвет: '.$data->color.', Город: '.$data->city.'</P>';
			
			//$datahere=$item->addChild('description');
			//$datahere->addCData($xmlstr);
			$item->addChild('description', $xmlstr);
			$item->addChild("link", "http://127.0.0.1?showobjvar=$key&shownamevar=".rus2translit($data->name));
			$item->addChild('pubDate', date(DATE_RFC2822));
		}
		
		$xml->saveXML('../../rss.xml');
	}
	
?>