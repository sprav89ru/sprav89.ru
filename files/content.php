<style>
#p1 = {font: bold; font-family: serif; margin: 10px 2px 12px 4px;}
#p2 = {font: bold; font-family: sans-serif; margin: 10px 2px 15px 4px;}
#p3 = {font: bold; font-family: arial; margin: 10px 2px 15px 4px;}
</style>

<p>content.php</p>
<p><?php echo 'HOST = '.HOST.' PATH = '.PATH  ;?></p>


<?php
/*
if (isset($_GET['file'])) { // определен $_GET['file']
	if ($_GET['file'] == '') { $_GET['file'] = 'content.php';} // определен, но не задан - $_GET['file'] = 'content.php'
}
else {	$_GET['file'] = 'content.php';	} // неопределен — $_GET['file'] = 'content.php'

if (isset($_POST['alias_for_content_xml'])) { //определен $_POST['alias_for_content_xml'], по которому ищем данные
	if ($_POST['alias_for_content_xml'] == '') { $_POST['alias_for_content_xml'] = 'arts_for_action';} // определен, но не задан -$_POST['alias_for_content_xml'] = 'arts_for_action';
}
else { $_POST['alias_for_content_xml'] = 'arts_for_action';	// неопределен $_POST['alias_for_content_xml'] = 'arts_for_action';	
}

$alias_for_content_xml = $_POST['alias_for_content_xml'];

$p = common ($alias_for_content_xml);
*/
//function common ($alias_for_content_xml) {
	$xml = simplexml_load_file ('content.xml');
		foreach($xml as $param) { 
			echo '<p id="p1"> name = '.$param['name'].'</p>';
			echo '<p id="p2"> alias = '.$param['alias'].'</p>';
			echo '<p id="p3">'.print_r($param).'</p>';
			//echo print_r($param).'<br/>';
		//	if ($param['alias'] == $alias_for_content_xml) {
				//$array = (array) $param;
				//return $param; 	
				// echo ' name = '.$param['name'].' ; alias = '.$param['alias'].' number = '.$param['number'];
				// echo '<br/>';
				//break; 
		//}
	}

//}
?>