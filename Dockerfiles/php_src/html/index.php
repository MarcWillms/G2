<?php
//echo "Hello World";die();
define ("__KONFIGURATION", true);
$config = array();

if($_SERVER['SERVER_PORT'] && __KONFIGURATION){
    define ("RESSOURCE_DIR",'../'.$_SERVER['SERVER_PORT'].'/');
    $config= parse_ini_file(RESSOURCE_DIR.'config.ini', true);
    if(!$config["BASE_CONFIG"]){//case of missing BASE_CONFIG
        die(404);
    }
} else{
    die(404);
}
//var_dump($config['BASE_CONFIG']);
//die();



//GET DIR-Constants
#require_once '../php_src/STDDIR.php';
#require_once '../php_src/SHAREDDIR.php';


//Load Basic Config and Constants
if (__KONFIGURATION) {
  define ("STDDIR","../php_src");
  define('SHAREDDIR', '/shared_data');
} else{ 
  die(404);
}
if($config['BASE_CONFIG']['debug']){
    function sh_log(mixed $data, string $context=null, string $react = 'log'):void{
        ob_start();
        if ($context) {
            $js_code = 'console.' . $react . '( \'' . json_encode(array($data, $context)) . '\' );';
        } else {
            $js_code = 'console.' . $react . '( \'' . json_encode($data) . ':\' );';
        }
        $js_code = '<script>' . $js_code . '</script>';
        echo $js_code;
    }
} else{
    function sh_log(mixed $data, string $context=null, string $react = 'log'):void{
    }
}
//load and execute test, sandbox and mainCode

try {
    sh_log($config['IDENTITY']['name']. "-container");
    // Do Unit-Tests
    if ($config['BASE_CONFIG']['test']) {
        try{
            sh_log("test",null, "warn");
            require_once(RESSOURCE_DIR.'test.php');
        } catch (Exception $e) {
            sh_log($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    //even approved ones
    if ($config['BASE_CONFIG']['test_approved']) {
        try{
            sh_log("test-approved",null, "warn");
            require_once(RESSOURCE_DIR.'test_approved.php');
        } catch (Exception $e) {
            sh_log($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    // do sandbox runs
    if ($config['BASE_CONFIG']['sandbox']) {
        try{
            sh_log("sandbox",null, "warn");
            require_once(RESSOURCE_DIR.'sandbox.php');
        } catch (Exception $e) {
            sh_log($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    //load and execute mainCode
    try{
        sh_log("main",null, "warn");
        require_once(RESSOURCE_DIR.'main.php');
    } catch (Exception $e) {
        sh_log($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
    }

} catch (Exception $e) {
    sh_log($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
}

//(C) Marc Willms 2021
