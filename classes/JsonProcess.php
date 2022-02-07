<?php
namespace app\classes;
use app\classes\RootClass;
use app\classes\XmlUse;

class JsonProcess extends RootClass
{
    const RAITING_DIV    = 100000;
    const POINT_DIV      = 10;
    const OSCAR_REWARDS  = [1 => 0.3, 2 => 0.3, 3 => 0.5, 4 => 0.5, 5 => 0.5, 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1.5];
    
    protected static function saveFile($fileName, $exec) {
        if ($fileName && $fileName != ''){
            $myfileName = self::DIR_JSON.$fileName.'.json';
            return parent::saveFile($myfileName, $exec);
        }
        return 0;
    }
    public static function testSaveFile($fileName, $exec) {
        return self::saveFile($fileName, $exec);
    }
    public static function processingJSONFileForPenalizer($fileNameInput, $fileNameOutput){
        $json = self::readForFile($fileNameInput);
        return self::processingJSONForPenalizer($json, $fileNameOutput);
    }
    public static function processingJSONForPenalizer($json, $fileName){
        $max = self::findMaxUserRaiting($json);
        return self::penalizer($json, $max, $fileName);
    }

    public static function findMaxUserRaiting($json) {
        $arrayForJson    = json_decode($json);
        $maxUserRaitings = 0;
        foreach ($arrayForJson as $rowJson) {
            if ($maxUserRaitings < intval($rowJson->userRatings)){
                $maxUserRaitings = intval($rowJson->userRatings);
            }
        }
        return $maxUserRaitings;
    }

    public static function penalizer($json, $maxUserRaitings, $fileName){
        $arrayForJson    = json_decode($json);
        foreach ($arrayForJson as $key => $rowJson) {
            if (intval($rowJson->userRatings)<$maxUserRaitings) {
                $div = $maxUserRaitings - intval($rowJson->userRatings);
                $div = intdiv($div,  self::RAITING_DIV)/self::POINT_DIV;
                $arrayForJson[$key]->newOwnRaiting = number_format($rowJson->IMDB-$div, 1);
            }
        }
        $json = json_encode($arrayForJson);
        return self::saveFile($fileName, $json);
    }

    public static function getOscarNumber() {

    }
    public static function oscarCalculator($filename){

    }
    
}

