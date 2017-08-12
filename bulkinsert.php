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
ini_set('memory_limit',MEMORY_LIMIT);

$factory = new JsonDumpFactory();
$dumpReader = $factory->newExtractedDumpReader(JSON_FILE);
$dumpIterator = $factory->newStringDumpIterator($dumpReader);
$client = new MongoDB\Client(MONGODB_SERVER);
$items = $client->wikidata->items;
$properties = $client->wikidata->properties;

$itemList = [];
$propertyList = [];
foreach ( $dumpIterator as $jsonLine ) {
    $item = json_decode($jsonLine, true);
    $id = $item["id"];
    $item["_id"] = $id;

    if ($item["type"] == "item") {
        array_push($itemList, $item);
    } else {
        array_push($propertyList, $item);
    }
    try {
        if (count($itemList) === BULK_COUNT) {
            $insertManyResult = $items->insertMany($itemList);
            $itemList = [];
            printf("Inserted %d document(s) \n", $insertManyResult->getInsertedCount());
            var_dump($insertManyResult->getInsertedIds());
        }
        if (count($propertyList) === BULK_COUNT) {
            $insertManyResult = $properties->insertMany($propertyList);
            $propertyList = [];
            printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());
            var_dump($insertManyResult->getInsertedIds());
        }
    } catch(Exception $e){
        $message = $e->getMessage();
        $code = $e->getCode();
        printf("error:%s\n", $message);
    }
}
try {
    if (count($itemList) > 0) {
        $insertManyResult = $items->insertMany($itemList);
        $itemList = [];
        printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());
        var_dump($insertManyResult->getInsertedIds());
    }
    if (count($propertyList) > 0) {
        $insertManyResult = $properties->insertMany($propertyList);
        $propertyList = [];
        printf("Inserted %d document(s)\n", $insertManyResult->getInsertedCount());
        var_dump($insertManyResult->getInsertedIds());
    }
} catch(Exception $e){
    $message = $e->getMessage();
    $code = $e->getCode();
    echo "error:".$message;
}
