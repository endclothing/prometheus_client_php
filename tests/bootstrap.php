<?php

error_reporting(-1);
date_default_timezone_set('UTC');

require __DIR__ . '/../vendor/autoload.php';

define('REDIS_HOST', isset($_SERVER['REDIS_HOST']) ? $_SERVER['REDIS_HOST'] : '127.0.0.1');
define('PUSH_GATEWAY_ADDR', isset($_SERVER['PUSH_GATEWAY_ADDR']) ? $_SERVER['PUSH_GATEWAY_ADDR'] : 'pushgateway:9091');
