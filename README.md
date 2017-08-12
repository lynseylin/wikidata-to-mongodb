# wikidata-to-mongodb

Introduction
Read from wikidata json file，and insert into mongodb。

Requires  
> php: >=5.6.0
> MongoDB PHP Driver

Installation
Add two dependencies to your project's composer.json file. Here is an minimal example of a composer.json file:

> "require": {
>   "jeroen/json-dump-reader": "~1.4",
>   "mongodb/mongodb": "^1.1"
> }

>composer install

Usage
> nohup php singleinsert.php >nohup.log 2>&1
> or if you has suffient memory, you can use bulk insert:
> nohup php bulkinsert.php >nohup.log 2>&1
