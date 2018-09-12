<?php
include_once './crawler.php';
require_once './DB/DBConnection.php';

libxml_use_internal_errors(true);
error_reporting(E_ERROR | E_PARSE);

if (!isset($_GET['url']) || empty($_GET['url'])) {
    echo "wrong url";
}
$url = $_GET['url'];
$crawler = new Crawler();
$dom = $crawler->getPageContent($url);
if(!$dom)
    return 0;
$links = $crawler->getUrls($dom);
$crawler->insertLinks(DBConnection::getDBConnection(), $links);
foreach ($links as $link) {
    $dom = $crawler->getPageContent($link);
    if($dom) {
        $subLinks = $crawler->getUrls($dom);
        $crawler->insertLinks(DBConnection::getDBConnection(), $subLinks);
    }
}

echo "Done";
