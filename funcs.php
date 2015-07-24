<?php
function cut($string, $length){ // готовая отсюда : http://tradebenefit.ru/obrezka-teksta-php-obrezat-stroku
$string = mb_substr($string, 0, $length,'UTF-8'); // обрезаем и работаем со всеми кодировками и указываем исходную кодировку
$position = mb_strrpos($string, ' ', 'UTF-8'); // определение позиции последнего пробела. Именно по нему и разделяем слова
$string = mb_substr($string, 0, $position, 'UTF-8'); // Обрезаем переменную по позиции
return $string;
}

function get_meta() { 	//формируем title $title для страницы

	if (defined('HOST')) {}
    else {require 'login.php';}
	$mysqli = mysqli_connect(HOST,USER,PASSWORD,DB_SPRAV89);
	if ($mysqli->connect_error) {die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
	mysqli_query($mysqli,"SET NAMES utf8");

	if (isset($_GET['news'])) { //news, но его вообще не определяем
		$meta['title'] = 'Новый Уренгой. Телефонный справочник города. Новости';
		$meta['description'] = 'Новости сайта "Новый Уренгой. Телефонный справочник города"';
		$meta['og_url'] = '';
		$meta['og_title'] = '';
		$meta['og_description'] = '';
		$meta['og_image'] = '';
	}  
	elseif (isset($_GET['new'])) { //new но не определен
		$q = 'SELECT * FROM news WHERE num = '.$_GET['new'];
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		$meta['title'] = datetime_string($row['datetime'],'').' '.$row['title'];
		$meta['description'] = datetime_string($row['datetime'],'').' '.$row['title'];
		$meta['og_url'] = PATH.'/?new='.$_GET['new'];
		$meta['og_title'] = $meta['title'];
		$meta['og_description'] = $meta['description'];
		$meta['og_image'] = PATH.'/img/new300.jpg';

	}	
	elseif (isset($_GET['art'])) { 
		$meta['title'] = 'Новый Уренгой. Телефонный справочник города. Статьи';
		$meta['description'] = 'Статьи на сайте "Новый Уренгой. Телефонный справочник города"';
		$meta['og_url'] = PATH.'/?art=';
		$meta['og_title'] = 'Новый Уренгой. Телефонный справочник города. Статьи';
		$meta['og_description'] = 'Статьи на сайте "Новый Уренгой. Телефонный справочник города"';
		$meta['og_image'] = '';

		if ($_GET['art'] !='') {
		$q = 'SELECT * FROM articles WHERE alias = "'.$_GET['art'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		$meta['title'] = $row['title'].'. '.$row['subtitle'];
		$meta['description'] = $row['description'];
		$meta['og_url'] = PATH.'/?art='.$_GET['art'];
		$meta['og_title'] = $meta['title'];
		$meta['og_description'] = $meta['description'];
		$meta['og_image'] = PATH.'/'.$row['catalog'].'/'.$row['img_ogl'];
		}
	}
	elseif (isset($_GET['org'])) {
		$meta['title'] = 'Новый Уренгой. Телефонный справочник города. Справочник';  //news, но его вообще не определяем
		$meta['description'] = 'Город Новый Уренгой (ЯНАО, Россия): Телефонный справочник города, Карта города, Газета Топ-новости, sprav89.ru';
		if ($_GET['org'] !='') {
		$q = 'SELECT * FROM aliases WHERE alias_ru = "'.$_GET['org'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		$q1 = 'SELECT naimenovanie_na_pechat FROM '.$row['table_name'].' WHERE id = "'.$row['id'].'"';
		$res1 = mysqli_query($mysqli, $q1) or die (mysqli_error($mysqli));
		$row1 = mysqli_fetch_array($res1);
		$meta['title'] = $row1['naimenovanie_na_pechat'];
		$meta['description'] = 'Телефонный справочник города Новый Уренгой: '.$row1['naimenovanie_na_pechat'];
		$meta['og_title'] = $meta['title'];
		$meta['og_description'] = $meta['description'];
		//$meta['og_image'] = PATH.'/'.$row['catalog'].'/'.$row['img_ogl'];
		}	
	}
	elseif (isset($_GET['search'])) {
		$meta['title'] = 'Поиск по сайту: '.$_GET['search'];  //news, но его вообще не определяем
		$meta['description'] = 'Результаты поиска в Телефонном справочнике города Новый Уренгой по строке: '.$_GET['search'];
		$meta['og_url'] = '';
		$meta['og_title'] = '';
		$meta['og_description'] = '';
		$meta['og_image'] = '';
		}
	else {
		$meta['title'] = 'Новый Уренгой. Телефонный справочник города';
		$meta['description'] = 'Город Новый Уренгой (ЯНАО, Россия): Телефонный справочник города, Карта города, Газета "Топ-новости, sprav89.ru';
		$meta['og_url'] = '';
		$meta['og_title'] = '';
		$meta['og_description'] = '';
		$meta['og_image'] = '';
	}	

	return $meta;
}

function arts_array_by_content_alias($alias) { //выводит массив статей, отобранный по alias, переданному в параметре

	if (defined('HOST')) {}
    else {require 'login.php';}
	
	$mysqli = mysqli_connect(HOST,USER,PASSWORD,DB_SPRAV89);
	
	if ($mysqli->connect_error) {die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);}
	mysqli_query($mysqli,"SET NAMES utf8");

	$arts = array(); //''alias_en','title',catalog','category','description','html_code','html_file','html_source','img_ogl','num','subtitle','datetime',

	$xml = simplexml_load_file ('content.xml');
		foreach($xml as $param)
		{ 
			if ($param['alias'] == $alias)
			{
				foreach($param -> children() as $key => $value)
				{ 
				$q = 'SELECT * FROM articles WHERE alias = "'.$value.'"';
				$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
				$row = mysqli_fetch_array($res);
				array_push($arts,$row);//добавляем строку запроса $row к массиву $arts
				}
			break; 
			}
		}	
	return $arts;
}

function trimmed($line,$symb) {

	$strpos  = strpos ($line, $symb);
	$trim_line = substr($line,0,$strpos);
	return $trim_line;

}

function file_search($p) { // проверка наличия файла возвращает строку-путь или false 

	//$p['filename'] = '';

	switch ($p['context']) {
		case 'new':
			$name = '';
			for ($i=0; $i<(6-strlen($p['num'])); $i++) {$name = $name.'0';}
			$name = $name.$p['num'];		
			//$filename = PATH.'/news/'.$name.'/'.$name.'.html';
			$filename = 'news/'.$name.'/'.$name.'.html';
			 if (file_exists($filename)) {$p['filename'] = $filename;}
			 else {$p['filename'] = 0;}	
			break;
		case 'art':
			echo $p['context'].' / '.$p['alias'].' / '.$p['catalog'];
			$name = '';
			//отложено, это я хотел, чтобы в каталоге статьи был index.html
			//оставил alias
	
		default: $p['filename'] = 0; 		 
	}
	return $p;
	// echo 'контекст '.$p['context'];
}

function datetime_string($datetime, $time) {
	// $datetime - дата время,  $time = 'yes / <любое значение > ' выводить / не выводить время
	$y = substr($datetime,0,4);
	$m=	month(substr($datetime,5,2));
	$d = substr($datetime,8,2);
	if ($d[0]=='0') {$d = substr($datetime,9,1);}

	if ($time == 'yes') {	$stringtime = substr($datetime,11,8); 	}
	else { $stringtime = ''; }

	$data = $d.' '.$m.' '.$y.' г. '.$stringtime; 
	return $data;
}

function news_navy($p)
{

	if ($p['items_for_page'] == 'news_for_main') {

		$p['prev_link'] = '';
		$p['prev_text'] = '';	
		$p['next_link'] = PATH.'/?news=';
		$p['next_text'] = 'Все новости >>>';
		}
	
	else {

		$p['prev_numb'] = $p['current_of_item'] + $p['number_of_items'];
		$p['prev_link'] = PATH.'/?news='.$p['prev_numb'];
		$p['prev_text'] = ' <<< '.$p['prev_numb'];

		if ($p['prev_numb'] >= $p['total']) {
			$p['prev_numb'] = $p['total'];
			$p['prev_link'] = '';
			$p['prev_text'] = '';
		}
		
		$p['next_numb'] = $p['current_of_item'] - $p['number_of_items'];
		$p['next_link'] = PATH.'/?news='.$p['next_numb'];
		$p['next_text'] = ''.$p['next_numb'].' >>> ';

		if ($p['next_numb'] == 0) {
		$p['next_numb'] = '';
		$p['next_link'] = PATH.'/?news=';
		$p['next_text'] = ''.$p['next_numb'].' >>> ';
		}
		if ($p['next_numb'] < 0) {
			$p['next_numb'] = '';
			$p['next_link'] = '';
			$p['next_text'] = '';
		}
		
		}
	
	return $p;
}


function news_query($p)
{  
	$p['items_for_page']  = $_POST['items_for_page'];
	//$p['current_of_item'] = $_GET['news']; // текущий элемент
	$p['fields'] = 'datetime, html_code, num';
	$p['table'] = 'news';
	$p['ORDER_BY_field'] = 'num';
	

	// Всего записей:
	$q = 'SELECT count(num) FROM news';
	$res = mysqli_query($p['mysqli'],$q) or die (mysqli_error($p['mysqli']));
	$row = mysqli_fetch_row($res);

	$p['total'] = $row[0]; // всего записей в таблице 

	//echo 'items_for_page = '.$_p['items_for_page'];

	if ($p['items_for_page'] == 'news_for_news')
		{$p['number_of_items'] = 30;}
	else {$p['number_of_items'] = 10;}	//для main

	if ( ($p['current_of_item'] + $p['number_of_items']) <= $p['total'])
	{ //если текущая + кол-во еще не превышают тотал
		$first_item = $p['current_of_item']; //первая - текущая
		$number_items =  $p['number_of_items']; // количеств - как положено
	}
	else	{ 	// если превышают
		if ($p['current_of_item'] > $p['total'])	{//если превышают за счет текущей, 
			$first_item = $p['total']; // текущая равна тоталу
			$number_items = 0; // добавляемых - 0
			} 
		else
			{ // если перевышают за счет добавляемых
			$first_item = $p['current_of_item']; // первая как обычно
			$number_items = $p['total'] - $p['current_of_item']; //уменьшаем добавляемые
			}
	}

	$str = ' DESC LIMIT '.$first_item.','.$number_items;

	$p['q'] = 'SELECT '.$p['fields'].' FROM '.$p['table'].' ORDER BY '.$p['ORDER_BY_field'].$str;

	return $p;

} 

function  month($month_str){
	
	switch($month_str){ 
	case '01': $m = 'января'; break;
	case '02': $m = 'февраля'; break;
	case '03': $m = 'марта'; break;
	case '04': $m = 'апреля'; break;
	case '05': $m = 'мая'; break;
	case '06': $m = 'июня'; break;
	case '07': $m = 'июля'; break;
	case '08': $m = 'августа'; break;
	case '09': $m = 'сентября'; break;
	case '10': $m = 'октября'; break;
	case '11': $m = 'ноября'; break;
	case '12': $m = 'декабря'; break;
	default: $m = $month_str;break;
	}	
	
	return $m;
	} //function  tel($tel){
		
function navy_by_alias($mysqli, $alias)  // навигационная строка с алиасами		
{
	posts_by_alias($mysqli, $alias);
	
	$navy_link0=''.PATH.'?org=';
	$navy_name0='Справочник';

//	echo ''.$_POST['sprav_id'];
//	echo ''.$_POST['sprav_idr'];
//		
//	echo ''.$_POST['roditel2_id'];
//	echo ''.$_POST['roditel2_idr'];
//	echo ''.$_POST['roditel2_naimenovanie_na_pechat'];
//	echo ''.$_POST['roditel2_alias_ru'];
//	
//	echo ''.$_POST['roditel1_id'];
//	echo ''.$_POST['roditel1_naimenovanie_na_pechat'];
//	echo ''.$_POST['roditel1_alias_ru'];

	$link = '<div class="navy"><a class="navy_a" href="'.$navy_link0.'">'.$navy_name0.'</a>';
	
	if ($_POST['roditel1_naimenovanie_na_pechat'] <> '') 
	{
		$navy_name1 = $_POST['roditel1_naimenovanie_na_pechat'];
		$navy_link1 = ''.PATH.'?org='.$_POST['roditel1_alias_ru'];
		$link = $link.'&nbsp;&mdash;&nbsp;<a class="navy_a" href="'.$navy_link1.'">'.$navy_name1.'</a>';
	}
	if ($_POST['roditel2_naimenovanie_na_pechat'] <> '') 
	{
		$navy_name2 = $_POST['roditel2_naimenovanie_na_pechat'];
		$navy_link2 = ''.PATH.'?org='.$_POST['roditel2_alias_ru'];
		$link = $link.'&nbsp;&mdash;&nbsp;<a class="navy_a" href="'.$navy_link2.'">'.$navy_name2.'</a>';
	}
	
	$link = $link.'</div>';
	
	return $link;

}	

function posts_by_alias($mysqli, $alias)  // получить родителя по алиасу
{
	$q = 'SELECT * FROM `aliases` WHERE alias_ru="'.$alias.'"';//ищем в aliases
	$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
	$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
	
	//echo ' id = '.$row['id'].' '.$row['alias_ru'].' '.$row['table_name'].'<br> '; 
	if ($row['table_name'] == 'sprav')
		{//ищем в sprav
		$q = 'SELECT * FROM sprav WHERE id="'.$row['id'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['sprav_id'] = $row['id'];
		$_POST['sprav_idr'] = $row['idr'];
		
		$q = 'SELECT * FROM roditel2 WHERE id="'.$_POST['sprav_idr'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['roditel2_id'] = $row['id'];
		$_POST['roditel2_idr'] = $row['idr'];
		$_POST['roditel2_naimenovanie_na_pechat'] = $row['naimenovanie_na_pechat'];
		$_POST['roditel2_alias_ru'] = $row['alias_ru'];
		
		$q = 'SELECT * FROM roditel1 WHERE id="'.$_POST['roditel2_idr'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['roditel1_id'] = $row['id'];
		$_POST['roditel1_naimenovanie_na_pechat'] = $row['naimenovanie_na_pechat'];
		$_POST['roditel1_alias_ru'] = $row['alias_ru'];
		}
	elseif ($row['table_name'] == 'roditel2') 
		{
		$_POST['sprav_id'] = '';
		$_POST['sprav_idr'] = '';

		$q = 'SELECT * FROM roditel2 WHERE id="'.$row['id'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['roditel2_id'] = $row['id'];
		$_POST['roditel2_idr'] = $row['idr'];
		$_POST['roditel2_naimenovanie_na_pechat'] = $row['naimenovanie_na_pechat'];
		$_POST['roditel2_alias_ru'] = $row['alias_ru'];
		
		$q = 'SELECT * FROM roditel1 WHERE id="'.$_POST['roditel2_idr'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['roditel1_id'] = $row['id'];
		$_POST['roditel1_naimenovanie_na_pechat'] = $row['naimenovanie_na_pechat'];
		$_POST['roditel1_alias_ru'] = $row['alias_ru'];
		}
	elseif ($row['table_name'] == 'roditel1') 
		{
		$_POST['sprav_id'] = '';
		$_POST['sprav_idr'] = '';
		$_POST['roditel2_id'] = '';
		$_POST['roditel2_idr'] = '';
		$_POST['roditel2_naimenovanie_na_pechat'] = '';
		$_POST['roditel2_alias_ru'] = '';
		
		$q = 'SELECT * FROM roditel1 WHERE id="'.$row['id'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$_POST['roditel1_id'] = $row['id'];
		$_POST['roditel1_naimenovanie_na_pechat'] = $row['naimenovanie_na_pechat'];
		$_POST['roditel1_alias_ru'] = $row['alias_ru'];
		}
	else
		{
		$_POST['sprav_id'] = '';
		$_POST['sprav_idr'] = '';
		$_POST['roditel2_id'] = '';
		$_POST['roditel2_idr'] = '';
		$_POST['roditel2_naimenovanie_na_pechat'] = '';
		$_POST['roditel2_alias_ru'] = '';
		$_POST['roditel1_id'] = '';
		$_POST['roditel1_naimenovanie_na_pechat'] = '';
		$_POST['roditel1_alias_ru'] = '';
		}	
}

function get_alias_of_rod($mysqli, $alias)  // получить родителя по алиасу
{
	$q = 'SELECT * FROM `aliases` WHERE alias_ru="'.$alias.'"';//ищем в aliases
	$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
	$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
	
	//echo ' id = '.$row['id'].' '.$row['alias_ru'].' '.$row['table_name'].'<br> '; 
	if ($row['table_name'] == 'sprav')
		{//ищем в sprav
		$q = 'SELECT * FROM sprav WHERE id="'.$row['id'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		
		$q = 'SELECT * FROM roditel2 WHERE id="'.$row['idr'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$alias_rod = $row['alias_ru'];
		}
	elseif ($row['table_name'] == 'roditel2') {
		$q = 'SELECT * FROM roditel2 WHERE id="'.$row['id'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		
		$q = 'SELECT * FROM roditel1 WHERE id="'.$row['idr'].'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);//выберем одну строку (она и должна быть одна)
		$alias_rod = $row['alias_ru']; //возвращаем алиас родителя из roditel1
		}
	else { // table_name = 
		$alias_rod = 'Cправочник'; //головной, нет родителя
		}
		
		return $alias_rod; 
}	
		
function navy($mysqli, $idr, $zn)  // навигационная строка 
{
	$navy_link1=$_POST['source'];
	$navy_name1='Справочник';
		
	switch($zn)
	{
	case 1: //echo "функция уровня 1";
	
		$link = '<div class="navy"><a class="navy_a" href="'.$navy_link1.'">'.$navy_name1.'</a></div>';
		
		break;
		
	case 2:	//echo "функция уровня 2";
		//навигационная строка;
		$q = 'SELECT * FROM `roditel1` WHERE id="'.$idr.'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		$navy_name2=$row['naimenovanie_na_pechat'];
		$navy_link2=''.$navy_link1.'zn=2&idr='.$idr;
		
		$link='<div class="navy"><a class="navy_a" href='.$navy_link1.'>'.$navy_name1.'</a>&nbsp;&mdash;&nbsp;';
		$link=$link.'<a class="navy_a" href='.$navy_link2.'>'.$navy_name2.'</a></div>';
		
		break;
		
	case 3:	//echo "функция уровня 3 "; Элементы группы idr
		
		$q = 'SELECT * FROM `roditel2` WHERE id="'.$idr.'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		//находим на этом же уровне:
		$navy_name3=$row['naimenovanie_na_pechat'];
		$navy_link3=''.$navy_link1.'zn='.$zn.'&idr='.$idr;
		
		//находим на вышестоящем  уровне:
		$q = 'SELECT * FROM `roditel1` WHERE id="'.$row['idr'].'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		$navy_name2=$row['naimenovanie_na_pechat'];
		$navy_link2=''.$navy_link1.'zn=2&idr='.$row['id'];
		
		$link='<div class="navy"><a class="navy_a" href='.$navy_link1.'>'.$navy_name1.'</a>&nbsp;&mdash;&nbsp;';
		$link=$link.'<a class="navy_a" href='.$navy_link2.'>'.$navy_name2.'</a>&nbsp;&mdash;&nbsp;';
		$link=$link.'<a class="navy_a" href='.$navy_link3.'>'.$navy_name3.'</a></div>';
		
		break;
		
	case 4:
		//echo "функция уровня 4 ";
		
		$q = 'SELECT * FROM `sprav` WHERE id="'.$idr.'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		$q = 'SELECT * FROM `roditel2` WHERE id="'.$row['idr'].'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		//находим на этом же уровне:
		$navy_name3=$row['naimenovanie_na_pechat'];
		$navy_link3=''.$navy_link1.'zn=3&idr='.$row['id'];
		
		//находим на вышестоящем  уровне:
		$q = 'SELECT * FROM `roditel1` WHERE id="'.$row['idr'].'" LIMIT 1';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
		
		$navy_name2=$row['naimenovanie_na_pechat'];
		$navy_link2=''.$navy_link1.'zn=2&idr='.$row['id'];
		
		
		$link='<div class="navy"><a class="navy_a" href='.$navy_link1.'>'.$navy_name1.'</a>&nbsp;&mdash;&nbsp;';
		$link=$link.'<a class="navy_a" href='.$navy_link2.'>'.$navy_name2.'</a>&nbsp;&mdash;&nbsp;';
		$link=$link.'<a class="navy_a" href='.$navy_link3.'>'.$navy_name3.'</a></div>';
		
		break;
		
	default: // 
		$link = '<div class="navy"><a class="navy_a" href="'.$navy_link1.'">'.$navy_name1.'</a></div>';
	break;
	
	}
	//echo $link;
	return $link;
	
}
?>