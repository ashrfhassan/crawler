<?php

include_once './DBConnection.php';

function createDB($dbConnection)
{
    $statement = 'CREATE TABLE IF NOT EXISTS `links` (`id` int(10) UNSIGNED NOT NULL,
`parent_link` text COLLATE utf8_unicode_ci,
`link` text COLLATE utf8_unicode_ci NOT NULL)
 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 ALTER TABLE `links` ADD PRIMARY KEY (`id`);
 ALTER TABLE `links` MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
 COMMIT;';
    mysqli_multi_query($dbConnection, $statement);
    if (mysqli_error($dbConnection))
        return mysqli_error($dbConnection);
    else
        return "success";
}

function execute()
{
    echo createDB(DBConnection::getDBConnection());
}