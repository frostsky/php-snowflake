# php-snowflake

php snowflake implment.

usage:

```
composer require frostsky/snowflake dev-master

require 'vendor/autoload.php';
use \Snowflake\Snowflake;

$snowflake = Snowflake::getInstance(1);
echo $snowflake->nextId();
```
