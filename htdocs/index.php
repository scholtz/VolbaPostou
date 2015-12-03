<?php
/** 
* Loading index file for apache or nginx
/**/

try{
	require '../vendor/autoload.php';
	if($_SERVER['APPLICATION_ENV'] && file_exists($f="../conf/".$_SERVER['APPLICATION_ENV']."/settings.php")){
		require_once($f);
	}elseif(file_exists("../conf/settings.php")){
		require_once("../conf/settings.php");
	}elseif(file_exists("settings.php")){
		require_once("settings.php");
	}else{
		\AsyncWeb\Frontend\SetupSettings::show();
	}

	\AsyncWeb\Frontend\BlockManagement::renderWeb();
}catch(\Exception $exc){
	echo "Exception: ".$exc->getMessage();
}
