<?
include("config.php");
include "301.php";
//require_once('lang.php');
//Language support

$indexGetExp = explode(',' , $_GET['mod']);
if($indexGetExp[0] == 'pages')
{
	if( !is_numeric($indexGetExp[1]) )
	{
		$moduleExp = explode('-' , $indexGetExp[1]);
		$_GET['mod'] = 'pages,' . $moduleExp[0];
	}
}

$GLOBALS["members"]="";
		$q=mysql_query("SELECT * FROM `mod_languages` WHERE `code`='".mysql_escape_string(strtolower($_GET["lang"]))."'");
		if(mysql_num_rows($q)>0){
			$r=mysql_fetch_array($q);
			define("LANG",$r["id"]);
			define("LANGCODE",$r["code"]);
			define("LANGDIR",$r["direction"]);
			define("LANGDEFAULT",$r["default"]>0?true:false);
			if($r["default"]){
				header("Location: /");
			}
			if($r["domain"]!=""){
				//header("HTTP/1.1 301 Moved Permanently");
				//header("Location: ".preg_replace("/^www\./","",$r["domain"]));
			}
		}else{
			$DOMAIMN=preg_replace("/^www\./","",$_SERVER['HTTP_HOST']);
			$DOMAIMN=preg_replace("/\.$/","",$DOMAIMN);
			$domain_lang=mysql_query("SELECT * FROM `mod_languages` WHERE `domain`='".mysql_real_escape_string($DOMAIMN)."'")or die(mysql_error());
			if($r=mysql_fetch_array($domain_lang)){
					define("LANG",$r["id"]);
					define("LANGCODE",$r["code"]);
					define("LANGDIR",$r["direction"]);
					define("LANGDEFAULT",$r["default"]>0?true:false);	
			}else{
				$q=mysql_query("SELECT * FROM `mod_languages` WHERE `default`=1");
				$r=mysql_fetch_array($q);
				define("LANG",$r["id"]);
				define("LANGCODE",$r["code"]);
				define("LANGDIR",$r["direction"]);
				define("LANGDEFAULT",$r["default"]>0?true:false);
			}
		}
	


$PUT_LEFT=true;
$PATH=array();
$lang=array();
$get_langs_q=mysql_query('SELECT L.`text`,V.`text` AS `vtext`
							FROM `mod_languages_label` L LEFT JOIN `mod_languages_values` V
							ON L.`id`=V.`lable_id` AND V.`lang_id`='.LANG
							)or die(mysql_error());
while($get_langs_r=mysql_fetch_array($get_langs_q)){
	$lang[$get_langs_r["text"]]=$get_langs_r["vtext"];
}







// Lock check
$GET_SITE_LOCK_q = mysql_query("SELECT * FROM `config_sitelock`");
$GET_SITE_LOCK_r = mysql_fetch_array($GET_SITE_LOCK_q);

$UserSess = mysql_real_escape_string($_SESSION[$SESSUSER]);
$PassSess = mysql_real_escape_string($_SESSION[$SESSPASS]);

$GET_SITE_CONFIG_q = mysql_query("SELECT * FROM `config_site` WHERE `lang_id`=".LANG)or die(mysql_error());;
$GET_SITE_CONFIG_r = mysql_fetch_array($GET_SITE_CONFIG_q);

if ($UserSess && $PassSess) {
	$LOGIN_C_q = mysql_query("SELECT id FROM `admins` WHERE `username` = '$UserSess' AND `password` = '$PassSess'");
	$LOGIN_C_c = mysql_num_rows($LOGIN_C_q);

	if ($LOGIN_C_c > 0) { 
		$GET_SITE_LOCK_r[status] = 0;
	}
}




// Statistics include
echo "<iframe src='statistics.php?page=".$_SERVER['REQUEST_URI']."' style='width:1px;height:1px;'></iframe>";
//include "statistics.php?page=".$_SERVER['REQUEST_URI']."&referer=".$_SERVER['HTTP_REFERER']."";




