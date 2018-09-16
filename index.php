<?php
include_once './crawler.php';
require_once './DB/DBConnection.php';

libxml_use_internal_errors(true);
error_reporting(E_ERROR | E_PARSE);

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo "wrong url";
}

$testUrl = "https://www.homegate.ch/mieten/108824869";
$url = $_GET['url'];
$crawler = new Crawler();
$dom = $crawler->getPageContent($url);
if (!$dom)
    return 0;
$links = $crawler->getUrls($dom, true);
$crawler->insertLinks(DBConnection::getDBConnection(), $links);

echo "</br><h2>Test url : <p style='color:red'>$testUrl</p></h2></br>";

$dom = $crawler->getPageContent($testUrl);
if (!$dom)
    return 0;
$links = $crawler->getUrls($dom);
$crawler->insertLinks(DBConnection::getDBConnection(), $links);

echo "<center><h2 style='color:red'>Done</h2></center>";
