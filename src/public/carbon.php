<?php
require '../vendor/autoload.php';

use Carbon\Carbon;

$dt = Carbon::create(2025,9,1,14,30,0);

echo $dt->format('Y年m月d日 H時i分s秒');