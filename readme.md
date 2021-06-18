# API Address Provider (ID)
[![With](https://img.shields.io/badge/With-SemeFramework4-fb898f?style=flat)](https://seme.framework.web.id/)

Ready to use API provider for address options in Indonesian format. Created from Seme Framework version 4.

## Version
Here is the version log:
- 1.0.0 First Release

## Get started
Start consuming the API by reading the [alamat.thecloudalert.com](https://alamat.thecloudalert.com/)

## Instalation
Clone this repository
`git clone https://github.com/drosanda/address-id-api.git alamat`

### Importing database
Importing database from sql/tca_alamat.sql

### Configuration
For setup your site please create and adjust following files:
- app/config/config.php

```php
<?php
$site = "http://".$_SERVER['HTTP_HOST']."/alamat/";
$sene_method = "PATH_INFO";//REQUEST_URI,PATH_INFO,ORIG_PATH_INFO,
```

- app/config/database.php

```php
<?php
$db['host']  = "localhost";
$db['user']  = "root";
$db['pass']  = "";
$db['name']  = "tca_alamat";
$db['engine']= "mysqli"; //available mysql,mysqli,pdo
```


## How to use
To use the API, you can open [postman.json](https://github.com/drosanda/address-id-api/blob/master/postman.json) on this project directory using [Postman](https://www.postman.com/downloads/).

## Data Source
Address and Location data obtained from [bachors](https://github.com/bachors/kodepos-indonesia.sql).

## License
Licensed under MIT License 2.0
