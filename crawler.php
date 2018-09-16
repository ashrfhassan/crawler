<?php

class Crawler
{

    function getRedirectTarget($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $headers = curl_exec($ch);
        curl_close($ch);
        // Check if there's a Location: header (redirect)
        if (preg_match('/^Location: (.+)$/im', $headers, $matches))
            return trim($matches[1]);
        // If not, there was no redirect so return the original URL
        // (Alternatively change this to return false)
        return $url;
    }

    public function getPageContent($url)
    {
        $url = $this->getRedirectTarget($url);
        fopen("cookies.txt", "w");
        $parts = parse_url($url);
        $host = $parts['host'];
        $ch = curl_init();
        $header = array('GET /1575051 HTTP/1.1',
            "Host: {$host}",
            'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language:en-US,en;q=0.8',
            'Cache-Control:max-age=0',
            'Connection:keep-alive',
            'Host:adfoc.us',
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);

        curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getUrls($dom, $withId = false)
    {
        $doc = new DOMDocument;
        $doc->loadHTML($dom);
        $items = $doc->getElementsByTagName('a');
        $hrefs = [];
        foreach ($items as $value) {
            $attrs = $value->attributes;
            $href = $attrs->getNamedItem('href')->nodeValue;
            if ($withId) {
                $id = $this->getId($href);
                if ($id) {
                    $hrefs[] = $href;
                    echo "<a href='" . $href . "'>$href</a>" . " has been added. </br>";
                    flush();
                }
            } else {
                $hrefs[] = $href;
                echo "<a href='" . $href . "'>$href</a>" . " has been added. </br>";
                flush();
            }
        }
        return $hrefs;
    }

    public function insertLinks($dbConnection, $links)
    {
        $queryHead = "Insert Into links(link) Values ";
        $queryValues = "";
        foreach ($links as $link) {
            $queryValues .= " ('" . $link . "'), ";
        }
        if ($queryValues != "")
            $queryValues = substr($queryValues, 0, strlen($queryValues) - 2);
        $query = $queryHead . $queryValues;
        mysqli_query($dbConnection, $query);
        if (mysqli_error($dbConnection))
            return mysqli_error($dbConnection);
        else
            return "success";
    }


    public function getId($url)
    {
        if (strpos($url, '108824868') !== false)
            return $url;
        return false;
    }
}

