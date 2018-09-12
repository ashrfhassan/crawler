<?php

class Crawler
{
    public function getPageContent($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) == true) {
            $str = file_get_contents($url);

            if (strlen($str) > 0) {
                $str = trim(preg_replace('/\s+/', ' ', $str));
            }else{
                echo "couldn't get content from URL : $url \n";
                flush();
                return false;
            }
            return $str;
        } else {
            echo "Invalid URL : $url \n";
            flush();
            return false;
        }
    }

    public function getUrls($dom)
    {
        $doc = new DOMDocument;
        $doc->loadHTML($dom);
        $items = $doc->getElementsByTagName('a');
        $hrefs = [];
        foreach ($items as $value) {
            $attrs = $value->attributes;
            $href = $attrs->getNamedItem('href')->nodeValue;;
            if (filter_var($href, FILTER_VALIDATE_URL) == true) {
                $hrefs[] = $href;
                echo "<a href='".$href."'>$href</a>" . " has been added. </br>";
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
}

