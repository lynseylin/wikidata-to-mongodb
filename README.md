# wikidata-to-mongodb

## Introduction  
Read from wikidata json file and insert into mongodb

## Requires  
> php: >=5.6.0  
> MongoDB PHP Driver

## Installation  
Add two dependencies to your project's composer.json file. Here is an minimal example of a composer.json file:

> "require": { 
> 
>   "jeroen/json-dump-reader": "~1.4", 
> 
>   "mongodb/mongodb": "^1.1"  
> 
> }
>
> composer install

## Usage  
If your server has suffcient memory, you can set the memory limit and bulk count in config.ini, and use bulk insert code:  
> nohup php bulkinsert.php >nohup.log 2>&1

If your server don't hava enough memory, use single insert code, insert one item each time: 
> nohup php singleinsert.php >nohup.log 2>&1    
