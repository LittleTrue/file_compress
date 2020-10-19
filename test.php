<?php

require_once __DIR__ . '/vendor/autoload.php';

use file\FileHandleClient\Application;
use file\FileHandleService\CompressFileService;

$ioc_con_app = new Application();

//出口电子订单申报
$CompressFileService = new CompressFileService($ioc_con_app);
$CompressFileService->compressImg('D:\新建文件夹\jjj.png', 'D:\新建文件夹\33.jpg', 1);
die();
