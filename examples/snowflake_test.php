<?php
/**
 * @authors frostsky (dongshimin@frostsky.com)
 * @date    2017-12-04 17:36:04
 *
 * @version $Id$
 */
require 'vendor/autoload.php';
use \Snowflake\Snowflake;

$st = microtime(true);

$snowflake = Snowflake::getInstance(mt_rand(1, 1023));

for ($i = 0; $i < 10000; ++$i) {
    $id = $snowflake->nextId();
    echo "{$id}\n";
}

echo 'time:'.(microtime(true) - $st)."\n";