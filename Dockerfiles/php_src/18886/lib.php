<?php
if(!__KONFIGURATION)
    die(404);
function sh_err_one_liner(mixed $data, string $react = 'error'):void{
    echo '<script> console.' . $react . '( \'' . json_encode($data) . '\' ); </script>';
}

//License_lib
define('LICENSE_PATH', '/shared_data/remote_data/license_data/');
define('LICENSE_EXTENSION', '.lic');
//DTO class to join data do a array data set by name and value, also to unjoin data from a array data set by name and value
class myDTO{
    private $data = [];

    function __construct(myDTO $dto = null){
        try {
            if($dto){
                $this->data = $dto->get_data();
            }
        } catch (Exception $e) {
            sh_err_one_liner($e->getMessage(), 'error');
        }
    }

    final public function join(string $name,mixed $value):void{
        $this->data[$name] = $value;
    }

    final public function unjoin(string $name):mixed{
        return $this->data[$name];
    }

    private function get_data():mixed{
        return $this->data;
    }

    final public function destroy():void{
        $this->data = null;
    }
}

//LicenseDTO class to join and unjoin licenseID and licenseDataArray
class LicenseDTO extends myDTO{

    function __construct(myDTO $dto = null){
        try {
            parent::__construct($dto);
        } catch (Exception $e) {
            sh_err_one_liner($e->getMessage(), 'error');
        }
    }

    final public function join_licenseID(string $licenseID):void{
        $this->join('licenseID', $licenseID);
    }

    final public function unjoin_licenseID():string{
        return $this->unjoin('licenseID');
    }

    final public function join_licenseDataArray(array $licenseDataArray):void{
        $this->join('licenseDataArray',$licenseDataArray);
    }

    final public function unjoin_licenseDataArray():mixed{
        return $this->unjoin('licenseDataArray');
    }
}

//LicenseChecker class to check if a license is valid
class LicenseChecker{
    public static function checkLicenseFormat(string $licenseCandidate, int $length=16, string $pattern="/\W/"):bool{
        $result= ((strlen($licenseCandidate) === $length)
            &&  !(preg_match($pattern, $licenseCandidate) >= 1));
        if($result) {
            sh_err_one_liner('valid');
        }
        return $result;
    }

    public static function checkLicenseExistsAlready(string $licenseCandidate): bool{
        return file_exists(LICENSE_PATH . $licenseCandidate . LICENSE_EXTENSION);
    }

    public static function getLicenseAvailable(string $licenseCandidate): ?LicenseDTO{
        $dto=new LicenseDTO();
        try {
            if (self::checkLicenseExistsAlready($licenseCandidate)) {
                $dto->join_licenseDataArray(parse_ini_file(LICENSE_PATH . $licenseCandidate . LICENSE_EXTENSION, true));
                $dto->join_licenseID($licenseCandidate);
            }
        } catch (Exception $e) {
            sh_err_one_liner($e->getMessage(), 'error');
            return null;
        }
        return $dto;
    }

    public static function setLicenseAvailable(LicenseDTO $licenseDTO): bool{
        try {
            $licenseID = $licenseDTO->unjoin_licenseID();
            $licenseDataArray = $licenseDTO->unjoin_licenseDataArray();
            if (!self::checkLicenseExistsAlready($licenseID)) {
                $fh = fopen(LICENSE_PATH . $licenseID . LICENSE_EXTENSION, 'wb');
                flock($fh, LOCK_EX);
                    fwrite($fh, self::arr2ini($licenseDataArray) . PHP_EOL);
                fclose($fh);
                return true;
            }
        } catch (Exception $e) {
            sh_err_one_liner($e->getMessage(), 'error');
        }
        return false;
    }

    private static function arr2ini(array $a,array $parent = array()):string{
        // https://stackoverflow.com/questions/17316873/convert-array-to-an-ini-file
        $out = '';
        foreach ($a as $k => $v)
        {
            if (is_array($v))
            {
                //subsection case
                //merge all the sections into one array...
                $sec = array_merge((array) $parent, (array) $k);
                //add section information to the output
                $out .= '[' . implode('.', $sec) . ']' . PHP_EOL;
                //recursively traverse deeper
                $out .= self::arr2ini($v, $sec);
            }
            else
            {
                //plain key->value case
                $out .= "$k=$v" . PHP_EOL;
            }
        }
        return $out;
    }

}

//generate License, save license after if its valid and not exists in Filesystem
function generateRandomString(string $pattern = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', int $length=16):string{
    $result = '';
    $charactersLength = strlen($pattern);
    for ($i = 0; $i < $length; $i++) {
        $result .= $pattern[rand(0, $charactersLength - 1)];
    }
    return $result;
}

function generateLicense(array $licenseDataArray):?licenseDTO{
    try {
        // do new licenses while there is no new or valid one
        $licenseID='';
        do{
            if(!LicenseChecker::checkLicenseFormat($licenseID)) {
                $licenseID = generateRandomString();
            }
        } while(LicenseChecker::checkLicenseExistsAlready($licenseID));

        $licenseDTO=new licenseDTO();
        $licenseDTO->join_licenseID($licenseID);
        $licenseDTO->join_licenseDataArray($licenseDataArray);

        if(LicenseChecker::setLicenseAvailable($licenseDTO)){
            return $licenseDTO;
        }
    } catch (Exception $e) {
        sh_err_one_liner($e->getMessage(),'error');
    }
    return null;
}

function clearLicenseDir(){
    $files = glob(LICENSE_PATH.'*'); // get all file names
    foreach($files as $file){ // iterate files
        if(is_file($file))
            unlink($file); // delete file
    }
}

//CRUD operations and ask Quantity of Licenses in LICENSE_PATH
class License{
    public static function createLicense(array $licenseDataArray):?licenseDTO{
        return generateLicense($licenseDataArray);
    }

    public static function readLicense(string $licenseID):?licenseDTO{
        return LicenseChecker::getLicenseAvailable($licenseID);
    }

    public static function deleteLicense(string $licenseID):bool{
        try {
            if (LicenseChecker::checkLicenseExistsAlready($licenseID)) {
                return unlink(LICENSE_PATH . $licenseID . LICENSE_EXTENSION);
            }
        } catch (Exception $e) {
            sh_err_one_liner($e->getMessage(), 'error');
        }
        return false;
    }

    public static function askQuantityOfLicenses():int{
        $files = glob(LICENSE_PATH.'*'.LICENSE_EXTENSION); // get all file names
        return count($files);
    }
}