<?
function template($file, $vars = array()){
	extract($vars);

	ob_start();
	include(basename(__FILE__).'view/'.$file);
	$contents = ob_get_contents();
	ob_end_clean();
	
	return $contents;
}