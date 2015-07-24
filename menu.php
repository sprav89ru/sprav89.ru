<div class="menu">
<?php
//echo ' $path '.$path;
//echo '.$path.';

//echo HOST.' '.USER.' '.PASSWORD.' '.DB_SPRAV89;

echo '<ul class="menu-ul">';
	
	$mysqli = mysqli_connect(HOST,USER,PASSWORD,DB_SPRAV89);

	if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	mysqli_query($mysqli,"SET NAMES utf8");
	
	$q = 'SELECT * FROM menu ORDER BY id';
	$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
	//$row = mysqli_fetch_array($res);
	
	while ($row = mysqli_fetch_assoc($res)) {
//	echo ' id '.$row['id'].' name '.$row['name'].' text '.$row['text'].' option '.$row['option'].'<br />';	
	if (trim($row['option']) != 'do_not_show')	{ // если в option указано do_not_show - не выводить
		if (trim($row['alias']) != '') {
			$link=''.PATH.'/'.$row['alias'];
		}
		elseif (trim($row['option']) != '') {
			$link=''.PATH.'/?option='.$row['option'];
		}
		else {
			$link=''.PATH;
		}
		echo '<li class="menu-li"><a class="menu-a" href="'.$link.'">'.$row['name'].'</a></li>';
	}
	}
?>			
<!--		<li class="menu-li"><a class="menu-a" href="">Фотогалерея</a></li>
			<li class="menu-li"><a class="menu-a" href="">Цены</a></li>
			<li class="menu-li"><a class="menu-a" href="">Отзывы</a></li>
			<li class="menu-li"><a class="menu-a" href="">Контакты</a></li>-->
		</ul>
</div>