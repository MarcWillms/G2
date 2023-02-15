<?php

include_once 'lib.php';
//execute approved tests randomly
if(rand(0,8)===0){
//test sh_err_one_liner
sh_err_one_liner('test','warn');
sh_err_one_liner('test2');
sh_err_one_liner(array('test3','test2',123),'info');

//new dto
$dto= new myDTO();
$dto->join('test',123);
$dto->join('test2','test2');
$dto->join('test3',array('test3','test2',123));
sh_err_one_liner($dto->unjoin('test'),'warn');
sh_err_one_liner($dto->unjoin('test2'));
sh_err_one_liner($dto->unjoin('test3'),'info');

//transfer dto
$dto2= new myDTO($dto);
sh_err_one_liner($dto2->unjoin('test'),'warn');
sh_err_one_liner($dto2->unjoin('test2'));
sh_err_one_liner($dto2->unjoin('test3'),'info');

//destroy dto
$dto->destroy();
sh_err_one_liner($dto->unjoin('test'),'log');//destroyed
sh_err_one_liner($dto2->unjoin('test'),'log');//already exists
}
