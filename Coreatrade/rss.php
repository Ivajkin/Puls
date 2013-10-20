<?php 
function utf8($value)
{
	return stripslashes(mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value));
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


if ( !preg_match("/MSIE/i", $_SERVER["HTTP_USER_AGENT"]) ) {
	$enc = 'UTF-8';
	header('Content-type: text/html; charset='.$enc);
	mb_internal_encoding($enc);
} else {
	$enc = 'windows-1251';
	header('Content-type: text/html; charset='.$enc);
	mb_internal_encoding($enc);
}

/*if (file_exists('rss.xml')) {
	$xml = simplexml_load_file('rss.xml');  
	
	//to Ddata
	$inp = file_get_contents('js/ddata.js');
	$carDB = json_decode($inp); 
	
	foreach ($carDB as $key => $data) {
	
		$item = $xml->channel->addChild('item');
		$item->addChild('title', $data->name); //.'    | Coreatrade.com'
		$xmlstr = '<P style="CLEAR: both"><IMG style="WIDTH: auto; HEIGHT: 100px" border=0 hspace=0 alt="'.$data->name.'" title="'.$data->name.'" align="left" src="http://coreatrade.com/'.$data->img[0].'" width=auto height=100 />Модель: '.$data->model.', Год: '.$data->year.', Объём: '.$data->vvv.', Мощность: '.$data->power.', Топливо: '.$data->fuel.', Передача: '.$data->kpp.', Привод: '.$data->drive.', Двери: '.$data->door.', Сиденья: '.$data->seat.', Цвет: '.$data->color.', Город: '.$data->city.'</P>';
		
		//$datahere=$item->addChild('description');
		//$datahere->addCData($xmlstr);
		$item->addChild('description', $xmlstr);
		$tmp= rus2translit($data->name);
		$item->addChild("link", "http://coreatrade.com?showobjvar=$key&shownamevar=$tmp");
		$item->addChild('pubDate', date(DATE_RFC2822));
	}
	
	$xml->saveXML('rss.xml');
}*/
//to Ddata
$inp = file_get_contents('js/ddata.js');
$carDB = json_decode($inp); 
$inp2 = file_get_contents('js/dbrand.js');
$markDB = json_decode($inp2); 


//var_dump($carDB);

foreach ($carDB as $car) {
	//var_dump($car);
	for ($key=0; $key < count($car->img); $key++) {
		//var_dump( $imgs);    
		$imgs = $car->img[$key];
		$path = pathinfo($imgs);
		$tmp = $path['dirname'].'/'.rus2translit($car->model).' '.$markDB[$car->mark]->mark.' '.substr($path['filename'], rand(0, strlen($path['filename'])-11), 10).$key.'.'.$path['extension'];
		$tmp = str_replace(" ", "-", $tmp);
		//rename ($imgs, $tmp);
		$car->img[$key]= $tmp;
		//var_dump( $imgs);    
	}
	if(strlen($car->vvv)) preg_match('/[\d.,]+/', $car->vvv, $car->vvv);
	if(strlen($car->power)) preg_match('/[\d.,]+/', $car->power, $car->power);
	
	if(strlen($car->price)) {
		$car->price = str_replace(array(' ', ',', '.'), "", $car->price);
		preg_match('/\d+/', $car->price, $tmp);
		if ($tmp) {
			$car->price= $tmp[0];
			$tmp2= strlen($car->price);
			if ($tmp2 > 5) $car->price= substr($car->price, 0, $tmp2-3);
		} else {
			preg_match('/[\S]+/', $car->price, $car->price);
			if (mb_strtolower($car->price[0]) != 'ask' ) 
				$car->price = mb_strtolower($car->price[0]);
			else 
				$car->price = $car->price[0];
		}
	}
	if(strlen($car->drive)) {
		if (mb_strtolower(substr($car->drive, 0, 6)) == 'подк') $car->drive= mb_strtolower($car->drive);
		else {
			preg_match('/[^\s]+/', $car->drive, $tmp);
			$car->drive= mb_strtolower($tmp[0]);
		}
	}
	if(strlen($car->door)) preg_match('/\d+/', $car->door, $car->door);
	if(strlen($car->seat)) preg_match('/\d+/', $car->seat, $car->seat);
	if(strlen($car->color)) $car->color= mb_strtolower($car->color);
	
	if(strlen($car->fuel)) {
		$tmp= mb_strtolower(substr($car->fuel, 0, 6));
		if ($tmp == 'бен') $car->fuel= 'бензин';
		else if($tmp == 'диз' || $tmp == 'д/т' || $tmp == 'д./' || $tmp == 'дт') $car->fuel= 'дизель';
	}
	
	$car->vvv = $car->vvv[0];
	$car->power = $car->power[0];
	$car->door = $car->door[0];
	$car->seat = $car->seat[0];
	//var_dump($car);*/
}
var_dump($carDB);
/*$jsonData = json_encode($carDB);
file_put_contents('js/ddata.js', $jsonData);*/

/*echo $path_parts['dirname'], "\n";
echo $path_parts['basename'], "\n";
echo $path_parts['extension'], "\n";
echo $path_parts['filename'], "\n"; // начиная с PHP 5.2.0*/

/*
name
type
mark
model
price
color
year
power
vvv
fuel
kpp
drive
door
seat
city
img
complect
*/

?>