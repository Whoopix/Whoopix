<?php
	ob_start();
	session_start();

	require 'composer_vendor/autoload.php';

	/**
	 * Some Settings
	 */
	include("admin/resources/icons.php");
	$usrip = $_SERVER["REMOTE_ADDR"];
	$usrtime = time();
	$usrhour = date('H:i', $usrtime);
	$usrday = date('d.m.Y', $usrtime);
	$usrsite = $_SERVER['HTTP_HOST'];
	$usrpage = $_SERVER['PHP_SELF'];
	if ($_SERVER['QUERY_STRING']) {
		$usrpage .= "?".$_SERVER['QUERY_STRING'];
	}

	/**
	 * MySQL db Connect
	 */
	include("db.php");

	define('IN_SYSTEM',true);

	include 'pdo-connection.php';

	if ($dbnfo[host] && $dbnfo[user] && $dbnfo[pass] && $dbnfo[name]) {
		$link = mysql_connect($dbnfo[host], $dbnfo[user], $dbnfo[pass]) or die(mysql_error());
		mysql_select_db($dbnfo[name], $link) or die(mysql_error());

		mysql_query("SET NAMES 'utf8'");
	}
	$q=mysql_query("SELECT `email` FROM `contact_config` WHERE `id`='1'");
	$r=mysql_fetch_array($q);
	define("SITEDOMAIN","audio-club.co.il");
	define("SALEMAIL",$r["email"]);
	/**
	 * System Basic Functions
	 */
	function iconv2($utf8) {
 	   return preg_replace("/\xD7([\x90-\xAA])/e","chr(ord(\${1})+80)",$utf8);
	}

	function generate_code($chars){
		for($i=0;$i<=($chars-1);$i++){
			$r0 = rand(0,1); $r1 = rand(0,2);
			if ($r0==0){ $r .= chr(rand(ord('A'),ord('Z'))); }
			elseif ($r0==1){ $r .= rand(0,9); }
			if ($r1==0){ $r = strtolower($r); }
		}
		return $r;
	}

	function vldpt($Text) {
		$Text = mysql_escape_string($Text);
		$Text = stripslashes($Text);
		$Text = htmlspecialchars($Text);
		return $Text;
	}


	function permcheck($filename) {
		return substr(sprintf('%o', @fileperms($filename)), -4);
	}


	/**
	 * License Check
	 */
	if ($dbnfo[host] && $dbnfo[user] && $dbnfo[pass] && $dbnfo[name]) {
		$LCS_q = mysql_query("SELECT * FROM `license` WHERE id = 1");

		if (mysql_num_rows($LCS_q)) {
			$LCS_r = mysql_fetch_array($LCS_q);

			$SESSNAME = substr($LCS_r[key], -5, 5);
			$SESSUSER = "User".$SESSNAME;
			$SESSPASS = "Pass".$SESSNAME;

			#$LCS_c = @file_get_contents("http://digitalst.co.il/dst/confirm.php?myKey=".urlencode($LCS_r[key])."&myName=".urlencode($LCS_r[name])."&mySite=".urlencode($usrsite));

			if ($LCS_c == FALSE) {
				$LCS_c = "OK";
			}
		}
		// shay extra
		$LCS_c = "OK";
	}

	$PHONE_PREFS = array( "02" , "03" , "04" , "08" , "09" , "073" , "077" , "072" , "052" , "054" , "050" );


	require_once('functions.inc.php');
	$USER_LOGIN=loggedIn();
//	$lang='english';
?>
