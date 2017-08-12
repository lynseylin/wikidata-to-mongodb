<?php
/**
 * Created by PhpStorm.
 * User: lynseylin
 * Date: 2017/8/7
 * Time: 上午11:43
 */

require 'config.php';
require __DIR__ . '/vendor/autoload.php';
use Wikibase\JsonDumpReader\JsonDumpFactory;

set_time_limit(0);

$factory = new JsonDumpFactory();
$dumpReader = $factory->newExtractedDumpReader(JSON_FILE);
$dumpIterator = $factory->newStringDumpIterator($dumpReader);

$client = new MongoDB\Client(MONGODB_SERVER);
$itemsCollection = $client->wikidata->items;
$propertiesCollection = $client->wikidata->properties;

$itemList = [];
$propertyList = [];
foreach ( $dumpIterator as $jsonLine ) {
    $item = json_decode($jsonLine, true);
    $id = $item["id"];
    $item["_id"] = $id;

    try {
        if ($item["type"] == "item") {
            $result = $itemsCollection->insertOne($item);
        } else {
            $result = $propertiesCollection->insertOne($item);
        }
        var_dump($result->getInsertedId());
    } catch(Exception $e){
        $message = $e->getMessage();
        $code = $e->getCode();
        printf("error:%s\n", $message);
    }
}
