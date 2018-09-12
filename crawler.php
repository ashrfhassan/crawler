<?php
$main_url="http://samplesite.com";
$str = file_get_contents($main_url);

// Gets Webpage Title
if(strlen($str)>0)
{
    $str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
}

// Gets Webpage Internal Links
$doc = new DOMDocument;
$doc->loadHTML($str);

$items = $doc->getElementsByTagName('a');
foreach($items as $value)
{
    $attrs = $value->attributes;
    $sec_url[]=$attrs->getNamedItem('href')->nodeValue;
}
$all_links=implode(",",$sec_url);

// Store Data In Database
$host="localhost";
$username="root";
$password="";
$databasename="sample";
$connect=mysql_connect($host,$username,$password);
$db=mysql_select_db($databasename);

mysql_query("insert into webpage_details values('$main_url','$title','$description','$all_links')");

?>