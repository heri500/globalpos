<?php

function _addJqueryPlugins($jqueryversion = 'jquery-1.7.2.js'){
	drupal_add_js('sites/all/libraries/jquery/'.$jqueryversion);
}
function _addJeditablePlugins(){
	drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.mini.js');
	drupal_add_js('sites/all/libraries/jeditable.datepicker/jquery.jeditable.datepicker.js');	
}
function _addAutotabPlugins(){
	drupal_add_js('sites/all/libraries/autotab/jquery.autotab.js');	
}
function _addTimePickerPlugins(){
	drupal_add_css('sites/all/libraries/timepicker/jquery-ui-timepicker-addon.css');
	drupal_add_js('sites/all/libraries/timepicker/jquery-ui-timepicker-addon.js');	
}
function _addCustomCSS(){
	$path = drupal_get_path('theme', 'cti_flex');
 	$form_style = $path.'/css/custom-style.css';
 	drupal_add_css($form_style, 'theme', 'all', FALSE);
 	$variables['styles'] = drupal_get_css();	
}
function _addBlockUiPlugins(){
	drupal_add_js('sites/all/libraries/block/jquery.blockUI.js');	
}
function ARRAY_TYPE_ITEM(){
	return array('Produk','Jasa');	
}
function _addChosenJqueryPlugins(){
	$path = 'sites/all/libraries/chosen/';
	$csspath = $path.'css/chosen.css';
	drupal_add_css($csspath);
	$jspath = $path.'js/chosen.jquery.min.js';
	drupal_add_js($jspath);
}