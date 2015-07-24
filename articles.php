<style>
#img_ogl {width: 140px;}
</style>
<?php

	if (defined('PATH')) { }
    else {require 'login.php';}
	
	if (isset($_GET['art']))
		{
		$art=$_GET['art'];
		}
	else {
		$art = '';
		}
	//Подключение 
	$mysqli = mysqli_connect(HOST,USER,PASSWORD,DB_SPRAV89);
	
	if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	mysqli_query($mysqli,"SET NAMES utf8");
	//
	
	if (trim($art) == '') { //выводим все
	
		$q = 'SELECT * FROM `articles` ORDER BY datetime DESC';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		//$row = mysqli_fetch_array($res);
		
		while ($row = mysqli_fetch_assoc($res)) {
		//сформируем $link
		
		$link_art = PATH.'?art='.$row['alias'];
		$link_img_ogl = PATH.'/'.$row['catalog'].'/'.$row['img_ogl'];
		
	/*	echo 'PATH		= '.PATH.'<br/>';
		echo '$link_art = '.$link_art.'<br/>';
		echo '$link_img = '.$link_img.'<br/>';*/
		
		echo '<div class="ogl">';
		echo '<a href="'.$link_art.'"><img src="'.$link_img_ogl.'" class="img_ogl" id="img_ogl" alt="'.$row['title'].'" /></a>';
		echo '<a href="'.$link_art.'"><p class="ptitle">'.$row['title'].'</p></a>';
		echo '<p class="psubtitle">'.$row['subtitle'].'</p>'; 
		echo '<p class="pdescription">'.$row['description'].'</p>';
		echo '<div class="ogl_sub">';
		echo '<a href="'.$link_art.'"><span class="dalee">&#62;&#62;&#62;&#62;&#62;</span></a>';
		echo '</div>';
		echo '</div>';
		}
		}
	else {//выводим статью
	
		$q = 'SELECT * FROM `articles` WHERE alias="'.$art.'"';
		$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
		$row = mysqli_fetch_array($res);
				
		$_POST['catalog'] = $row['catalog'];
		//echo $row['catalog'].'/'.$row['html_file'];
		include $row['catalog'].'/'.$row['html_file'];
		}
?>
