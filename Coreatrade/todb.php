<?php
  
function utf8($value)
{
    return mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);
}
  
$type = $_POST['type'];
$mark = $_POST['mark'];
$model = utf8($_POST['model']);
$price = utf8($_POST['price']);
$color = utf8($_POST['color']);
$year = utf8($_POST['year']);
$vvv = utf8($_POST['vvv']);
$fuel = utf8($_POST['fuel']);
$kpp = utf8($_POST['kpp']);
$drive = utf8($_POST['drive']);
$complect = utf8($_POST['complect']);
$name= utf8($_POST['name']);

$data = array(
"name" => $name,
"type" => $type,
"mark" => $mark,
"model" => $model,
"price" => $price,
"year" => $year,
"image" => array("imagePath_1", "ImagePath_2", "ImagePath_3"),
"complect" => $complect);
//$value = mb_check_encoding($value, 'UTF-8') ? $value : utf8_encode($value);

$inp = file_get_contents('js/ddata.js');
$tempArray = json_decode($inp);
$tempArray[]= $data;
$jsonData = json_encode($tempArray);
file_put_contents('js/ddata.js', $jsonData);

?>


<!--<html>
<head><title>123></title>
<script type="javascript"
</head>
<body></body>
</html>-->


<!--<html>
    <head>
        <title>����� ���������� �����������</title>
    
        <style type="text/css">
            .table 
            {
                display:block;
                font-weight:bold;
            	float:left;
            }
            .left 
            {
            	width:15%;
            	font-size:1.2em;
            }
            .middle
            {
            	width: 10%;
            }
            .right 
            {
            	width: 60%;
            }
            .newrow 
            {
            	clear:both;
            }
            h1 
            {
            	font-size:2em;
            }
            h2 
            {
            	font-size:1.7em;
            }
        </style>
    
    </head>
    <body style="padding: 40px">
        <h1>����� ���������� �����������</h1>
         <div class="newrow"></div>
            
            <h2>������������</h2>
            <div style="padding-bottom: 10px">���������� ������� ������ ��������� � ����� �������</div>
            <textarea name="complect" cols="60" rows="33">
������:
ABS
Airbag �/��������
Airbag �/���������
�������� ���������
���
������������
������-��������
�����-��������
������� �������
����������� �����
Airbag �������
Airbag �������
ESP	Handsfree
������ �����
������ ������� ����
��������� ���
���������� ����
������� ������
���������� ���
����������
����������� ��������
��������������� ����
����. ������ ����. �������
�����. ���. ����. �� ������
��������������
��������������
���������: � MP3
�����: ����
������������: ���
����������� ����: � 2 ��.</textarea>
            <div style="padding:10px"></div>
            <input type="submit" value="��������� ������" />
            &nbsp;&nbsp;&nbsp;&nbsp;<input type="reset" value="�������� �����" />	
        </form>
    </body>
</html>-->