if ($GET_SITE_LOCK_r[status] == 0) {
	//print_r($_GET);
	if($_GET["query"]!=""){
		$q=mysql_query("SELECT * FROM `mod_301` WHERE `before`='".mysql_escape_string($_GET["query"])."'");
		$q1=mysql_query("SELECT * FROM `mod_seo` WHERE `plink`='".mysql_escape_string($_GET["query"])."' AND `lang_id`=".LANG);
		if(mysql_num_rows($q)>0){
			$r=mysql_fetch_array($q);
			header("HTTP/1.1 301 Moved Permanently");
			header("Location: ".$r["after"]);
			die();
		}elseif(mysql_num_rows($q1)>0){
			$r=mysql_fetch_array($q1);
			list($get_mod, $get_act, $get_id) = array($r["mod"],$r["act"],$r["id"]);
			

		
		}else{
			//header("HTTP/1.1 404 Not Found");
			//die();
		}
	}
	else if (trim($_GET['mod']) !="") 
	{
		$getmod = explode(",",$_GET['mod']);
		list($get_mod, $get_act, $get_page) = $getmod;
		if (is_numeric($get_act)) 
		{ 
			$get_id = $get_act; 
			$get_act=""; 
		}
		else 
		{
			$get_act = explode("_", $get_act); 
			list($get_act, $get_id) = $get_act;
		}
	}

	if ($get_act && is_string($get_act)) { $INC_File = $get_act;  }
	else { $INC_File = "index";  }


	if (file_exists("modules/".$get_mod."/".$INC_File.".php")) {

		$MOD_CHECK_q = mysql_query("SELECT * FROM `mod_modules` WHERE `module` = '$get_mod'");
		$MOD_CHECK_r = mysql_fetch_array($MOD_CHECK_q);

		if ($MOD_CHECK_r[status] == 1) {

			$MOD_TEMPCHECK_q = @mysql_query("SELECT * FROM `".$get_mod."_config` WHERE `id` = '1'");
			if (@mysql_num_rows($MOD_TEMPCHECK_q) > 0) {
				$MOD_TEMPCHECK_r = mysql_fetch_array($MOD_TEMPCHECK_q);	
				$TEMP_TEMPLATE = $MOD_TEMPCHECK_r[template];
			}		
			
			include ("modules/".$get_mod."/".$INC_File.".php");
		}

		else {
			function PAGE_TEXT() { echo "<h3>  </h3>:   "; }
			$TEMP_PAGE_TITLE = "  ";
			$TEMP_TEMPLATE = 2;
		}
	}
	else {
		//function PAGE_TEXT() { echo "<h3>  </h3><strong>  </strong>"; }
		header('location:/');
		$TEMP_PAGE_TITLE = "  ";
		$TEMP_TEMPLATE = 2;
	}
}

else {
	function PAGE_TEXT() { global $GET_SITE_LOCK_r; echo "<h3> </h3>".$GET_SITE_LOCK_r[msg]; }
	$TEMP_PAGE_TITLE = " ";
	$TEMP_TEMPLATE = 2;
}

include("templates.php");















// Live Edit include 
if ($UserSess && $PassSess) {
	//echo "logged In";
	echo "
	<style>
		.admin100100100_wrapper {
			width:100%;
			position:fixed;
			
			direction:rtl;
			top:0px;
			height:50px;
			background-color:#003872;
			
			-webkit-box-shadow: 0px 50px 120px -50px rgba(0,0,0,0.75);
			-moz-box-shadow: 0px 50px 120px -50px rgba(0,0,0,0.75);
			box-shadow: 0px 50px 120px -50px rgba(0,0,0,0.75);
			color:white;
			z-index:9999999999999999;
			line-height:50px;
			
			
		}
		.admin100100100_wrapper ul{
			float:left;
			width:50%;
			direction:rtl;
			margin:0px;
			padding:0px;
			
		}
		.admin100100100_wrapper ul li{
			float:left;
			list-style:none;
			width:150px;
			text-align:left;
		}
		.admin100100100_wrapper ul li a{
			color:white;
			float:right;
		}
		.admin100100100_wrapper ul li img{
			float:right;
			padding-top:17px;
			padding-left:10px;
			padding-right:10px;
		}
	</style>
	
	
	";
	if($get_mod == 'products' & $get_act == 'show'){
		
		$opLink = 'edit_product';
	} else {
		$opLink = 'edit';
	}
	
	echo "<div class='admin100100100_wrapper' style=''>";
	echo "<div style='float:right;padding-top:5px;padding-right:5px;padding-left:5px;'> <a href='/admin.php' target='_blank'><img src='http://www.digitalst.co.il/cms7/images/admin-symbol.png'></a></div>";
	echo "<div style='float:right;'> שלום ".$UserSess." ברוך הבא למערכת</div>";
	
	echo "<ul style=''>";
	 echo "<li style='background-color:#002953;'><a href='/admin.php?act=logout' >
	 התנתקות
	 <img src='http://www.digitalst.co.il/cms7/images/icon_out.png'>
	 </a></li>";
	echo "<li style='background-color:#0054a9;'>
	<a target='_blank' href='admin.php?act=go&mod=".$get_mod."&op=".$opLink."&id=".$get_id."'> ערוך עמוד <img src='http://www.digitalst.co.il/cms7/images/icon_edit.png'></a> </li>";
	echo "<li style='background-color:#024991;'>
	<a onclick='if(confirm('האם אתה בטוח שתרצה להעביר את העמוד לארכיון ?')){return true}else{return false};' target='_blank' href='admin.php?act=go&mod=".$get_mod."&op=del&id=".$get_id."'>
	 העבר לארכיון
	 <img src='http://www.digitalst.co.il/cms7/images/icon_archive.png'>
	 </a></li>";
	echo "<li style='background-color:#002953;'><a onclick='if(confirm('האם אתה בטוח כי ברצונך לבצע מחיקה ?')){return true}else{return false};' target='_blank' href='admin.php?act=go&mod=".$get_mod."&op=del&id=".$get_id."&rdel=1' >
	 מחק עמוד
	 <img src='http://www.digitalst.co.il/cms7/images/icon_delete.png'>
	 </a></li>";
	
	echo "</ul>";
	echo "</div>";
	
	
}




?>