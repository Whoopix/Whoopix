<?
//setlocale( LC_ALL, 'he_IL.utf8');



mb_internal_encoding("UTF-8");
function get_default_language(){}
function get_mod_id(){}

function sendHTMLemail($to, $subject, $from, $body) { 

    if (ereg("(.*)< (.*)>", $from, $regs)) {
        $from = '=?UTF-8?B?'.base64_encode($regs[1]).'?= < '.$regs[2].'>';
    } else {
        $from = $from;
    }

    $headers = "From: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $boundary = uniqid("HTMLEMAIL");
    $headers .= "Content-Type: multipart/alternative;".
        "boundary = $boundary\r\n\r\n";
    $headers .= "This is a MIME encoded message.\r\n\r\n";
    $headers .= "--$boundary\r\n".
        "Content-Type: text/plain; UTF-8\r\n".
        "Content-Transfer-Encoding: base64\r\n\r\n";
    $headers .= chunk_split(base64_encode(strip_tags($body)));
    $headers .= "--$boundary\r\n".
        "Content-Type: text/html; charset=UTF-8\r\n".
        "Content-Transfer-Encoding: base64\r\n\r\n";
    $headers .= chunk_split(base64_encode($body)); 

    $result = mail($to,'=?UTF-8?B?'.base64_encode($subject).'?=',"",$headers);
    return $result;
}	
function doLink($mod="",$id="",$act=""){
	$new=$mod.(($act!="" OR $id !="")?",":"").($act!=""?$act:"").($act!=""?"_".$id:$id);
	$q=mysql_query("SELECT * FROM `mod_seo` WHERE `mod`='".mysql_escape_string($mod)."' AND `act`='".mysql_escape_string($act)."' AND `id`='".mysql_escape_string($id)."'");
	if($r=mysql_fetch_array($q) && $r["plink"]!="")
		return htmlspecialchars($r["plink"]);
	return ",".$new;
}
	function do_num_pages($count,$Ppage,$mod="products",$query=""){//&word=".$_REQUEST["word"]
		if($count>1){
			echo "\t\t\t\t<div class=\"pnav\" style=\"text-align:center;clear:both;\">\n";
			if($Ppage>1){
				echo "\t\t\t\t\t<a href=\",".$mod.$query."&page=".($Ppage-1)."\" onclick=\"return make_prohref(this.href);\">הקודם</a>\n";
			}
			$from=1;
			$to=$count;
			if($count>8){
				$from=$Ppage;
				$to=$Ppage+8;
				if($Ppage>$count-8){
					$from=$count-8;
					$to=$count;
				}
			}
			for($i=$from;$i<=$to;$i++){
				if($i != $Ppage)
					echo "\t\t\t\t\t<a href=\",".$mod.$query."&page=".($i)."\" onclick=\"return make_prohref(this.href);\">".$i."</a>\n";
				else
					echo "\t\t\t\t\t<b>".$i."</b>\n";
			}

			if($Ppage<$count){
				echo "\t\t\t\t\t<a href=\",".$mod.$query."&page=".($Ppage+1)."\" onclick=\"return make_prohref(this.href);\">הבא</a>\n";
			}
			echo "\t\t\t\t</div>\n";
		}	
	}
	

