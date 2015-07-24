<?php

if (defined('PATH')==FALSE) {require 'login.php';}

if (isset($_GET['predl'])) {
	
	//echo 'predl определено ';
	
	switch($_GET['predl']) {
	
	case 'top-news':
	//echo 'top-news nottell';
		include 'nottell/top-news.html';
		break;
	case 'presentation':
		include 'nottell/presentation.html';
		break;
	case 'plan':
		include 'nottell/plan.html';
		break;
	default:
		include 'nottell/dictionary.html';
		break;
	}
	
}
else {
	
		echo '<link rel="stylesheet" type="text/css" href="'.$path.'/css/articles.css">';
		$pathweb='http://sprav89.ru/nottell/';
		
		$style_ogl =  'style="padding:0px 0px 0px 1px; margin:0px 0px 0px 1px; border-bottom:1px solid #CFCFCF;display: inline-block;width: 95%;"';
		$style_img = 'style="padding: 0px; margin: 10px 30px 11px 10px; width: 20%; float: left;"';
		$style_divtitle = 'style="padding: 0px 0px 0px 1px; margin: 15px 2px 0px 1px;"';
		$style_title = 'style="padding: 0px; margin: 9px 2px 0px 1px;"';
		$style_paragr = 'style="padding: 10px 11px 12px 0px; margin: 0px; font-size: 16px;"';
		
		
	//echo '<link rel="stylesheet" href="../сss/articles.css" type="text/css" />';
	
	//Газета Топ-новости
	
	echo '<div '.$style_ogl.'>';
	echo '<img '.$style_img.' src="'.PATH.'/nottell/cp_00.jpg" class="img_ogl" alt="Обложка газеты" />';
	echo '<div '.$style_divtitle.'>';
	echo '<a class="ptitle" '.$style_title.' href="'.PATH.'?option=nottell&predl=top-news">Газета «Топ-новости»</a>';
	echo '<p class="description" '.$style_paragr.'>Самое оперативное рекламно-информационное издание с наибольшим охватом потенциальных покупателей — 17 000 экз.<br />Распространяется бесплатно по почтовым ящикам.</p>';
	echo '<p class="dalee"><a href="'.PATH.'?option=nottell&predl=top-news"'.$style_paragr.'>Подробнее...</a></p>';
	echo '</div>';
	echo '</div>';

	//Справочник dictionary

	echo '<div '.$style_ogl.'>';
	echo '<img '.$style_img.' src="'.$pathweb.'cp_03.jpg" class="img_ogl" alt="Обложка справочника" />';
	echo '<div '.$style_divtitle.'>';
	echo '<a class="ptitle" '.$style_title.' href="'.PATH.'?option=nottell&predl=dictionary">Новый Уренгой. Телефонный справочник города</a>';
	echo '<p class="description" '.$style_paragr.'>Глянцевый журнал формата А4 (210 х 297 мм) — самый полный адресно-телефонный справочник по предприятиям и организациям города.</p>';
	echo '<p class="dalee"><a href="'.PATH.'?option=nottell&predl=dictionary"'.$style_paragr.'>Подробнее...</a></p>';
	echo '</div>';
	echo '</div>';
	
	//План-схема города plan
	echo '<div '.$style_ogl.'>';
	echo '<img '.$style_img.' src="'.$pathweb.'cp_04.jpg" class="img_ogl" alt="Обложка карты" />';
	echo '<div '.$style_divtitle.'>';
	echo '<a class="ptitle" '.$style_title.' href="'.PATH.'?option=nottell&predl=plan">Новый Уренгой. План-схема города</a>';
	echo '<p class="description" '.$style_paragr.'>Самое первое наше издание: выпускается с 2010 года.<br />Представляет из себя глянцевый буклет формата  А4 (210 х 297 мм), периодичность — 1 раз в год.</p>';
	echo '<p class="dalee"><a href="'.PATH.'?option=nottell&predl=plan" '.$style_paragr.'>Подробнее...</a></p>';
	echo '</div>';
	echo '</div>';
	
	//Настенная карта map
	echo '<div '.$style_ogl.'>';
	echo '<img '.$style_img.' src="'.$pathweb.'cp_02.jpg" class="img_ogl" alt="Настенная карта" />';
	echo '<div '.$style_divtitle.'>';
	echo '<a class="ptitle" '.$style_title.' href="'.PATH.'?option=articles&article=mapwall">Настенная карта города Новый Уренгой</a>';
	echo '<p class="description" '.$style_paragr.'>';
	echo 'Формат карты: 100 х 70 см, тираж — 1000 экз., которого должно хватить на все офисы города на несколько лет.<br />Самая наглядная реклама, действующая наиболее продолжительное время.</p>';
	echo '<p class="dalee"><a href="'.PATH.'?option=articles&article=mapwall" '.$style_paragr.'>Подробнее...</a></p>';
	echo '</div>';	
	echo '</div>';
	
	//Журнал ПРЕЗЕНТАЦИЯ presentation
	
	
	echo '<div '.$style_ogl.'>';
	echo '<img '.$style_img.' src="'.PATH.'/nottell/cp_01.jpg" class="img_ogl" alt="Обложка журнала" />';
	echo '<div '.$style_divtitle.'>';
	echo '<a class="ptitle" '.$style_title.' href="'.PATH.'?option=nottell&predl=presentation">Журнал-проспект «ПРЕЗЕНТАЦИЯ»</a>';
	echo '<p class="description" '.$style_paragr.'>Распространялось бесплатно по почтовым ящикам и стойкам в магазинах.<br />
	Тираж — 15&nbsp;000 экз. Формат&nbsp;А5&nbsp;(148&nbsp;х&nbsp;210&nbsp;мм).<br />
	В настоящий момент издание приостановлено.</p>
	</p>';
	echo '<p class="dalee"><a href="'.PATH.'?option=nottell&predl=presentation">Подробнее...</a></p>';
	echo '</div>';
	echo '</div>';

}

?>