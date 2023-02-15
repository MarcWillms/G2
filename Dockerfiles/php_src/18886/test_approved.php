<?php

include_once 'lib.php';
//execute approved tests randomly
if(rand(0,8)===0){

    //test sh_err_one_liner
    sh_err_one_liner('test','warn');
    sh_err_one_liner('test2');
    sh_err_one_liner(array('test3','test2',123),'info');

    //check minKeyValuePair example for load balancing
    $array= [0=>50,1=>53,2=>30,3=>20,4=>15,5=>11,6=>70,7=>80];
    sh_err_one_liner(getMinKeyValuePair($array),'info');

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

    $license='123456789456132345645';
    $licenseDataArray=array('userID'=>'2131654asdf465321','Begin'=>'2020-12-31','End'=>'2024-12-31','LicenseType'=>'Trial','LicenseID'=>$license);
    $dummyLicenseParam=array('Begin'=>'2022-02-01','End'=>'2022-08-01','UserID'=>array('usdf12jk','usdf156k'),'groupID'=>array('gsdf12jk','gsdf12ja','gsdf156k'),'billing_open'=>0);

//create DTO
    $licenseDTO=new LicenseDTO();
    $licenseDTO->join_licenseID($license);
    $licenseDTO->join_licenseDataArray($licenseDataArray);
    sh_err_one_liner($licenseDTO->unjoin_licenseID(),'warn');
    sh_err_one_liner($licenseDTO->unjoin_licenseDataArray(),'info');


    //copy DTO
    $licenseDTO2=new LicenseDTO($licenseDTO);
    sh_err_one_liner($licenseDTO2->unjoin_licenseID(),'warn');
    sh_err_one_liner($licenseDTO2->unjoin_licenseDataArray(),'info');

    //change DTO
    $licenseDTO->join_licenseID('asdhjkhrjkl');
    $licenseDTO->join_licenseDataArray(array('a','as','asd'));
    sh_err_one_liner($licenseDTO->unjoin_licenseID(),'warn');
    sh_err_one_liner($licenseDTO->unjoin_licenseDataArray(),'info');

    //destroy DTO
    $licenseDTO->destroy();
    sh_err_one_liner($licenseDTO->unjoin_licenseID(),'log');//destroyed
    $licenseDTO2->destroy();
    sh_err_one_liner($licenseDTO2->unjoin_licenseID(),'log');//destroyed


    //create dto3
    $dto3=generateLicense($dummyLicenseParam);
    sh_err_one_liner($dto3->unjoin_licenseID(),'log');
    sh_err_one_liner($dto3->unjoin_licenseDataArray(),'log');

    //retrieve dto4
    $dto4=LicenseChecker::getLicenseAvailable($dto3->unjoin_licenseID());
    sh_err_one_liner($dto4->unjoin_licenseID(),'log');
    sh_err_one_liner($dto4->unjoin_licenseDataArray(),'log');
    /*      License Class         */
    //retrieve Quantity of Licenses
    sh_err_one_liner(License::askQuantityOfLicenses(),'log');
    $dto=License::createLicense($dummyLicenseParam);
    sh_err_one_liner($dto->unjoin_licenseID(),'log');
    sh_err_one_liner($dto->unjoin_licenseDataArray(),'log');
    $dto=License::readLicense($dto->unjoin_licenseID());
    sh_err_one_liner($dto->unjoin_licenseID(),'log');
    sh_err_one_liner($dto->unjoin_licenseDataArray(),'log');
    $dtos=License::updateLicense($dto->unjoin_licenseID(),array(
        'UserID'=>array('asdf12jk','asdf15s6k'),
        'groupID'=>array('asdf12jr','asdf12ja','assdf156k'),
        'billing_open'=>1));
    $dto=License::readLicense($dto->unjoin_licenseID());
    sh_err_one_liner($dto->unjoin_licenseID(),'log');
    sh_err_one_liner($dto->unjoin_licenseDataArray(),'log');

}
