<?php
    // 应用存放的名称
    define("APP_NAME",'WEB');
    // 应用存放的路劲，系统会创建一些文件，并切放到这里
    define('APP_PATH','./WEB/');
    // 开启debug模式
    define('APP_DEBUG',true);
    //
    define('CSS_URL', '/WEB/Public/Css');
    define('IMG_URL','/WEB/Public/Images');
    define('JS_URL','/WEB/Public/Js');  
    require("./ThinkPHP/ThinkPHP.php"); 
 ?>
