<div class="alert alert-success" style="
direction:rtl;text-align:center;display:none;position:fixed;top:50%;margin-left:-175px;margin-top:-100px;height:200px;left:50%;width:350px;z-index:99999999999999999;

-webkit-box-shadow: 0px 0px 66px 0px rgba(168,168,168,1);
-moz-box-shadow: 0px 0px 66px 0px rgba(168,168,168,1);
box-shadow: 0px 0px 66px 0px rgba(168,168,168,1);


background:-webkit-radial-gradient(circle farthest-side at center center, rgb(255, 255, 255) 0%, rgb(230, 255, 232) 100%);
background:-o-radial-gradient(circle farthest-side at center center, rgb(255, 255, 255) 0%, rgb(230, 255, 232) 100%);
background:-moz-radial-gradient(circle farthest-side at center center, rgb(255, 255, 255) 0%, rgb(230, 255, 232) 100%);
background:radial-gradient(circle farthest-side at center center, rgb(255, 255, 255) 0%, rgb(230, 255, 232) 100%);
">
	
	<div style="width:80px;margin:auto;">
	<img src="images/cart_success.gif">
	</div>
	<br>
  <strong>המוצר התווסף לעגלת הקניות!</strong> <br>המשך לעיין באתר או צפה  <a href=",cart">בעגלת הקניות</a><br>
  <!--<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button> -->
</div>

<?php



// Get the template
if (!$TEMP_TEMPLATE) { 
	$TEMP_TEMPLATE = 2; $TEMP_PAGE_TITLE = "שגיאה במערכת";
	//function PAGE_TEXT() { echo "</h3>שגיאה במערכת</h3><strong>שגיאה במערכת</strong>"; } 
}
elseif (!function_exists(PAGE_TEXT)) {
	$TEMP_TEMPLATE = 2; $TEMP_PAGE_TITLE = "שגיאה במערכת";
	//function PAGE_TEXT() { echo "</h3>שגיאה במערכת</h3><strong>שגיאה במערכת</strong>"; } 
}


$GET_TEMP_q = mysql_query("SELECT * FROM `templates` WHERE `id` = '$TEMP_TEMPLATE'") or die(mysql_error());
$GET_TEMP_r = mysql_fetch_array($GET_TEMP_q);
include("templates/".$GET_TEMP_r[file]);

// Get site configuration
$GET_SITE_CONFIG_q = mysql_query("SELECT * FROM `config_site` WHERE `lang_id`=".LANG) or die(mysql_error());
$GET_SITE_CONFIG_r = mysql_fetch_array($GET_SITE_CONFIG_q);

if ($GET_keywords) { $GET_SITE_CONFIG_r[keywords] = $GET_keywords; }
if ($GET_description) { $GET_SITE_CONFIG_r[description] = $GET_description; }

//$result = mysql_query('SELECT * FROM `mod_seo` WHERE `mod_id`='.intval(get_mod_id()).' AND `get_act`="'.mysql_real_escape_string($get_act).'" AND `get_id`='.intval($get_id).' AND `lang_id`='.intval($lang));
//$seo = mysql_fetch_array($result);

// Get blocks lisr
$GET_BLOCKS_q = mysql_query("SELECT L.* FROM `mod_blocks` L,`mod_blocks_list` T WHERE L.`page_id`=T.`id` AND L.`lang_id`=".LANG) or die(mysql_error());


/*$GET_SEO_q = mysql_query("SELECT L.* FROM `mod_seo` L,`mod_seo_list` T 
WHERE L.`mod`='$get_mod' OR L.`act`='$get_act' AND
L.`id`=T.`id` AND L.`lang_id`=".LANG);*/


