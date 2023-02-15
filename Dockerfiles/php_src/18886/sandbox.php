<?php
if(!__KONFIGURATION)
    die(404);

include_once 'lib.php';




//tests approved
$license='123456789456132345645';
$licenseDataArray=array('userID'=>'2131654asdf465321','Begin'=>'2020-12-31','End'=>'2024-12-31','LicenseType'=>'Trial','LicenseID'=>$license);

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
    $dummyLicenseParam=array('Begin'=>'2022-02-01','End'=>'2022-08-01','UserID'=>array('usdf12jk','usdf156k'),'groupID'=>array('gsdf12jk','gsdf12ja','gsdf156k'),'billing_open'=>0);

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
    sh_err_one_liner('------------------------------');
    sh_err_one_liner(License::askQuantityOfLicenses(),'log');
    $dto=License::createLicense($dummyLicenseParam);
    sh_err_one_liner($dto->unjoin_licenseID(),'log');
    sh_err_one_liner($dto->unjoin_licenseDataArray(),'log');
    $dto=License::readLicense($dto->unjoin_licenseID());
    sh_err_one_liner($dto->unjoin_licenseID(),'log');
    sh_err_one_liner($dto->unjoin_licenseDataArray(),'log');
    sh_err_one_liner('------------------------------');


//tests open
//clearLicenseDir();

