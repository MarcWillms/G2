<?php
echo "Hello World";die();
define ("__KONFIGURATION", true);
if($_SERVER['SERVER_PORT'] && __KONFIGURATION){
    $config= parse_ini_file('../php_src/'.$_SERVER['SERVER_PORT'].'config.ini', true);
    if(!$config){
        die(404);
    }
} else{
    die(404);
}
var_dump($config);
die();

function shlog(mixed $data, string $context, string $react = 'log'):void{
        ob_start();
        if ($context) {
            $js_code = 'console.' . $react . '( \'' . json_encode(array($data, $context)) . '\' );';
        } else {
            $js_code = 'console.' . $react . '( \'' . json_encode($data) . ':\' );';
        }
        $js_code = '<script>' . $js_code . '</script>';
        echo $js_code;
}

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
//load and execute test, sandbox and mainCode

try {
    // Do Unit-Tests
    if (__TEST) {
        try{
            require_once(STDDIR . '/../specific_src/'.$_SERVER['SERVER_PORT'].'/testcode.php');
        testCode();
        } catch (Exception $e) {
            shlog($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    //even approved ones
    if (__TEST_APPROVED) {
        try{
            require_once(STDDIR . '/../specific_src/'.$_SERVER['SERVER_PORT'].'/testcode.php');
            testApprovedCode();
        } catch (Exception $e) {
            shlog($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    // do sandbox runs
    if (__SANDBOX) {
        try{
            require_once(STDDIR . '/../specific_src/'.$_SERVER['SERVER_PORT'].'/sandcode.php');
            sandCode();
        } catch (Exception $e) {
            shlog($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
        }
    }

    //load and execute mainCode
    try{
        require_once(STDDIR . '/../specific_src/'.$_SERVER['SERVER_PORT'].'/maincode.php');
        mainCode();
    } catch (Exception $e) {
        shlog($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
    }

} catch (Exception $e) {
    shlog($e->getMessage(), "Main-Index Exception:".__FILE__.".".__LINE__,'error');
}

//(C) Marc Willms 2021