function PUTseo($lang_id=0,$value=array(),$put=true){
	global $lang;
	if($put){
?>


     <div class="panel-body">
									<a class="btn btn-custom m-t-10 collapseble col-md-12 col-sm-12 col-xl-12 col-xs-12">קידום אתרים SEO</a>
                                    
                             
	<!--input type="button" value="<?=$lang["seo"];?>" class="button orange" onclick="showHideSEO('seo_content_<?=$lang_id;?>')" />-->
<?
	}
?>


<div class="m-t-15 collapseblebox dn" <?=(!$value["ptitle"] AND !$value["keywords"] AND !$value["description"] AND $put)?"style=\"display:none;\"":"style=\"display:block;\"";?>  id="seo_content_<?=$lang_id;?>">
        <div class="well"  style="background-color:#f7fafc !important;"> 


		
		
	
		 <div class="form-group" style="padding-top:50px;">
                               
                                <input name="seo[<?=$lang_id;?>][ptitle]" type="text" class="form-control" id="inputName1" 
								placeholder="<?=$lang["seo_title"];?>" value="<?=htmlspecialchars($value["ptitle"]);?>">
								<div class="help-block with-errors"><ul class="list-unstyled"><li></li></ul></div>
         </div>
						
						
						
		 <div class="form-group">
                                
                                <input name="seo[<?=$lang_id;?>][plink]" type="text" class="form-control" id="inputName1" 
								placeholder="<?=$lang["seo_link"];?>" value="<?=htmlspecialchars($value["plink"]);?>">
								<div class="help-block with-errors"><ul class="list-unstyled"><li></li></ul></div>
          </div>
						
						
						
						
						
		 <div class="form-group">
                                 
								<textarea placeholder="<?=$lang["seo_keywords"];?>" name="seo[<?=$lang_id;?>][keywords]" class="form-control form-control" rows="5"><?=htmlspecialchars($value["keywords"]);?></textarea>
								<div class="help-block with-errors"><ul class="list-unstyled"><li></li></ul></div>
          </div>
						
						
						
						
									
		 <div class="form-group">
                                 <label for="inputName1" class="control-label"><?=$lang["seo_description"];?></label>
								<textarea placeholder="<?=$lang["seo_description"];?>" name="seo[<?=$lang_id;?>][description]" class="form-control form-control" rows="5"><?=htmlspecialchars($value["description"]);?></textarea>
								<div class="help-block with-errors"><ul class="list-unstyled"><li></li></ul></div>
         </div>
						
						
						
	
						
						
						
<!--
<?=$lang["seo_title"];?>
<input name="seo[<?=$lang_id;?>][ptitle]" type="text" class="text" value="<?=htmlspecialchars($value["ptitle"]);?>" />


<?=$lang["seo_link"];?>
<input name="seo[<?=$lang_id;?>][plink]" type="text" class="text" value="<?=htmlspecialchars($value["plink"]);?>" />






<?=$lang["seo_keywords"];?>:</strong>
<textarea cols="50" rows="5" name="seo[<?=$lang_id;?>][keywords]" class="text"><?=htmlspecialchars($value["keywords"]);?></textarea>

<?=$lang["seo_description"];?>
<textarea cols="50" rows="5" name="seo[<?=$lang_id;?>][description]" class="text"><?=htmlspecialchars($value["description"]);?></textarea>


-->

</div>
	</div>
</div>
<?
}
function INSERTseo($MOD="",$ID="",$ACT=""){
	$values=(array)$_POST["seo"];
	mysql_query("DELETE FROM `mod_seo` WHERE `mod`='".$MOD."' AND `id`='".$ID."' AND `act`='".$ACT."'");
	foreach($values AS $key=>$value){
		if(trim($value["ptitle"]) != "" OR trim($value["keywords"]) != "" OR trim($value["description"]) != "" OR trim($value["plink"]) != ""){
			mysql_query("INSERT INTO `mod_seo` (`id`,`mod`,`act`,`lang_id`,`ptitle`,`keywords`,`description` , `plink`) VALUES
												('".($ID)."','".$MOD."','".$ACT."','".$key."','".strip($value["ptitle"],true,true)."','".strip($value["keywords"],true,true)."','".strip($value["description"],true,true)."' , '".strip($value["plink"],true,true)."' )");
		}
	}
}
function DATAseo($MOD="",$ID="",$ACT="",$lang=0){
	if($lang>0){
		$q=mysql_query("SELECT * FROM `mod_seo` WHERE `mod`='".$MOD."' AND `id`='".$ID."' AND `act`='".$ACT."' AND `lang_id`='".$lang."'");
		return mysql_fetch_array($q);
	}
	$q=mysql_query("SELECT * FROM `mod_seo` WHERE `mod`='".$MOD."' AND `id`='".$ID."' AND `act`='".$ACT."'");
	$seo=array();
	while($r=mysql_fetch_array($q))
		$seo[$r["lang_id"]]=$r;
	return $seo;
	
}
function get_edit_language(){
	$q=mysql_query("SELECT * FROM `mod_languages` WHERE `id`='".intval($_COOKIE["edit_lang"])."'");
	if(mysql_num_rows($q)>0){
		return intval($_COOKIE["edit_lang"]);
	}else{
		$result = mysql_query('SELECT * FROM `mod_languages` WHERE `default`=1');
		if (!($row = mysql_fetch_array($result)))
		{
			return false;
		}
		return $row['id'];
	}	
}
function do_no_access($access=""){
	global $USER_LOGIN;
	$access=explode(",",$access);
	$access=is_array($access)?$access:array();
	$access=array_filter($access,create_function('$var','return (intval($var)>0);'));
	$access=array_unique($access);
	if(count($access)>0){
		if($USER_LOGIN!=false){
			if(!in_array($USER_LOGIN['group'],$access)){
				return false;
			}
		}else{
			return false;			
		}
	}
	return true;
}
function gocode($m) { 
	$string = "abcdefghijklmnopqrstuvwxyz0123456789"; 
	$name = ""; 
	for($i = 0; $i < $m; $i++) 
	$name .= substr($string, rand(0,(strlen($string) - 1)), 1); 
		return $name; 
}
function cart_stat(){
	$amount=0;
	$quantity=0;

	$query=mysql_query("SELECT `p`.`quantity`,`l`.`price`,`l`.`newprice`,`l`.`onsale` FROM `mod_cart` AS `p` 
													INNER JOIN `mod_products_list` AS `l` 
													ON `p`.`prod_id`=`l`.`id` 
													WHERE `p`.`hash` = '".mysql_real_escape_string($_SESSION["cart"])."'");
	while($row=mysql_fetch_array($query)){

		if($row["onsale"]>0)
			$amount+=$row["newprice"]*$row["quantity"];
		else
			$amount+=$row["price"]*$row["quantity"];
		
		$quantity+=$row["quantity"];
	}
	return array("amount"=>$amount,"quantity"=>$quantity);

}
function fileIcon($name){
	$name=get_ext_name($name);
	switch($name){
                case "pdf": return "/admin/style/icons/pdf.gif";
                case "zip": return "/admin/style/icons/zip.gif";
				case "docx":
                case "doc": return "/admin/style/icons/doc.gif";
				case "xlss":
                case "xls": return "/admin/style/icons/xls.gif";
				case "pptx":
                case "ppt": return "/admin/style/icons/ppt.gif";
                case "gif": 
                case "png":
                case "jpe":
				case "jpeg":
				case "bmp":
                case "jpg": return "/admin/style/icons/image.gif";
				case "txt": return "/admin/style/icons/txt.gif";
				case "exe":
                default: return "/admin/style/icons/file.gif";
	}
	return "";
}
function get_file_icon($name){
	$name=get_ext_name($name);
	switch($name){
                case "pdf": return "pdf";
                case "zip": return "zip";
				case "docx":
                case "doc": return "doc";
				case "xlss":
                case "xls": return "xls";
				case "pptx":
                case "ppt": return "ppt";
                case "gif": 
                case "png":
				case "bmp":
                case "jpe":
				case "jpeg":
                case "jpg": return "image";
				case "txt": return "txt";
				case "exe":
                default: return "file";
	}
	return "";
}
function get_file_header($name){
	$name=get_ext_name($name);
	switch($name){
                case "pdf": return "application/pdf";
                case "zip": return "zip";
				case "docx":
                case "doc": return "application/msword";
				case "xlss":
                case "xls": return "application/vnd.ms-excel";
				case "pptx":
                case "ppt": return "application/vnd.ms-powerpoint";
                case "gif": return "image/gif";
                case "png": return "image/png";
				case "bmp": return "image/bmp";
                case "jpe":
				case "jpeg":
                case "jpg": return "image/jpeg";
				case "txt": return "txt";
				case "exe":
                default: return "application/octet-stream";
	}
	return "";
}
function ALLOW_IMAGES(){
	return array(
				"gif",
				"jpg",
				"png",
				"bmp",
				"jpeg"
				);
}
function get_ext($file=array()){
	return strtolower(array_pop(explode(".",$file["name"])));
}
function get_ext_name($file){
	return strtolower(array_pop(explode(".",$file)));
}
function give_name($ext,$dir){
	$name=gocode(6).".".$ext;
	if(file_exists($dir.".".$name))
		return give_name($ext,$dir);
	return $name;
}
function Up_file($file=array(),$dir="./"){
	$name=give_name(get_ext($file),$dir);
	if(move_uploaded_file($file["tmp_name"],$dir.$name))
		return $name;
	return "";
}
function UP_files($file=array(),$dir="./"){
	if(is_array($file["tmp_name"])){
		$ret=array();
		foreach($file["tmp_name"] AS $key=>$f){
			$name=give_name(get_ext_name($file["name"][$key]),$dir);
			if(move_uploaded_file($f,$dir.$name))
				$ret[]=$name;
		}
		return implode(",",$ret);
	}
	return "";
}
function loggedIn(){
	if(isset($_SESSION["login"]) && $_SESSION["login"]!=null){
		list($CHECK_id, $CHECK_pass)=explode("_",$_SESSION["login"]);
		$LOG_q = mysql_query("SELECT * FROM `mod_members` WHERE `id` = ".intval($CHECK_id)." AND `pass`='".mysql_escape_string($CHECK_pass)."' AND `stat` >0 ")or die(mysql_error());
		//echo $_SESSION["login"];
		if(mysql_num_rows($LOG_q)>0){
				
			$LOG_r = mysql_fetch_array($LOG_q);		
			return $LOG_r;
		}else
			return false;
	}elseif(isset($_COOKIE["USER"])){
		$LOG_q = mysql_query("SELECT * FROM `mod_members` WHERE `id` = ".intval($_COOKIE["USER"]["id"])." AND `pass`='".mysql_escape_string($_COOKIE["USER"]["pass"])."' AND `stat` >0 ");
		if(mysql_num_rows($LOG_q)>0){
			$LOG_r = mysql_fetch_array($LOG_q);	
			//$CHECK_c=md5(gocode(8));
			//mysql_query("UPDATE `mod_members` SET `check`='".$CHECK_c."' WHERE `id` = ".intval($LOG_r["id"]));
			session_regenerate_id();
			$_SESSION["login"]=intval($LOG_r["id"])."_".$_COOKIE["USER"]["pass"];
			return $LOG_r;
		}else
			return false;
	}else
		return false;
}
function get_block($id){
	$q=mysql_query("SELECT * FROM `mod_blocks` WHERE `page_id`='".$id."' AND `lang_id`=".LANG);
	if(mysql_num_rows($q)>0)
		return mysql_fetch_array($q);
	return array();
}
function get_site_language(){
	$q=mysql_query("SELECT * FROM `mod_languages` WHERE `id`='".intval($_COOKIE["lang"])."'");
	if(mysql_num_rows($q)>0){
		return intval($_COOKIE["lang"]);
	}else{
		$result = mysql_query('SELECT * FROM `mod_languages` WHERE `default`=1');
		if (!($row = mysql_fetch_array($result)))
		{
			return false;
		}
		return $row['id'];
	}	
}




function get_panel_language(){


	
	/// 2 its En
	/// 1 its HE
	/// meaning deafult is HE
	
	$UserSess = mysql_real_escape_string($_SESSION[$SESSUSER]);
	$Admin_Lang = mysql_query("SELECT * FROM `admins` WHERE username = '$UserSess'");
	$AL = mysql_fetch_array($Admin_Lang);
	
	if($AL['panel_lang'] == '2'){
		
		return "en";
		
	 } else {
		
		return "he";
		
		
	}
	
}
function strip($text,$html=true,$sql=false){
	if(get_magic_quotes_gpc())
		$text=stripslashes($text);
	if($html)
		$text=htmlspecialchars($text);
	if($sql){
		$text=mysql_real_escape_string($text);
	}
	return $text;
}
function getLangs(){
	$array=array();
	if ($handle = opendir('./admin/langs')) {
	    while (false !== ($file = readdir($handle))) {
			$lang=array();
	        if ($file != "." && $file != "..") {
	            include "./admin/langs/".$file;
				$array[$lang["code"]]=$lang["lang"];
	        }
	    }
	    closedir($handle);
	}
	return $array;
}
function utf8_substr($str,$from,$len){
# utf8 substr
# www.yeap.lv
  return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
                       '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
                       '$1',$str);
}
function send_mail($to, $from, $subject, $body, $attachs = array(), $encoding = 'UTF-8')
{
	if (!is_array($attachs))
	{
		$attachs = array('attach' => $attachs);
	}
	$subject = mb_convert_encoding($subject, $encoding);
	$body = mb_convert_encoding($body, $encoding);
	if (strtolower($encoding) == 'utf-8')
	{
		$subject = "=?UTF-8?B?".base64_encode($subject)."?=";
	}
	
	$semi_rand = md5( time() ); 
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x"; 	
	
	
	$headers = 'From: '.$from;
    $headers .=	"\nMIME-Version: 1.0\n" . 
                "Content-Type: multipart/mixed;\n" . 
                " boundary=\"{$mime_boundary}\"";
	
	$body = "This is a multi-part message in MIME format.\n\n" . 
                "--{$mime_boundary}\n" . 
                "Content-Type: text/html; charset=\"{$encoding}\"\n" . 
                "Content-Transfer-Encoding: 7bit\n\n" . 
                $body . "\n\n";
    
	foreach ($attachs as $name => $attach)
	{
		if ($data = file_get_contents($attach))
		{
			$data = chunk_split( base64_encode( $data ) );
            //$fileattname = basename($attach);
			$body .= "--{$mime_boundary}\n" . 
                 "Content-Type: application/octet-stream;\n" . 
                 " name=\"{$name}\"\n" . 
                 "Content-Disposition: attachment;\n" . 
                 " filename=\"{$name}\"\n" . 
                 "Content-Transfer-Encoding: base64\n\n" . 
                 $data . "\n\n" . 
                 "--{$mime_boundary}--\n";	
		}
	}
	return mail($to, $subject, $body, $headers);
}
	function PATH($first_id){
		$q=mysql_query("SELECT * FROM `mod_menu` WHERE `id`='".$first_id."' AND `lang_id`=".LANG)or die(mysql_error());
		$r=mysql_fetch_array($q);
		if($r["parent"]!=0)
			return array_merge(PATH($r["parent"]),array($r));
		return array($r);
	}
	
	/*  Debug functions begin */
	
	function d($param) {
		echo <<<HTML
	<br />
	<span style="color:#F00">Param $param</span>
	<br />
HTML;
	}
	
	function ddd($arr){
		foreach($arr as $key => $value){
			d('key '.$key);
			d('value '.$value);
		}
	}
	
	function dd($param) {
	  echo "<br />";
	  var_dump($param);
	  echo "<br />";
	}
	
	/*  Debug functions end */
		
	/* Path functions begin */
	function GetParentCategories(&$P,$get_id,$LANG) {

		$iParentQuery = mysql_query("SELECT `parent`,`catalog_id` FROM `mod_category_list` WHERE `id` = '".$get_id."'");
		$iParenFetch2 = mysql_fetch_assoc( $iParentQuery );
		if($iParenFetch2[parent])
			GetParentCategories($P,$iParenFetch2[parent],$LANG);
		else{
			//die(print($LANG));
			$iNavQuery3 = mysql_query("SELECT `title`,`id` FROM `mod_catalogs` WHERE `catalog_id` = '".$iParenFetch2[catalog_id]."' AND `lang_id`=".$LANG);
			$iNavFetch3 = mysql_fetch_assoc( $iNavQuery3 );

			$P[]=array("link"=>",cats_catalog,{$iNavFetch3['id']}" ,"title"=>$iNavFetch3['title']);
		}
		
		$iNavQuery2 = mysql_query("SELECT `title` FROM `mod_category` WHERE `category_id` = '".$get_id."' AND `lang_id`=".$LANG);
		$iNavFetch2 = mysql_fetch_assoc( $iNavQuery2 );

		$P[]=array("link"=>",cats_catalog,cat_{$get_id}" ,"title"=>$iNavFetch2['title']);		
	}


	/* Path functions begin */
	/* Articles Begin */
		function GetArticlesCatalogs(){
	global $lang,$idel,$iedit,$actId,$modId;
	$return="";
	$ACT_q=mysql_query("SELECT * FROM `mod_art_cat_list`");// ORDER BY `order`
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$GLOBALS["count"]++;
		$row=$GLOBALS["count"]%2;
		$padding=0;
	$return.= <<<html
		<tr class="row{$row}">
			<td>{$ACT_r[id]}</td>
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">&nbsp;<input type="text" class="order_input" name="order[{$ACT_r["id"]}]" value="{$ACT_r["order"]}" />&nbsp;{$ACT_r[title]}&nbsp;(<a href="admin.php?act={$actId}&mod={$modId}&op=index_art&id={$ACT_r[id]}">{$lang["articles_list"]}</a>)</td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
	}
	return $return;
}
		
	function GetArticles($catalog_id = 0,$parent=0,$i=0,$count=0){
	global $count,$lang,$idel,$iedit,$actId,$modId;
	$count = 0;
	$output = "";
	$ACT_q=mysql_query("SELECT * FROM `mod_article_list` WHERE `catalog_id` = '".$catalog_id."'") or die(mysql_error());
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$row=$count++%2;
		$padding=$i*12;
	$output .= <<<html
		<tr class="row{$row}">
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">{$ACT_r[title]}</td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit_art&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_art&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_art&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
		//$output .= GetCategories($catalog_id,$ACT_r["id"],$i+1);
	}
	
	return 	$output;
}	
	
	function GetArtCatalogName($id){
		$result = "";
		$ACT_q=mysql_query("SELECT * FROM `mod_art_cat_list` WHERE `id` = '".$id."'");
		if($ACT_r = mysql_fetch_array($ACT_q))
			$result = $ACT_r[title];
			
		return $result;
	}

	/* Articles End */
	/* Formation functions begin */
	function GetFormationsCatalogs(){
	global $lang,$idel,$iedit,$actId,$modId;
	$return="";
	$ACT_q=mysql_query("SELECT * FROM `mod_formation_list` ORDER BY `order`");// ORDER BY `order`
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$GLOBALS["count"]++;
		$row=$GLOBALS["count"]%2;
		$padding=0;
	$return.= <<<html
		<tr class="row{$row}">
			<td>{$ACT_r[id]}</td>
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">&nbsp;<input type="text" class="order_input" name="order[{$ACT_r["id"]}]" value="{$ACT_r["order"]}" />&nbsp;{$ACT_r[title]}&nbsp;(<a href="admin.php?act={$actId}&mod={$modId}&op=index_teztzura&id={$ACT_r[id]}">{$lang["formations_list"]}</a>)</td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit_form_cat&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_form_cat&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_form_cat&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
	}
	return $return;
}	

	function GetFormationCatalogName($id){
		$result = "";
		$ACT_q=mysql_query("SELECT * FROM `mod_formation_list` WHERE `id` = '".$id."'");
		if($ACT_r = mysql_fetch_array($ACT_q))
			$result = $ACT_r[title];
			
		return $result;
	}



	function GetFormationCategories($catalog_id = 0,$parent=0,$i=0,$count=0){
	global $count,$lang,$idel,$iedit,$actId,$modId;
	$count = 0;
	$output = "";
	$ACT_q=mysql_query("SELECT * FROM `mod_tetzs_list` WHERE `catalog_id` = '".$catalog_id."'") or die(mysql_error());
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$row=$count++%2;
		$padding=$i*12;
	$output .= <<<html
		<tr class="row{$row}">
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">{$ACT_r[title]}</td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit_teztzura&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_teztzura&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_teztzura&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
		//$output .= GetCategories($catalog_id,$ACT_r["id"],$i+1);
	}
	
	return 	$output;
}	
	/* Formation functions end */
	
	
	/* Catalog functions begin */
	
	function GetCatalogs(){
	global $lang,$idel,$iedit,$actId,$modId,$igo;
	$return="";
	$ACT_q=mysql_query("SELECT * FROM `mod_catalogs_list` ORDER BY `order`");// ORDER BY `order`
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$cats_num = GetCatsNum($ACT_r[id]);
		$GLOBALS["count"]++;
		$row=$GLOBALS["count"]%2;
		$padding=0;
	$return.= <<<html
		<tr class="row{$row}">
			<td>{$ACT_r[id]}</td>
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">&nbsp;<input type="text" class="order_input" name="order[{$ACT_r["id"]}]" value="{$ACT_r["order"]}" />&nbsp;{$ACT_r[title]}&nbsp;(<a href="admin.php?act={$actId}&mod={$modId}&op=index_categories&id={$ACT_r[id]}">{$lang["categories_list"]} ($cats_num)</a>)</td>
			<td><a href=",cats_catalog,{$ACT_r[id]}" target="_blank">{$igo}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
	}
	return $return;
}
	
	function GetCatsNum($catalog_id){
		$result = 0;
		$ACT_q=mysql_query("SELECT COUNT(*) AS num FROM `mod_category_list` WHERE  `catalog_id` = '".$catalog_id."'");
		
		$ACT_r = mysql_fetch_array($ACT_q);
		
		return $ACT_r[num];
	}
	

	function GetCatalogName($id){
		$result = "";
		$ACT_q=mysql_query("SELECT * FROM `mod_catalogs_list` WHERE `id` = '".$id."'");
		if($ACT_r = mysql_fetch_array($ACT_q))
			$result = $ACT_r[title];
			
		return $result;
	}
	
	/* Catalog functions end */
	
	/* Category functions begin */
	
	
		
	function GetCategories($catalog_id = 0,$parent=0,$i=0,$count=0){
	global $count,$lang,$idel,$iedit,$actId,$modId,$igo;
	
	$output = "";

	$ACT_q=mysql_query("SELECT * FROM `mod_category_list` WHERE `parent`='".$parent."' AND `catalog_id` = '".$catalog_id."' ORDER BY `order`");
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$cat_info = GetCatInfo($ACT_r[id]);
		$row=$count++%2;
		$padding=$i*12;
	$output .= <<<html
		<tr class="row{$row}">
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">&nbsp;<input type="text" class="order_input" name="order[{$ACT_r["id"]}]" value="{$ACT_r["order"]}" />&nbsp;{$ACT_r[title]}&nbsp;(<a href="admin.php?act={$actId}&mod={$modId}&op=index_kind&id={$ACT_r[id]}">{$lang["products_list"]} ($cat_info[num])</a>)</td>
			<td><a href=",cats_catalog,cat_{$ACT_r[id]}" target="_blank">{$igo}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=edit_category&id={$ACT_r[id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_category&id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op=del_category&id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
		$output .= GetCategories($catalog_id,$ACT_r["id"],$i+1);
	}
	
	return 	$output;
}
	
	function CreateCatsSelect($self = 0,$parent_catalog=0,$parent=0,$i=0,$chosen=0,$limit=false){
	global $lang;
	$ACT_q=mysql_query("SELECT * FROM `mod_category_list` WHERE `catalog_id`='".$parent_catalog."' AND `parent`='".$parent."'");
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		if($self != $ACT_r["id"]) {  
		$padding=str_repeat("-",$i);
		$ch=$ACT_r["id"]==$chosen?" selected=\"selected\"":"";
	echo <<<html
		<option value="{$ACT_r["id"]}"{$ch}>{$padding}{$ACT_r["title"]}</option>

html;
	}
	if(!($i+1>$limit && $limit !==false))
		CreateCatsSelect($self,$parent_catalog,$ACT_r["id"],$i+1,$chosen,$limit);
	}	
}

	/*  Category functions end  */
	
	/* Products function begin */
	
	function GetAdditionalFormationKindsTemplateD ($formKinds,$r,$mce_suffix) {
	global $lang;
	$skip_arr = array("dnote" , "title1" , "title2", "title3", "title4", "title5", "image1", "image2", "image3", "image4", "image5");
	
	if(empty($formKinds[values_arr])) return;
	
	
	
	
	
	foreach($formKinds[values_arr] as $key => $value){
		if(1 == $key) continue;
		
		if(in_array($key,$skip_arr)) continue;
		
		
	?>
     
    <table id="tetz_<?=$r["id"];?>_<?=$key?>" width="100%" style="padding-bottom:30px; margin-bottom:30px;border-bottom: solid #CCC;">
			<tr>
            	<td colspan="3">
                	<input type="button" value="<?=$lang["remove_formation_kind"];?>" class="button orange" onclick="removeFormKindTable('tetz_<?=$r["id"];?>_<?=$key?>')" />
                </td>
            </tr>
			<tr>	
								<td width="80" valign="top"><strong><?=$lang["kind_teken"];?>:</strong></td>
								<td>
									<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][teken]" type="text"  class="text" value="<?=htmlspecialchars($value["teken"]);?>" />
								</td>
								<td></td>
							</tr>
							
							<tr>	
								<td width="80" valign="top"><strong><?=$lang["kind_homer"];?>:</strong></td>
								<td>
									<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][homer]" type="text"  class="text" value="<?=htmlspecialchars($value["homer"]);?>" />
								</td>
								<td></td>
							</tr>
							
							<tr>	
								<td width="80" valign="top"><strong><?=$lang["kind_tavrig"];?>:</strong></td>
								<td>
									<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][tavrig]" type="text"  class="text" value="<?=htmlspecialchars($value["tavrig"]);?>" />
								</td>
								<td></td>
							</tr>
							<tr>	
								<td width="80" valign="top"><strong><?=$lang["kind_hozek"];?>:</strong></td>
								<td>
									<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][hozek]" type="text"  class="text" value="<?=htmlspecialchars($value["hozek"]);?>" />
								</td>
								<td></td>
							</tr>
							
							
							<tr>
								<td width="80" valign="top"><strong><?=$lang["kind_measured_in"];?>:</strong></td>
								<td>
									<input type="radio" name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][measured]" value="<?=$lang["kind_mm"]?>" <?=($value["measured"] == $lang["kind_mm"]? "checked":"")?>> <?=$lang["kind_mm"]?>
									<input type="radio" name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][measured]" value="<?=$lang["kind_intch"]?>" <?=($value["measured"] == $lang["kind_intch"]? "checked":"")?>> <?=$lang["kind_intch"]?>
								</td>
								<td></td>
							</tr>
							<tr>	
								<td width="80" valign="top"><strong><?=$lang["kind_sizes"];?>:</strong></td>
								<td>
									<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][sizes]" type="text"  class="text" value="<?=htmlspecialchars($value["sizes"]);?>" />
								</td>
								<td></td>
							</tr>
							<tr>
								<td width="80"><strong> <?=$lang["kind_makat"];?>:</strong></td>
								<td><input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][makat]" type="text" class="text" value="<?=htmlspecialchars($value["makat"]);?>" /></td>
								<td></td>
							</tr>							
        </table>
    <?
		}
	}
	function GetAdditionalFormationKinds($formKinds,$r,$mce_suffix) {
	global $lang;

	if(empty($formKinds[values_arr])) return;
	
	foreach($formKinds[values_arr] as $key => $value){
		if(1 == $key) continue;
		
		if(!intval($key)) continue;
	
	?>
     
    <table id="tetz_<?=$r["id"];?>_<?=$key?>" width="100%" style="padding-bottom:30px; margin-bottom:30px;border-bottom: solid #CCC;">
			<tr>
            	<td colspan="3">
                	<input type="button" value="<?=$lang["remove_formation_kind"];?>" class="button orange" onclick="removeFormKindTable('tetz_<?=$r["id"];?>_<?=$key?>')" />
                </td>
            </tr>
			<tr>	
				<td width="80" valign="top"><strong><?=$lang["kind_desc"];?>:</strong></td>
				<td>
					<input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][desc]" type="text"  class="text" value="<?=htmlspecialchars($value["desc"]);?>" />
				</td>
				<td></td>
			</tr>
            <tr>
                <td width="80" valign="top"><strong><?=$lang["kind_sizes"];?>:</strong></td>
                <td>
                    <input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][mm]" type="text" style="width:93px;" class="text" value="<?=htmlspecialchars($value["mm"]);?>" />
                </td>
                <td></td>
            </tr>							
            <tr>
                <td width="80"><strong>* <?=$lang["kind_makat"];?>:</strong></td>
                <td><input name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][makat]" type="text" class="text" value="<?=htmlspecialchars($value["makat"]);?>" /></td>
                <td></td>
            </tr>
            <tr>
                <td width="80" valign="top"><strong>* <?=$lang["kind_note"];?>:</strong></td>
                <td><textarea class="editor_<?=$mce_suffix;?>" name="tetz[<?=$r["id"];?>][values_arr][<?=$key?>][note]" cols="30" rows="5"><?=htmlspecialchars($value["note"]);?></textarea></td>
                <td></td>
            </tr>							
        </table>
    <?
		}
	}
	
	function CreateExistingFormationsObject($numId,$lang_edit){
			$Vtetz = array();

		$get1_q=mysql_query("SELECT * FROM `mod_mat_tetz` WHERE `kind_id`='$numId' AND `lang_id`='".$lang_edit."' ORDER BY `tetz_id`");
				$last_formation_id = -1;
				$iteration_counter = 1;
				while($get1_r=mysql_fetch_array($get1_q)){
					if($get1_r["tetz_id"] != $last_formation_id){
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][dnote] = $get1_r[dnote];
						
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title1] = $get1_r[title1];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title2] = $get1_r[title2];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title3] = $get1_r[title3];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title4] = $get1_r[title4];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title5] = $get1_r[title5];
						
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][1]=$get1_r;
						$Vtetz[$get1_r["tetz_id"]]["id"]=1;
						
						$iteration_counter = 1;
					} else if($get1_r["tetz_id"] == $last_formation_id) {
						$iteration_counter++;
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][dnote] = $get1_r[dnote];
						
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title1] = $get1_r[title1];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title2] = $get1_r[title2];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title3] = $get1_r[title3];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title4] = $get1_r[title4];
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][title5] = $get1_r[title5];
						
						
						$Vtetz[$get1_r["tetz_id"]]["values_arr"][$iteration_counter]=$get1_r;
					}
					
					$last_formation_id = $get1_r["tetz_id"];
				}
			return	$Vtetz;
	}
	
	function CreateFormationsDropDown($numId , $Vtetz) {
		$output = "";
		$output.= "<select id=\"form_select\" name=\"formations_select\">";
		$output.= "<option value=\"formations_container_-1\">תצורות של מוצר";
		$formations_catalog_ids = GetFormationsCatalogsIds();
		foreach($formations_catalog_ids as $catalog){
			$output.= "<option value=\"formations_container_$catalog[id]\">$catalog[title]";
		}
		$output.= "</select>";
		
		return $output;
		
	}
	
	function CreateFormationsAccordeon($numId , $Vtetz) {
	
		$output = "";
		
		$output.= CreateFormationsDropDown($numId , $Vtetz);
		
		$output .= CreateCurrentFormationsListDiv($numId , $Vtetz);
		
		$formations_catalog_ids = GetFormationsCatalogsIds();
		foreach($formations_catalog_ids as $catalog){
			$output .= CreateFormationDiv($catalog,$Vtetz);
		}
		
		
		return $output;
		//$output.= "<input type=\"button\" value=\"$lang[seo]\" class=\"button orange\" onclick=\"showFormationsAccordeon();\" />";
		
		$output.= "<div id=\"accordion\" style=\"margin-top:5px;\">";
		
		
		$output .= CreateCurrentFormationsList($numId , $Vtetz);


		$formations_catalog_ids = GetFormationsCatalogsIds();
		foreach($formations_catalog_ids as $catalog){
			$output .= AccordeonFromCatalog($catalog,$Vtetz);
		}
		
		$output .= "</div>";

		return $output;
	}
	
	function CreateFormationDiv($catalog,$Vtetz){
		$output = "";
		
		$q=mysql_query("SELECT * FROM `mod_tetzs_list` WHERE `catalog_id` = '$catalog[id]'");
		if(!mysql_num_rows($q))
			return $output;
			$output .= <<< html
			
			<div id="formations_container_$catalog[id]" class="formations_container">
html;
		
		$counter = 0;
		while($r=mysql_fetch_array($q)){
			if(1 == $Vtetz[$r["id"]]["id"]) continue;
			
			$counter++;
			$output .= <<< html
			                
        <span class="tetz">
            <input type="checkbox" name="tetz[{$r[id]}][id]" value="1" />{$r["title"]}
            <a href="{$r[id]}" class="{$r[id]}"  style="display:none">(הצג מידע)</a>
            <span style="display:none"></span>
        </span>

html;
		
		}
		
		if(!$counter){
			$output .= "<strong>אין תצורות לקטלוג או שכולן כבר שייכות למוצר</strong>";
		}
		$output .= "</div>";		
		return $output;
	}
	
	function CreateCurrentFormationsListDiv($numId , $Vtetz) {

		$output = "";
			
		$q=mysql_query("SELECT * FROM `mod_tetzs_list`");
		
		
		$output .= <<< html
					<div id="formations_container_-1" class="formations_container">
html;
		
		$counter = 0;
		while($r=mysql_fetch_array($q)){
			if(1 !=  $Vtetz[$r["id"]]["id"]) continue;
			$counter++;
			$output .= <<< html
			
			<span class="tetz">
				<input type="checkbox" name="tetz[{$r[id]}][id]" value="1"  checked="checked"/>{$r[title]}
				<a href="{$r["id"]}" class="{$r[id]}" >(הצג מידע)</a>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			</span>
html;
		
		}
		if(!$counter){
			$output .= "<strong>אין תצורות</strong>";
		}
		$output .= "</div>";
	
		return $output;
	}
	
	function AccordeonFromCatalog($catalog,$Vtetz){
		$output = "";
		
		$q=mysql_query("SELECT * FROM `mod_tetzs_list` WHERE `catalog_id` = '$catalog[id]'");
		if(!mysql_num_rows($q))
			return $output;
			$output .= <<< html
			
			<div class="toggler">
				<div class="title">$catalog[title]</div>
            </div>
			<div class="element" >
html;
		
		$counter = 0;
		while($r=mysql_fetch_array($q)){
			if(1 == $Vtetz[$r["id"]]["id"]) continue;
			
			$counter++;
			$output .= <<< html
			                
        <span class="tetz">
            <input type="checkbox" name="tetz[{$r[id]}][id]" value="1" />{$r["title"]}
            <a href="{$r[id]}" class="{$r[id]}"  style="display:none">(הצג מידע)</a>
            <span style="display:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        </span>

html;
		
		}
		
		if(!$counter){
			$output .= "<strong>אין תצורות לקטלוג או שכולן כבר שייכות למוצר</strong>";
		}
		$output .= "</div>";		
		return $output;
	}
	
	function GetFormationsCatalogsIds() {
		$result = array();
		$q=mysql_query("SELECT * FROM `mod_formation_list` ORDER BY `order`") or die(mysql_error());
		while($r=mysql_fetch_array($q)){
			$result[] = $r;
		}
		
		return $result;
	}
	
	
	function CreateCurrentFormationsList($numId , $Vtetz) {

		$output = "";
			
		$q=mysql_query("SELECT * FROM `mod_tetzs_list`");
		
		
		$output .= <<< html
					<div class="toggler">
				<div class="title">תצורות של מוצר</div>
            </div>
			<div class="element" >
html;
		
		$counter = 0;
		while($r=mysql_fetch_array($q)){
			if(1 !=  $Vtetz[$r["id"]]["id"]) continue;
			$counter++;
			$output .= <<< html
			
			<span class="tetz">
				<input type="checkbox" name="tetz[{$r[id]}][id]" value="1"  checked="checked"/>{$r[title]}
				<a href="{$r["id"]}" class="{$r[id]}" >(הצג מידע)</a>
				<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			</span>
html;
		
		}
		if(!$counter){
			$output .= "<strong>אין תצורות</strong>";
		}
		$output .= "</div>";
	
		return $output;
	}
	
	function GetCatInfo($category_id) {
	
		$result = array();
	
		$ACT_q=mysql_query("SELECT CTP.* , CAT.`title` 
								FROM `category_template_product` CTP
								INNER JOIN `mod_category_list` CAT
								ON CAT.`id` = CTP.`category_id`
								WHERE CTP.`category_id` = $category_id") or die(mysql_error());
								
		$num 	= mysql_num_rows($ACT_q);						
		$ACT_r = mysql_fetch_array($ACT_q);
		
		$result[num]	= $num;
		$result[title]	= $ACT_r[title];
		return 	$result;
		
	}
	
	function GetCategoryProductsClient($category_id ,$lang) {
		 $return=array();
		 $products_actions_hush = GetProdTemplatesHash();

		$ACT_q=mysql_query("SELECT MK.`plink`, CTP.`prod_template_id` , MK.`title` AS prod_title , CTP.`prod_id`
							FROM `category_template_product` CTP
							INNER JOIN `mod_materials_kind` MK
							ON MK.`kind_id` = CTP.`prod_id`
							WHERE 
							MK.`lang_id` =  $lang
							AND
							CTP.`category_id` = $category_id
							ORDER BY CTP.`order`");
		
		while($ACT_r = @mysql_fetch_array($ACT_q)) {
			 $product 			= array();
			 $product[id] 		= $ACT_r[prod_id];
			 $product[action] 	= $products_actions_hush[$ACT_r[prod_template_id]][index];
			 $product[title]	= $ACT_r[prod_title];
			 $product[plink]	= $ACT_r[plink];
			 
			 
			 $return[]		= $product;	
	}
	return $return;							
	 }
	
	function GetFormationNotes($notes){
		$output = "";

			if(empty($notes)) return $output;
			
			$output .= "<tr>";
			$output .= "<td class=\"cell\"><strong><% LANG_NOTE %></strong></td> ";
			$output .= "<td colspan=\"7\" class=\"cell\" width=\"65%\">";
			$output .= $notes;
			$output .= "</td>";
			$output .= "</tr>";
			
		
		
		return $output;
	}
	
	function GetFormationPicturesTemplateC($arr){
		$pics_arr = array();
		$FORM_IMG_PATH ="../files/tetzimages/";
		/*
		$GET_MAT_q = mysql_query("SELECT L1.`image1`,L1.`image2`,L1.`image3`,L1.`image4`,L1.`image5` FROM
					 `mod_tetzs_list` L1 
					WHERE L1.`id`='".$get_id."'") or die(mysql_error());
		if($ACT_r = mysql_fetch_assoc($GET_MAT_q)){
			foreach($ACT_r as $label => $pic){
					if(!empty($pic))
						$pics_arr[] = $FORM_IMG_PATH.$pic;
			}
		}
		*/
		foreach($arr as $label => $pic){
			if(preg_match("/image[1-5]/", $label)){
				if(!empty($pic)){
						$title = preg_replace('/image(.)/', 'title$1', $label);
						$pics_arr[] = array("image" => $FORM_IMG_PATH.$pic,
											"title" => htmlspecialchars($arr[$title]));
				}
			}
		}
		
		$output = TRfromArrTemplateC($pics_arr);
		return $output;
	}
	
	function GetFormationPictures($get_id){
		$pics_arr = array();
		$FORM_IMG_PATH ="files/tetzimages/";
		$GET_MAT_q = mysql_query("SELECT * FROM `mod_tetzs_list` WHERE `id`='".$get_id."'") or die(mysql_error());
		
		if($ACT_r = mysql_fetch_array($GET_MAT_q)){
			print(print_r($ACT_r));
			foreach($ACT_r as $label => $pic){
					if(!empty($pic)){
						$title = preg_replace('/image(.)/', 'title$1', $label);
						$pics_arr[] = array("image" => $FORM_IMG_PATH.$pic,
											"title" => $arr[$title]);
					}
			}
		}
		
		$output = TRfromArr($pics_arr);
		return $output;
	}
	
	function CreateFormImgsArr($numId,$Plang){
		$result = array();
		$prods_formations_query = mysql_query("SELECT * FROM `mod_mat_tetz` WHERE `kind_id`='$numId' AND `lang_id`='".$Plang."'");
		while($prods_formations = mysql_fetch_array($prods_formations_query)){
			$images_arr = array( "image1" => $prods_formations[image1],
								"image2" => $prods_formations[image2],
								"image3" => $prods_formations[image3],
								"image4" => $prods_formations[image4],
								"image5" => $prods_formations[image5],);
			$result[$prods_formations[kind_id]."_".$prods_formations[tetz_id]."_".$prods_formations[tetz_item_id]."_".$prods_formations[lang_id]] = $images_arr;
		}
		
		return $result;
	}
	
	function TRfromArrTemplateC($pics_arr){
		$output = "";
		$flag = false;
		foreach($pics_arr as $pic){
			if(!empty($pic[image]))
				$flag = true;
		}
		if(!$flag) return $output;
		if(!sizeof($pics_arr)) return $output;
		$output .= "<tr>";
		$output .= "<td class=\"cell\" valign=\"center\"><strong><% LANG_ClickToLarge %></strong></td> ";
		$output .= "<td colspan=\"4\" class=\"cell\" valign=\"top\">";
		$output .= "<div class=\"image_container\">";
		$output .= "<table style=\"width:330px;height:auto;\">";
		$output .= "<tr>";
		
		foreach($pics_arr as $pic){
			$output .= "<td>";
			$output .= "<a href=\"$pic[image]\" target=\"_blank\"><img src=\"$pic[image]\" class=\"formation_image\" alt=\"$pic[title]\" title=\"$pic[title]\" /></a>";
			$output .= "</td>";
		}
		$output .= "</tr>";
		$output .= "</table>";
		$output .= "</div>";
		$output .= "</td>";
		$output .= "</tr>";
		
		return $output;
	}

	function TRfromArr($pics_arr){
		$output = "";
		$flag = false;
		foreach($pics_arr as $pic){
			if(!empty($pic))
				$flag = true;
		}
		if(!$flag) return $output;
		if(!sizeof($pics_arr)) return $output;
		$output .= "<tr>";
		$output .= "<td class=\"cell\" valign=\"center\">תמונות</td> ";
		$output .= "<td class=\"cell\" valign=\"top\">";
		$output .= "<div class=\"image_container\">";
		foreach($pics_arr as $pic){
			$output .= "<a href=\"$pic\" target=\"_blank\"><img src=\"http://".$_SERVER['SERVER_NAME']."/$pic\" class=\"formation_image\" class=\"formation_image\" alt=\"$pic[title]\" title=\"$pic[title]\" /></a>";
		}
		$output .= "</div>";
		$output .= "</td>";
		$output .= "</tr>";
		
		return $output;
	}

	function TRfromArrq($pics_arr){
		$output = "";
		$flag = false;
		foreach($pics_arr as $pic){
			if(!empty($pic))
				$flag = true;
		}
		if(!$flag) return $output;
		if(!sizeof($pics_arr)) return $output;
		$output .= "<tr>";
		$output .= "<td class=\"cell\" valign=\"center\"><strong><% LANG_ClickToLarge %></strong></td> ";
		$output .= "<td class=\"cell\" valign=\"top\">";
		$output .= "<div class=\"image_container\">";
		$alt_arr = array(
		htmlspecialchars ($pics_arr[0])
		,
		htmlspecialchars ($pics_arr[1])
		,
		htmlspecialchars ($pics_arr[2])
		,
		htmlspecialchars ($pics_arr[3])
		,
		htmlspecialchars ($pics_arr[4])
		
		);
		$pics_arr = array($pics_arr[5],$pics_arr[6],$pics_arr[7],$pics_arr[8],$pics_arr[9]);
		$i =0;
		foreach($pics_arr as $pic){
			if (!empty($pic))
				$output .= "<a href=\"http://".$_SERVER['SERVER_NAME']."/files/tetzimages/$pic\" target=\"_blank\"><img src=\"http://".$_SERVER['SERVER_NAME']."/files/tetzimages/$pic\" class=\"formation_image\" class=\"formation_image\" alt=\"$alt_arr[$i]\" title=\"$alt_arr[$i]\" /></a>";
		$i++;
		}
		$output .= "</div>";
		$output .= "</td>";
		$output .= "</tr>";
		
		return $output;
	}
	
	function GetProductsFormations($get_id,$LANG){
		$GET_MAT_q = mysql_query("SELECT DISTINCT T.`kind_id` AS assit ,  T.*,L.`title`,L1.`image` FROM
					`mod_mat_tetz` T , `mod_tetzs` L ,`mod_tetzs_list` L1 
					WHERE T.`tetz_id`=L.`tetz_id` AND T.`kind_id`='".$get_id."' AND L1.`id`=L.`tetz_id` AND T.`lang_id`=".$LANG." AND L.`lang_id`=".$LANG." GROUP BY T.`tetz_id`") or die(mysql_error());
				
		return 	$GET_MAT_q;	
	}
	function GetFormationKinds($get_id,$LANG,$tetz_id) {
		$GET_MAT_q = mysql_query("SELECT DISTINCT T.`kind_id` AS assit ,  T.*,L.`title`,L1.`image` FROM
					`mod_mat_tetz` T , `mod_tetzs` L ,`mod_tetzs_list` L1 
					WHERE T.`tetz_id`=L.`tetz_id` AND T.`kind_id`='".$get_id."' AND T.`tetz_id`='".$tetz_id."'  AND L1.`id`=L.`tetz_id` AND T.`lang_id`=".$LANG." AND L.`lang_id`=".$LANG) or die(mysql_error());
				
		return 	$GET_MAT_q;
	}
	
	 function GetCategoryProducts($category_id) {
		 global $lang,$idel,$iedit,$actId,$modId,$igo;
		 $return="";
		 
		 	$products_actions_hush = GetProdTemplatesHash();
			$ACT_q=mysql_query("SELECT * 
								FROM `category_template_product`
								WHERE `category_id` = $category_id
								ORDER BY `order`");
		
		while($ACT_r = mysql_fetch_array($ACT_q)) {
		$GLOBALS["count"]++;
		$row=$GLOBALS["count"]%2;
		$padding=0;
	$return.= <<<html
		<tr class="row{$row}">
			<td class="left" style="padding-{$lang["left"]}:{$padding}px">&nbsp;<input type="text" class="order_input" name="order[{$ACT_r["id"]}]" value="{$ACT_r["order"]}" />&nbsp;{$ACT_r[prod_title]}</td>
			<td><a href=",cats_catalog,{$products_actions_hush[$ACT_r[prod_template_id]][index]}_{$ACT_r[prod_id]}" target="_blank">{$igo}</a></td>			
			<td><a href="admin.php?act={$actId}&mod={$modId}&op={$products_actions_hush[$ACT_r[prod_template_id]][edit]}&id={$ACT_r[prod_id]}">{$iedit}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op={$products_actions_hush[$ACT_r[prod_template_id]][del]}&id={$ACT_r[prod_id]}&prod_cat_tpl_id={$ACT_r[id]}" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
			<td><a href="admin.php?act={$actId}&mod={$modId}&op={$products_actions_hush[$ACT_r[prod_template_id]][del]}&id={$ACT_r[prod_id]}&prod_cat_tpl_id={$ACT_r[id]}&rdel=1" onclick="if(confirm('{$lang['realdelete']}')){return true}else{return false};">{$idel}</a></td>
		</tr>

html;
	}
	return $return;						
							
	 	
	 }
	 
	 function GetProdTemplatesHash() {
	 	$result;
	 	$ACT_q=mysql_query("SELECT * 
								FROM `prod_templates`");
								
		while($ACT_r = mysql_fetch_array($ACT_q)) {	
			$result[$ACT_r[id]]['index'] = $ACT_r[index_action];
			$result[$ACT_r[id]]['add'] = $ACT_r[add_action];
			$result[$ACT_r[id]]['edit'] = $ACT_r[edit_action];
			$result[$ACT_r[id]]['del'] = $ACT_r[del_action];
			$result[$ACT_r[id]]['title'] = $ACT_r[title];
		}
		
		return $result;					
	 }
	 
	 function CreateAddProdSelect() {
	 
	 	$output = "";
		$products_actions_hush = GetProdTemplatesHash();
		$output .= "<select onchange='AddProduct(this);'>";
		$output .= "<option value=\"\">בחר תבנית";
		$output .= "<option value=\"add_c\">הוסף מוצר";
		
		foreach($products_actions_hush as $template_id => $template_value) {
			
			//$output .= "<option value=\"$template_value[add]\">$template_value[title]";
			
		}

		$output .= "</select>";
	 return $output;
	 }
	 	
	 
	 /* Products funcion end */
	 
	 /* Cart page begin */
	function  GetProdItemInfoSingleForm($x){
	
		$prod_info_query = mysql_query("
			SELECT `MK`.`kind_id` , 
					`MKL`.`mat_id` , 
					`MK`.`title`,
					CAT.`title` AS cat_title
			FROM 		`mod_materials_kind_list` MKL 
			INNER JOIN	`mod_materials_kind` MK
			ON 			MKL.`id` = MK.`kind_id`
			INNER JOIN `mod_category` CAT
			ON 			CAT.`category_id` = MKL.`mat_id`
			WHERE  MK.`kind_id` = '{$x['pid']}' 
					AND MK.`lang_id` = '" . LANG . "'
					AND CAT.`lang_id` = '" . LANG . "'
			");
		$prod_info_arr 	= mysql_fetch_assoc($prod_info_query);
		
		$prod_form_query = mysql_query("SELECT `title` AS formation_title
				FROM `mod_tetzs`
				WHERE `tetz_id` = '{$x['item_id']}' AND `lang_id`=".LANG);
				
		$prod_form_arr 	= mysql_fetch_assoc($prod_form_query);
		
		$prod_info_arr[formation_title] = $prod_form_arr[formation_title];
		
		$prod_makat_query = mysql_query("SELECT `makat` 
				FROM `mod_mat_tetz`
				WHERE `tetz_id` = '{$x['item_id']}' AND `tetz_item_id`= '{$x['tetz_item_id']}' AND `kind_id`= '{$x['pid']}'");
		$prod_makat_arr 	= mysql_fetch_assoc($prod_makat_query);
		$prod_info_arr[makat] = $prod_makat_arr[makat];
		
		$prod_mm_query = mysql_query("SELECT `mm` 
				FROM `mod_mat_tetz`
				WHERE `tetz_id` = '{$x['item_id']}' AND `tetz_item_id`= '{$x['tetz_item_id']}' AND `kind_id`= '{$x['pid']}'");
		$prod_mm_arr 	= mysql_fetch_assoc($prod_mm_query);
		$prod_info_arr[mm] = $prod_size_arr[mm];
		
		
				$prod_inch_query = mysql_query("SELECT `inch` 
				FROM `mod_mat_tetz`
				WHERE `tetz_id` = '{$x['item_id']}' AND `tetz_item_id`= '{$x['tetz_item_id']}' AND `kind_id`= '{$x['pid']}'");
		$prod_inch_arr 	= mysql_fetch_assoc($prod_inch_query);
		$prod_info_arr[inch] = $prod_size_arr[inch];
		
		return $prod_info_arr;
	}
	
	function  GetProdItemInfoMultiForm($x){
	
		$prod_info_query = mysql_query("
			SELECT `MK`.`kind_id` , 
					`MKL`.`mat_id` , 
					`MK`.`title`,
					CAT.`title` AS cat_title
			FROM 		`mod_materials_kind_list` MKL 
			INNER JOIN	`mod_materials_kind` MK
			ON 			MKL.`id` = MK.`kind_id`
			INNER JOIN `mod_category` CAT
			ON 			CAT.`category_id` = MKL.`mat_id`
			WHERE  MK.`kind_id` = '{$x['pid']}' 
					AND MK.`lang_id` = '" . LANG . "'
					AND CAT.`lang_id` = '" . LANG . "'
			");
		$prod_info_arr 	= mysql_fetch_assoc($prod_info_query);
		
		$prod_form_query = mysql_query("SELECT `title` AS formation_title
				FROM `mod_tetzs`
				WHERE `tetz_id` = '{$x['item_id']}' AND `lang_id`=".LANG);
		$prod_form_arr 	= mysql_fetch_assoc($prod_form_query);
		
		$prod_info_arr[formation_title] = $prod_form_arr[formation_title];
		
		$prod_form_kind_query = mysql_query("SELECT * 
				FROM `mod_mat_tetz`
				WHERE `tetz_id` = '{$x['item_id']}' AND `tetz_item_id`= '{$x['tetz_item_id']}'  AND `kind_id`= '{$x['pid']}'");
		$prod_form_kind_arr 	= mysql_fetch_assoc($prod_form_kind_query);
		
		if(3 == $x['type'])		
			$prod_info_arr[prod_title] = $prod_form_kind_arr[desc];
		else	
			$prod_info_arr[prod_title] = $prod_form_kind_arr[teken];
			
		$prod_info_arr[makat] = $prod_form_kind_arr[makat];
		
		$prod_info_arr[mm] = $prod_form_kind_arr[mm];
		
		$prod_info_arr[inch] = $prod_form_kind_arr[inch];
	
		
		
		return $prod_info_arr;
	}
	
	function TemplatesAorB($value,$kind_id){
		$q = mysql_query("SELECT T.* FROM
						`mod_mat_tetz` T 
						WHERE T.`kind_id`='".$kind_id."' AND T.`tetz_id`='".$value."' AND T.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());			
		$r=mysql_fetch_array($q);
		$result = 1;
		if($r[desc])
				$result = 3;
		elseif($r[teken])
				$result = 4;
		else
			$result = 1;

		return $result;
	}
	
	function CreateOutputForAandB($tetz_id ,$id){
		$q = mysql_query("SELECT L.`title` ,L1.`id` FROM  `mod_tetzs` L 
				INNER JOIN `mod_tetzs_list` L1 
				ON L1.`id`=L.`tetz_id`
				WHERE  L1.`id`='$tetz_id' AND L.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());
			while($r=mysql_fetch_array($q))
				$options[]=array($r['id'],$r['title']);
			echo "<select id=\"formmodel".$id."\" name=\"formmodel[".$id."]\"  style = \"width: 57px\" >";
			foreach($options AS $value){
				echo "<option id=\"".$value[0]."\" value=\"".htmlspecialchars($value[1])."\">".htmlspecialchars($value[1])."</option>";
			}
			echo "</select>";
	}
	
	function CreateOutputForC($tetz_id ,$id,$kind_id){
			$options=array(
				array("",'בחר'),
				);
		$q = mysql_query("SELECT *  FROM  `mod_mat_tetz` L 
				WHERE  L.`tetz_id`='$tetz_id' AND `kind_id`='$kind_id' AND L.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());
			while($r=mysql_fetch_array($q))
				$options[]=array($r['tetz_item_id'],$r['desc']);
			echo "<select id=\"formmodel".$id."\" name=\"formmodel[".$id."]\" onchange=\"get_makat(this,".$id.",".$tetz_id.",".$kind_id.")\"  style = \"width: 57px\" >";
			foreach($options AS $value){
				echo "<option id=\"".$value[0]."\" value=\"".htmlspecialchars($value[1])."\">".htmlspecialchars($value[1])."</option>";
			}
			echo "</select>";
	}
	
	function CreateOutputForD($tetz_id ,$id,$kind_id){
			$options=array(
				array("",'בחר'),
				);
		$q = mysql_query("SELECT *  FROM  `mod_mat_tetz` L 
				WHERE  L.`tetz_id`='$tetz_id' AND `kind_id`='$kind_id' AND L.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());
			while($r=mysql_fetch_array($q))
				$options[]=array($r['tetz_item_id'],$r['teken']);
			echo "<select id=\"formmodel".$id."\" name=\"formmodel[".$id."]\" onchange=\"get_makat(this,".$id.",".$tetz_id.",".$kind_id.")\"  style = \"width: 57px\" >";
			foreach($options AS $value){
				echo "<option id=\"".$value[0]."\" value=\"".htmlspecialchars($value[1])."\">".htmlspecialchars($value[1])."</option>";
			}
			echo "</select>";
	}

	//todo check lang
	function GetCatNumForAandB($tetz_id,$id,$kind_id){
		$q = mysql_query("SELECT L.`makat`  FROM  `mod_mat_tetz` L 
				WHERE  L.`tetz_id`='$tetz_id' AND `tetz_item_id`=0 AND `kind_id`='$kind_id' AND L.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());
		$r=mysql_fetch_array($q);
				
		echo "<input id=\"catnum$id\" name=\"catnum[$id]\" readonly=\"readonly\" type=\"text\" value=\"$r[makat]\" style = \"width: 60px\">";
		
	}
	
	function GetCatNumForC($tetz_item_id,$id,$prod_id,$tetz_id){
		$q = mysql_query("SELECT L.`makat`  FROM  `mod_mat_tetz` L 
				WHERE  L.`tetz_id`='$tetz_id' AND `tetz_item_id`= '$tetz_item_id' AND `kind_id`='$prod_id' AND L.`lang_id`='".intval($_GET["lang"])."'") or die(mysql_error());
		$r=mysql_fetch_array($q);
		
		echo "<input id=\"catnum$id\" name=\"catnum[$id]\" readonly=\"readonly\" type=\"text\" value=\"$r[makat]\" style = \"width: 60px\">";
	}
	
	
	function EmptyMakat($value,$id,$prod_id){
		echo "<input id=\"catnum$id\" name=\"catnum[$id]\" readonly=\"readonly\" type=\"text\" value=\"\" style = \"width: 60px\">";
	}
	

	
	function ConvertToTRSingleForm($x,$prod_item_info,$row_id){
	$output = "";
	$getsizenow = mysql_query("SELECT * from `mod_mat_tetz` WHERE `tetz_id` = '{$x['item_id']}' AND `tetz_item_id`= '{$x['tetz_item_id']}'  AND `kind_id`= '{$x['pid']}'");
	$getsizenow_q = htmlspecialchars($getsizenow_r);
	$getsizenow_r = mysql_fetch_array($getsizenow);
	
	$output .= <<<HTML
		<tr id = "add{$row_id}">
				<td>
					<select name="what[{$row_id}]" id = "what[{$row_id}]" style = "width: 127px">
						<option value = "1||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['cat_title']}</option>
					</select>
				</td>
				<td id="sec{$row_id}">
					<select name="mat[{$row_id}]" style = "width: 126px"><option value="">{$prod_item_info['title']}</option></select>
				</td>
				<td id="four{$row_id}">
					<select name="model[{$row_id}]" style = "width: 57px"><option value="1||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['formation_title']}</option></select>
				</td>
				<td id="fourr{$row_id}">
					<select name="formmodel[{$row_id}]" style = "width: 57px"><option value="{$prod_item_info['formation_title']}">{$prod_item_info['formation_title']}</option></select>
				</td>
				
				
				
				<td id = "seven{$row_id}"><input type = "text" name = "size[{$row_id}]" value = '{$prod_item_info['mm']}{$prod_item_info['inch']}
				{$getsizenow_r['mm']}
				
				{$getsizenow_r['inch']}' style = "width: 45px" /></td>
				<td id = "five{$row_id}"><input type = "text" name = "count[{$row_id}]" value = "1" style = "width: 25px"/></td>
				<td id = "height{$row_id}"><select name = "type[{$row_id}]" style = "width: 65px">
										<option value = ""><% LANG_CHOOSE %></option>
										<option value = "<% LANG_UNIT %>"><% LANG_UNIT %></option>
										<option value = "<% LANG_kilogram %>"><% LANG_kilogram %></option>
										<option value = "<% LANG_tone %>"><% LANG_tone %></option>
										<option value = "<% LANG_Libra %>"><% LANG_Libra %></option>
										<option value = "<% LANG_Inch %>"><% LANG_Inch %></option>
										<option value = "<% LANG_meter %>"><% LANG_meter %></option>
									</select>
				</td>
				
				<td id="notes_row{$row_id}">
					<input name="notes[{$row_id}]"  type="text" style = "width: 110px">
				</td>
				<td id="catnum_row{$row_id}">
					<input name="catnum[{$row_id}]" readonly="readonly" type="text" value="{$prod_item_info['makat']}" style = "width: 60px">
				</td>
				<td id = "six{$row_id}"><input type = "button" onclick = "remove({$row_id})" value = "<% LANG_DELETE %>" class="button normal" /></td>
			</tr>
HTML;
		return $output;
	}
	
	function ConvertToTRMultiForm($x,$prod_item_info,$row_id){
	$output = "";
		
	$output .= <<<HTML
		<tr id = "add{$row_id}">
				<td>
					<select name="what[{$row_id}]" id = "what[{$row_id}]" style = "width: 127px">
						<option value = "4||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['cat_title']}</option>
					</select>
				</td>
				<td id="sec{$row_id}">
					<select name="mat[{$row_id}]" style = "width: 126px"><option value="">{$prod_item_info['title']}</option></select>
				</td>
				<td id="four{$row_id}">
					<select name="model[{$row_id}]" style = "width: 57px"><option value="1||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['formation_title']}</option></select>
				</td>
				<td id="fourr{$row_id}">
					<select name="formmodel[{$row_id}]" style = "width: 57px"><option value="{$prod_item_info['prod_title']}">{$prod_item_info['prod_title']}</option></select>
				</td>
				<td id = "seven{$row_id}"><input type = "text" name = "size[{$row_id}]" value = "{$prod_item_info['mm']}{$prod_item_info['inch']}
				{$getsizenow_r['mm']}{$getsizenow_r['inch']}" style = "width: 45px" /></td>
				<td id = "five{$row_id}"><input type = "text" name = "count[{$row_id}]" value = "1" style = "width: 25px"/></td>
				<td id = "height{$row_id}"><select name = "type[{$row_id}]" style = "width: 65px">
										<option value = ""><% LANG_CHOOSE %></option>
										<option value = "<% LANG_UNIT %>"><% LANG_UNIT %></option>
										<option value = "<% LANG_kilogram %>"><% LANG_kilogram %></option>
										<option value = "<% LANG_tone %>"><% LANG_tone %></option>
										<option value = "<% LANG_Libra %>"><% LANG_Libra %></option>
										<option value = "<% LANG_Inch %>"><% LANG_Inch %></option>
										<option value = "<% LANG_meter %>"><% LANG_meter %></option>
									</select>
				</td>
				
				<td id="notes_row{$row_id}">
					<input name="notes[{$row_id}]"  type="text" style = "width: 110px">
				</td>
				<td id="catnum_row{$row_id}">
					<input name="catnum[{$row_id}]" readonly="readonly" type="text" value="{$prod_item_info['makat']}" style = "width: 60px">
				</td>
				<td id = "six{$row_id}"><input type = "button" onclick = "remove({$row_id})" value = "<% LANG_DELETE %>" class="button normal" /></td>
			</tr>
HTML;
		return $output;
	}
	
	function ConvertToTRMultiForm1($x,$prod_item_info,$row_id){
	$output = "";
		
	$output .= <<<HTML
		<tr id = "add{$row_id}">
				<td>
					<select name="what[{$row_id}]" id = "what[{$row_id}]" style = "width: 127px">
						<option value = "3||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['cat_title']}</option>
					</select>
				</td>
				<td id="sec{$row_id}">
					<select name="mat[{$row_id}]" style = "width: 126px"><option value="">{$prod_item_info['title']}</option></select>
				</td>
				<td id="four{$row_id}">
					<select name="model[{$row_id}]" style = "width: 57px"><option value="1||{$x['item_id']}||{$x['pid']}||{$prod_item_info['mat_id']}">{$prod_item_info['formation_title']}</option></select>
				</td>
				<td id="fourr{$row_id}">
					<select name="formmodel[{$row_id}]" style = "width: 57px"><option value="{$prod_item_info['prod_title']}">{$prod_item_info['prod_title']}</option></select>
				</td>
				<td id = "seven{$row_id}"><input type = "text" name = "size[{$row_id}]" value = "{$prod_item_info['mm']}{$prod_item_info['inch']}
				{$getsizenow_r['mm']}{$getsizenow_r['inch']}" style = "width: 45px" /></td>
				<td id = "five{$row_id}"><input type = "text" name = "count[{$row_id}]" value = "1" style = "width: 25px"/></td>
				<td id = "height{$row_id}"><select name = "type[{$row_id}]" style = "width: 65px">
										<option value = ""><% LANG_CHOOSE %></option>
										<option value = "<% LANG_UNIT %>"><% LANG_UNIT %></option>
										<option value = "<% LANG_kilogram %>"><% LANG_kilogram %></option>
										<option value = "<% LANG_tone %>"><% LANG_tone %></option>
										<option value = "<% LANG_Libra %>"><% LANG_Libra %></option>
										<option value = "<% LANG_Inch %>"><% LANG_Inch %></option>
										<option value = "<% LANG_meter %>"><% LANG_meter %></option>
									</select>
				</td>
				
				<td id="notes_row{$row_id}">
					<input name="notes[{$row_id}]"  type="text" style = "width: 110px">
				</td>
				<td id="catnum_row{$row_id}">
					<input name="catnum[{$row_id}]" readonly="readonly" type="text" value="{$prod_item_info['makat']}" style = "width: 60px">
				</td>
				<td id = "six{$row_id}"><input type = "button" onclick = "remove({$row_id})" value = "<% LANG_DELETE %>" class="button normal" /></td>
			</tr>
HTML;
		return $output;
	}	
	
	function ParseCartProducts($iBasket ,&$i)
	{	
	
		$output = "";
		
		while($x = mysql_fetch_assoc($iBasket))
		{	
			//echo "<script> alert('".$x[type]."'); </script>";
			if($x[type] == 3){
				$prod_item_info = GetProdItemInfoMultiForm($x);
				$output .= ConvertToTRMultiForm1($x,$prod_item_info,$i);
			}
			elseif($x[type] < 2){
				$prod_item_info = GetProdItemInfoSingleForm($x);
				$output .= ConvertToTRSingleForm($x,$prod_item_info,$i);
			} 
			else {
				$prod_item_info = GetProdItemInfoMultiForm($x);
				$output .= ConvertToTRMultiForm($x,$prod_item_info,$i);
			}
			
			$i++;
		}

		
		return $output;
	}
	
	function CreateCatsForHomePage($groupID){
		
	global $lang;	
	
		$query = mysql_query("SELECT HC.* 
									FROM `mod_homepage_cats` HC
									WHERE HC.`group_id` = $groupID 
									ORDER BY HC.`id`
								") or die(mysql_error());
							
		$num_chosen_cats = mysql_num_rows($query);
							
		$i = 0;
		echo "<table width=\"100%\">";
		echo "<tr>";
		echo "<td width=\"25%\">";
		while($arr = mysql_fetch_array($query)){
			$i++;
		echo "<strong> $i</strong> : "; 
		 echo	"<select name=\"group_".$groupID."_".$i."\">";
		 echo "<option value=\"0\">".$lang['cancel'];
				choose_hprocat(0,0,0,array($arr[category_id]));
		 echo	"</select>";
		 echo	"<br />";
		 echo	"<br />";
		 if( 0 == $i % 10){
			echo "</td>";
			if( 20 == $i)
				echo "</tr>";
			else
			 echo "<td width=\"25%\">";
		 }
		}
		
		for(++$i;$i<21;$i++){
			echo "<strong> $i</strong> : "; 
		 echo	"<select name=\"group_".$groupID."_".$i."\">";
		 echo "<option value=\"0\">".$lang['select'];
				choose_hprocat(0,0,0);
		 echo	"</select>";
		 echo	"<br />";
		 echo	"<br />";
		 if( 0 == $i % 10){
			echo "</td>";
			if( 20 == $i)
				echo "</tr>";
			else
			 echo "<td width=\"25%\">";
		 }
		}
		echo "</table>";
		
		
		
	}
	
	function GetHomeCats($LANG){

		$query = mysql_query("SELECT MHC.* , CAT.title FROM mod_homepage_cats MHC
								INNER JOIN mod_category CAT
								ON CAT.category_id = MHC.category_id
								AND CAT.lang_id =".$LANG."
								ORDER BY MHC.group_id , MHC.id ");
		$groups_arr = array();
	
		while($arr = mysql_fetch_array($query)){
			$groups_arr[$arr[group_id]][$arr[id]][id] = $arr[category_id];
			$groups_arr[$arr[group_id]][$arr[id]][title] = $arr[title];
		}
		for($grID = 1 ; $grID < 6 ; $grID++){
		//foreach($groups_arr as $grID => $grVal){	
			
?>
			 <div class="in" style="display:<?=(1==$grID?'block':'none')?>;" id="item<?=$grID?>_menu">
					<?
					if(!empty($groups_arr[$grID])){
						$grVal = $groups_arr[$grID];
					foreach($grVal as $order => $cat_id){				
?>
					<a href=",cats_catalog,cat_<?=$cat_id[id]?>"><span class="hover"><span class="arrow">&nbsp;&nbsp;&nbsp;<?=$cat_id[title];?></span></span></a>
<?
					}
					}
?>
			</div>
<?
		}
		
	}
	
	function choose_hprocat($id=0,$parent=0,$i=0,$chosen=array(),$limit=false){
	global $lang;
	$ACT_q=mysql_query("SELECT * FROM `mod_category_list` WHERE `id` NOT IN(".$id.") AND `parent`=".$parent." ORDER BY `title` ASC") or die(mysql_error());
	while($ACT_r = mysql_fetch_array($ACT_q)) {
		$padding=str_repeat("-",$i);
		$ch=in_array($ACT_r["id"],$chosen)?" selected=\"selected\"":"";
	echo <<<html
		<option value="{$ACT_r["id"]}"{$ch}>{$padding}{$ACT_r["title"]}</option>

html;
	if(!($i+1>$limit && $limit !==false))
		choose_hprocat($id,$ACT_r["id"],$i+1,$chosen,$limit);
	}	
}

	function SelectsToArray($Post_arr){
		$groups_arr = array();
		for($groupsC = 1; $groupsC < 6; $groupsC++){
			$groups_arr[$groupsC] = array();
			for($selectsC = 1; $selectsC < 21; $selectsC++) {
				if(!$Post_arr["group_".$groupsC."_".$selectsC])continue;
				
				$groups_arr[$groupsC][$selectsC] = $Post_arr["group_".$groupsC."_".$selectsC];		
			}
		}
		return $groups_arr;
	}
	/* Cart page end */	
		
	/* Files upload begin */
	
	function GetFilesHash() {
		
		$result = array();
		
		$query = mysql_query("SELECT * FROM `mod_upload` order by name desc");
		while($arr = mysql_fetch_array($query)){
			$result[$arr[name]] = $arr[label];
		}
		return $result;
	}
	
	/* Files upload end */
?> 