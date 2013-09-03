<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 25.08.13
 * Time: 5:08
 * To change this template use File | Settings | File Templates.
 */

//Файл test.xml содержит XML-документ с корневым элементом
//и, по крайней мере, элемент /[root]/title.

if (file_exists('test.xml')) {
    $xml = simplexml_load_file('test.xml');

    print_r($xml);
} else {
    exit('Не удалось открыть файл test.xml.');
}
?>