if($get_mod == "products" || $get_mod == "catalog")
{
	if($get_mod == "products")
	{
		if($get_id != "" && $get_act == "")
		{
			$GET_SEO_q2 = mysql_query("SELECT * FROM `mod_products_categories` WHERE `lang_id`=".LANG." AND `id`='$get_id'") or die(mysql_error());
			$SEO = mysql_fetch_array($GET_SEO_q2);
		}
		elseif($get_act != "")
		{
			$GET_SEO_q2 = mysql_query("SELECT * FROM `mod_products` WHERE `lang_id`=".LANG." AND `page_id`='$get_id'") or die(mysql_error());
			$SEO = mysql_fetch_array($GET_SEO_q2);			
		}
	}
	
}
else
{
	$GET_SEO_q2 = mysql_query("SELECT * FROM `mod_seo` WHERE `lang_id`=".LANG." AND `mod`='$get_mod' AND `id`='$get_id' AND `act`='$get_act'") or die(mysql_error());
	$SEO = mysql_fetch_array($GET_SEO_q2);
}

//$SEO6=DATAseo($get_mod,$get_id,$get_act,LANG);
$P_TITLE=trim($SEO["ptitle"]) != ""?($SEO["ptitle"]):($TEMP_PAGE_TITLE);
$P_KEYWORDS=trim($SEO["keywords"]) != ""?($SEO["keywords"]):htmlspecialchars($GET_SITE_CONFIG_r[keywords]);
$P_DESCRIPTION=trim($SEO["description"]) != ""?($SEO["description"]):htmlspecialchars($GET_SITE_CONFIG_r[description]);

// Replacement expressions
$TEMP_Reps_A = array(

	"<% SITE_NAME %>",
	"<% SITE_KEYWORDS %>",
	"<% SITE_DESCRIPTION %>",
	"<% INPAGE_TITLE %>",
	"<% PAGE_TITLE %>",
	"<% SITE_FLOAT %>"
);

$TEMP_Reps_B = array(

	htmlspecialchars($GET_SITE_CONFIG_r[name]),
	$P_KEYWORDS,
	$P_DESCRIPTION,	
	stripslashes($TEMP_INPAGE_TITLE),
	$P_TITLE,
	((intval($GET_SITE_CONFIG_r['float'])==1) && $get_mod == 'pages' && $get_id == 1?"open_float_div('FloatDiv','FloatDiv_".intval($GET_SITE_CONFIG_r['float_update'])."');":"")
	
	);
	

$GET_TEMP_file = str_replace($TEMP_Reps_A, $TEMP_Reps_B, ob_get_contents());


if ($UserSess && $PassSess) {
$adminEdit = "<div style='background-color:#003872;height:30px;line-height:30px;width:100%;'><a style='color:white;' href='/admin.php?act=go&mod=blocks&op=edit&id=";
$adminEdit2 = "' target='_blank'>ערוך בלוק</a></div>";
} else {
	$adminEdit = "";
	$adminEdit2 = "";
}


/*
$GET_TEMP_text = str_replace($TEMP_Reps_A, $TEMP_Reps_B, ob_get_contents());
$GET_PAGE_text = str_replace("<% BLOCK_".$GET_BLOCKS_r[page_id]." %>", $GET_BLOCKS_r[text].$adminEdit.$id_of_block.$adminEdit2, $GET_TEMP_file);
*/


while($GET_BLOCKS_r = mysql_fetch_array($GET_BLOCKS_q)) {
	
	
	if ($UserSess && $PassSess) { 
	$id_of_block = $GET_BLOCKS_r[page_id];
	} else {
		$id_of_block = "";
	}
	
	
	$GET_TEMP_file = str_replace("<% BLOCK_".$GET_BLOCKS_r[page_id]." %>", $GET_BLOCKS_r[text].$adminEdit.$id_of_block.$adminEdit2, $GET_TEMP_file);
	$GET_TEMP_file = str_replace("<% BLOCK_TITLE_".$GET_BLOCKS_r[page_id]." %>", $GET_BLOCKS_r[title], $GET_TEMP_file);
}

$GET_TEMP_file = preg_replace_callback(
	"/<% LANG_([A-Za-z0-9]+) %>/",
	function($matches) use ($lang) { return $lang[$matches[1]]; },
	$GET_TEMP_file
);
ob_get_clean();
echo ($GET_TEMP_file);//stripslashes
//$SEO=array($get_mod,$get_id,$get_acts);
//print_r($SEO);
?>