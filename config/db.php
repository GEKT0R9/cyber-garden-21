<?php

$DATABASE_URL = [
    'host' => 'localhost',
    'path' => '/cyber_garden_21',
    'user' => 'postgres',
    'pass' => '',
];

// $url = "postgres://utccrldlyhnyst:eaf9327206de3ff5d8cb3572c0ada24cd20e60fd5b12a672c884d6220ccb45ab@ec2-54-155-87-214.eu-west-1.compute.amazonaws.com:5432/d5k65p2iq0t0o8";
$url = "postgres://blfvlwohpkykqb:cb4ff0051f5df35282d379c174f047f34dc57e27d5bf00c22bb6dda1d700c8aa@ec2-34-250-16-127.eu-west-1.compute.amazonaws.com:5432/d253l5djfqnk67";
$DATABASE_URL = parse_url(getenv("DATABASE_URL"));
$DATABASE_URL = parse_url($url);

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'pgsql:host='.$DATABASE_URL["host"].';dbname='.ltrim($DATABASE_URL["path"], "/"),
    'username' => $DATABASE_URL["user"],
    'password' => $DATABASE_URL["pass"],
    'charset' => 'utf8',
];
