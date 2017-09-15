<?php
require 'config.php';
ini_set('display_errors','Off');
$pictinfo=$_REQUEST['pictname'];
$pictname=$_REQUEST['pictname'];
$a=$_REQUEST['a'];
$pictname1=str_replace(' ','_',$pictname);
$pictname1=str_replace('/','-',$pictname1);
#$pictname1=rawurlencode($pictname1);

if($a != ""){$pictname_p=$pictname."-".$a;};


$checkfile="0";
$checkfile1="0";
$checkfile2="0";
$checkfile3="0";
$checkfile_p="0";

if($handle=fopen($config['images']."items/$pictname_p.jpg","r")){$checkfile_p="$pictname_p.jpg";}
if($handle=fopen($config['images']."items/$pictname_p.JPG","r")){$checkfile_p="$pictname_p.JPG";}
if($handle=fopen($config['images']."items/$pictname1.jpg","r")){$checkfile="$pictname1.jpg";}
if($handle=fopen($config['images']."items/$pictname1.JPG","r")){$checkfile="$pictname1.JPG";}
if($handle=fopen($config['images']."items/$pictname1-1.jpg","r")){$checkfile1="$pictname1-1.jpg";}
if($handle=fopen($config['images']."items/$pictname1-1.JPG","r")){$checkfile1="$pictname1-1.JPG";}
if($handle=fopen($config['images']."items/$pictname1-2.jpg","r")){$checkfile2="$pictname1-2.jpg";}
if($handle=fopen($config['images']."items/$pictname1-2.JPG","r")){$checkfile2="$pictname1-2.JPG";}
if($handle=fopen($config['images']."items/$pictname1-3.jpg","r")){$checkfile3="$pictname1-3.jpg";}
if($handle=fopen($config['images']."items/$pictname1-3.JPG","r")){$checkfile3="$pictname1-3.JPG";}

$pictname_p=$checkfile_p;
if($a == ""){$pictname_p = $checkfile;};



echo"<html><title>Просмотр крупного рисунка</title>";
echo"<BODY>";
echo"<table border=\"0\" width=\"100%\"><tr><td colspan=3 align=\"right\">
<form><input type=\"button\" value=\" X \" onClick=\"javascript:hidedet()\"></form>
</td></tr>
<tr><td align=\"center\"><img width=\"600\" height=\"400\" src=\"".$config['httpimages']."items/".$pictname_p."\"></td>";
echo"<td>";
echo"<a href=\"showpict.php?pictname=$pictname1\"><img width=\"160\" height=\"120\" src=\"".$config['httpimages']."items/".$checkfile."\"></a><br>";
if($checkfile1 != "0"){echo"<a href=\"showpict.php?pictname=$pictname1&a=1\"><img width=\"160\" height=\"120\" src=\"".$config['httpimages']."items/".$checkfile1."\"></a><br>";}
if($checkfile2 != "0"){echo"<a href=\"showpict.php?pictname=$pictname1&a=2\"><img width=\"160\" height=\"120\" src=\"".$config['httpimages']."items/".$checkfile2."\"></a><br>";}
if($checkfile3 != "0"){echo"<a href=\"showpict.php?pictname=$pictname1&a=3\"><img width=\"160\" height=\"120\" src=\"".$config['httpimages']."items/".$checkfile3."\"></a><br>";}
echo"</td>";
echo"</tr>
<tr><td height=40>&nbsp;</td></tr>
<tr><td align=center><font size=\"2\">".$pictinfo."</font></td></tr></table>";
echo"</BODY></HTML>";
?>