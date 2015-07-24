<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="spravochnik.css" type="text/css"/> 

<!--<div class="content">-->
<?php
	if (defined('PATH')) {
    }
    else {
	require 'login.php';	
	}
	
	if (isset($_GET['search'])) {
		
		$search=trim($_GET['search']);
		
		if (strlen('"'.$_GET['search'].'"')>=10) {
			echo '<div class="navy">Поиск по строке: <i>'.$_GET['search'].'</i></div>';
			$search=$_GET['search'];
			f_search($search);
			} 
		else {
			echo '<div class="navy"><i>'.$search.'</i> — недостаточно символов для поиска!</div>';
			}
		 }
	else {
		 echo 'Строка поиска не определена!'.'<br />';
		 }
function f_search($search)

	{//function search($_GET['search'])

	$mysqli = mysqli_connect(HOST,USER,PASSWORD,DB_SPRAV89);
	
	if ($mysqli->connect_error) {
    die('Ошибка подключения (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
	}
	mysqli_query($mysqli,"SET NAMES utf8");


	$q = 'SELECT * FROM sprav WHERE naimenovanie_na_pechat LIKE "%'.$search.'%"';
	
	$res = mysqli_query($mysqli, $q) or die (mysqli_error($mysqli));
	while ($row = mysqli_fetch_array($res))
		{
			
		$q_rod = 'SELECT * FROM roditel2 WHERE id ="'.$row['idr'].'"';
		$res_rod = mysqli_query($mysqli, $q_rod) or die (mysqli_error($mysqli));
		$row_rod = mysqli_fetch_array($res_rod);
			
		$link=''.PATH.'?org='.$row['alias_ru'].'';
		echo '<div class="element">'; //отдельный контейнер по элементам, более узкий, все остальное выводим туда
	
		//выводим шапку элемента
		echo '<div class="naimenovanie_na_pechat"><a href='.$link.' class="naimenovanie_na_pechat_a">'.$row['naimenovanie_na_pechat'].'</a></div>';
		//Найдем группу - родителя
		
		if ($row['podzagolovok']<>"") 			echo '<div class="podzagolovok">'.$row['podzagolovok'].'</div>';
		if ($row['yuridicheskiy_status']<>"") 	echo '<div class="yuridicheskiy_status">'.$row['yuridicheskiy_status'].'</div>';
		if ($row['adres']<>"") 					echo '<div class="adres">'.$row['adres'].'</div>';
		if ($row['litso_dolzhnost']<>"") 		echo '<div class="litso_dolzhnost">'.$row['litso_dolzhnost'].'</div>';
		if ($row['litso_fio']<>"") 				echo '<div class="litso_fio">'.$row['litso_fio'].'</div>';
	
	//	
	
	$idsprav=$row['id'];
	$aliassprav=$row['alias_ru'];
	
	$q_tel ='SELECT * FROM sprav_tel WHERE idr ="'.$idsprav.'" ORDER BY nom_str';
	
	$res_tel = mysqli_query($mysqli, $q_tel) or die(mysqli_error($mysqli));
	
	echo '<table class="table_tel">';//123	
	
	while ($row = mysqli_fetch_assoc($res_tel))
	{ //цикл по телефонам
			//01_adres_telefony_v_odnoy_stroke = Адрес, телефоны в одной строке
			if ($row['format_vyvoda_strok']=='01_adres_telefony_v_odnoy_stroke') {
				$adres=$row['adres'];		
				$tel=tel($row['telefony']);		
				//адрес, теелфон
				echo '<tr>';
				echo '<td class="adres_table">'.$adres.'</td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//01_adres_dolzhnost_fio_telefony_v_raznykh_strokakh = Адрес, Должность, ФИО, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='01_adres_dolzhnost_fio_telefony_v_raznykh_strokakh') {
				$adres=$row['adres'];	
				$litso_dolzhnost = $row['litso_dolzhnost'];
				$litso_fio = $row['litso_fio'];
				$tel=tel($row['telefony']);		
				//адрес, теелфон
				echo '<tr>';
				echo '<td class="adres_table">'.$adres.'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_dolzhnost_table">'.$litso_dolzhnost.'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_fio">'.$litso_fio.'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//02_adres_telefony_v_raznykh_strokakh = Адрес, телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='02_adres_telefony_v_raznykh_strokakh') {
				$adres=$row['adres'];		
				$tel=tel($row['telefony']);		
				//адрес
				echo '<tr>';
				echo '<td class="adres_table">'.$adres.'</td><td></td>';
				echo '</tr>';
				//телефон
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//03_dolzhnost_telefony_v_odnoy_stroke = Должность, телефоны в одной строке
			elseif ($row['format_vyvoda_strok']=='03_dolzhnost_telefony_v_odnoy_stroke') {
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td class="litso_dolzhnost_table">'.$row['litso_dolzhnost'].'</td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//04_dolzhnost_telefony_v_raznykh_strokakh = Должность, телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='04_dolzhnost_telefony_v_raznykh_strokakh') {
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td class="litso_dolzhnost_table">'.$row['litso_dolzhnost'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//05_dolzhnost_fio_v_odnoy_stroke = Должность, ФИО в одной строке
			elseif ($row['format_vyvoda_strok']=='05_dolzhnost_fio_v_odnoy_stroke') {
				$litso_dolzhnost = $row['litso_dolzhnost'];
				$litso_fio = $row['litso_fio'];
				echo '<tr>';
				echo '<td class="litso_dolzhnost">'.$litso_dolzhnost.'<span class="litso_fio">'.$litso_fio.'</span></td><td></td>';
				echo '</tr>';
				}
			//06_dolzhnost_fio_telefony_v_raznykh_strokakh = Должность, ФИО, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='06_dolzhnost_fio_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="litso_dolzhnost">'.$row['litso_dolzhnost'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_fio">'.$row['litso_fio'].'</td><td></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//09_podrazdelenie_adres_v_odnoy_stroke = Подразделение, Адрес в одной строке
			elseif ($row['format_vyvoda_strok']=='09_podrazdelenie_adres_v_odnoy_stroke') {
				$podr=$row['podrazdelenie_filial'];		
				$adres=$row['adres'];		
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$podr.'</td><td class="adres_table">'.$adres.'</td>';
				echo '</tr>';
				}
			//10_podrazdelenie_adres_v_raznykh_strokakh = Подразделение, Адрес в разных строках
			elseif ($row['format_vyvoda_strok']=='10_podrazdelenie_adres_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$row['podrazdelenie_filial'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="adres_table">'.$row['adres'].'</td><td></td>';
				echo '</tr>';
				}
			//11_podrazdelenie_adres_telefony_v_raznykh_strokakh = Подразделение, Адрес, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='11_podrazdelenie_adres_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial_table">'.$row['podrazdelenie_filial'].'</td><td class="tel"></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="adres_table">'.$row['adres'].'</td><td class="tel"></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//12_podrazdelenie_dolzhnost_telefony_v_raznykh_strokakh = Подразделение, Должность, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='12_podrazdelenie_dolzhnost_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial_table">'.$row['podrazdelenie_filial'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_dolzhnost_table">'.$row['litso_dolzhnost'].'</td><td></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//13_podrazdelenie_dolzhnost_fio_v_raznykh_strokakh = Подразделение, Должность, ФИО в разных строках
			elseif ($row['format_vyvoda_strok']=='13_podrazdelenie_dolzhnost_fio_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial_table">'.$row['podrazdelenie_filial'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_dolzhnost_table">'.$row['litso_dolzhnost'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_fio">'.$row['litso_fio'].'</td><td></td>';
				echo '</tr>';
				}
			//14_podrazdelenie_dolzhnost_fio_telefony_v_raznykh_strokakh = Подразделение, Должность, ФИО, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='14_podrazdelenie_dolzhnost_fio_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$row['podrazdelenie_filial'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_dolzhnost">'.$row['litso_dolzhnost'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="litso_fio">'.$row['litso_fio'].'</td><td></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//15_podrazdelenie_telefony_v_odnoy_stroke = Подразделение, Телефоны в одной строке
			elseif ($row['format_vyvoda_strok']=='15_podrazdelenie_telefony_v_odnoy_stroke') {
				$tel=tel($row['telefony']);		
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$row['podrazdelenie_filial'].'</td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//16_podrazdelenie_telefony_v_raznykh_strokakh = Подразделение, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='16_podrazdelenie_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$row['podrazdelenie_filial'].'</td><td class="tel"></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//17_rezhim_raboty = Режим работы
			elseif ($row['format_vyvoda_strok']=='17_rezhim_raboty') {
				echo '<tr>';
				echo '<td class="rezhimrabotystroka">'.$row['rezhimrabotystroka'].'</td><td class="tel"></td>';
				echo '</tr>';
				}
			//18_rezhim_raboty_s_dop_inf_v_raznykh_str_ = Режим работы с доп.инф.в разных стр.
			elseif ($row['format_vyvoda_strok']=='18_rezhim_raboty_s_dop_inf_v_raznykh_str_') {
				echo '<tr>';
				echo '<td class="rezhimrabotystroka">'.$row['rezhimrabotystroka'].'</td><td></td>';
				echo '</tr>';
				echo '<tr>';
				echo '<td class="dop_informatsiya">'.$row['dop_informatsiya'].'</td><td></td>';
				echo '</tr>';
				}
			//19_sayt_ = Сайт
			elseif ($row['format_vyvoda_strok']=='19_sayt_') {
				$sayt=$row['elpochta_sayt'];
				echo '<tr>';
				echo '<td class="sayt_">Сайт:</td><td class="sayt"><a class="sayt_a" href="http://'.$sayt.'">'.$sayt.'</a></td>';
				echo '</tr>';
				}
			//20_tolko_adres = Только адрес
			elseif ($row['format_vyvoda_strok']=='20_tolko_adres') {
				$adres=$row['adres'];
				echo '<tr>';
				echo '<td class="adres_table">'.$row['adres'].'</td><td></td>';
				echo '</tr>';
				}
			//21_tolko_podrazdelenie = Только подразделение 
			elseif ($row['format_vyvoda_strok']=='21_tolko_podrazdelenie') {
				echo '<tr>';
				echo '<td class="podrazdelenie_filial">'.$row['podrazdelenie_filial'].'</td><td></td>';
				echo '</tr>';
				}
			//22_tolko_telefony = Только телефоны 
			elseif ($row['format_vyvoda_strok']=='22_tolko_telefony') {
				$tel=tel($row['telefony']);		
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//23_dop_informatsiya = Только телефоны 
			elseif ($row['format_vyvoda_strok']=='23_dop_informatsiya') {
				echo '<tr>';
				echo '<td class="dop_informatsiya">'.$row['dop_informatsiya'].'</td><td></td>';
				echo '</tr>';
				}
			//	24_fio_telefony_v_raznykh_strokakh = ФИО, Телефоны в разных строках
			elseif ($row['format_vyvoda_strok']=='24_fio_telefony_v_raznykh_strokakh') {
				echo '<tr>';
				echo '<td class="litso_fio">'.$row['litso_fio'].'</td><td></td>';
				echo '</tr>';
				$tel=tel($row['telefony']);
				echo '<tr>';
				echo '<td></td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
			//25_fio_telefony_v_odnoy_stroke = ФИО, Телефоны в одной строке
			elseif ($row['format_vyvoda_strok']=='25_fio_telefony_v_odnoy_stroke') {
				$tel=tel($row['telefony']);	
				echo '<tr>';
				echo '<td class="litso_fio">'.$row['litso_fio'].'</td><td class="tel">'.$tel.'</td>';
				echo '</tr>';
				}
				//25_elpochta = ЭлПочта
			elseif ($row['format_vyvoda_strok']=='25_elpochta') {
				$elpochta=$row['elpochta_sayt'];
				echo '<tr>';
				echo '<td class="elpochta_">Эл.почта:</td><td class="elpochta"><a class="elpochta_a" href="mailto:'.$elpochta.'">'.$elpochta.'</a></td>';
				echo '</tr>';
				}
			//26_aska = Аська
			elseif ($row['format_vyvoda_strok']=='26_aska') {
				$ICQ=$row['elpochta_sayt'];
				echo '<tr>';
				echo '<td class="icq_table_">ICQ</td><td class="tel">'.$ICQ.'</td>';
				echo '</tr>';
				}
			//27_skayp = Скайп
			elseif ($row['format_vyvoda_strok']=='27_skayp') {
				$Skype=$row['elpochta_sayt'];
				echo '<tr>';
				echo '<td class="skype_table_">Skype</td><td class="tel">'.$Skype.'</td>';
				echo '</tr>';
				}
			else {
				echo '<tr>';
				echo '<td>'.$row['nom_str'].'</td><td>'.$row['format_vyvoda_strok'].'</td>';
				echo '</tr>';
				}
			}//конец цикла по телефонам
		echo '</table>';//echo '<table class="table_tel">';
		
		$q ='SELECT * FROM sprav_dop WHERE idr ="'.$idsprav.'" ORDER BY id';
		$res_mat = mysqli_query($mysqli, $q) or die( mysqli_error($mysqli) );
			
		while ($row_mat = mysqli_fetch_assoc($res_mat)) {
			
				echo ''.$row_mat['html_code'];
				
				} //конец вывода доп.материала if ($mysqli->connect_error) {
	
		
		echo '</div>';	//'<div class="element">'; //отдельный контейнер по элементам, более узкий, все остальное выводим туда	
		
		//добавляем строу навигации справочно
		$linknavy = navy_by_alias($mysqli, $aliassprav);
		echo '<div style="font-size: 85%; margin: 0px 10px 0px 4px; text-align: right"">'.$linknavy.'</div>';
			
		}
}		
		
function  tel($tel){
	
	$tel=str_replace('(ф.)','<span class="telfaxmob">(ф.)</span>', $tel );
	$tel=str_replace('(ф)','<span class="telfaxmob">(ф)</span>', $tel );
	$tel=str_replace('(моб.)','<span class="telfaxmob">(моб.)</span>', $tel );
	$tel=str_replace('(сот.)','<span class="telfaxmob">(моб.)</span>', $tel );
	$tel=str_replace('(ГС)','<span class="telfaxmob">(ГС)</span>', $tel );
	
	return $tel;
	} //function  tel($tel){	
	 
		 
?>
