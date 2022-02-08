<?php
namespace app\classes;
use app\classes\RootClass;
use app\classes\XmlUse;

class JsonProcess extends RootClass
{
    const RAITING_DIV    = 100000;
    const POINT_DIV      = 10;
    const OSCAR_REWARDS  = [0 => 0, 1 => 0.3, 2 => 0.3, 3 => 0.5, 4 => 0.5, 5 => 0.5, 6 => 1, 7 => 1, 8 => 1, 9 => 1, 10 => 1, 11 => 1.5];
    
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
        $max = self::findMaxUserRating($json);
        return self::penalizer($json, $max, $fileName);
    }

    public static function findMaxUserRating($json) {
        $arrayForJson    = json_decode($json);
        $maxUserRatings = 0;
        foreach ($arrayForJson as $rowJson) {
            if ($maxUserRatings < intval($rowJson->userRatings)){
                $maxUserRatings = intval($rowJson->userRatings);
            }
        }
        return $maxUserRatings;
    }

    public static function penalizer($json, $maxUserRatings, $fileName){
        $arrayForJson    = json_decode($json);
        foreach ($arrayForJson as $key => $rowJson) {
            if (intval($rowJson->userRatings)<$maxUserRatings) {
                $div = $maxUserRatings - intval($rowJson->userRatings);
                $div = intdiv($div,  self::RAITING_DIV)/self::POINT_DIV;
                $arrayForJson[$key]->newOwnRating = number_format($rowJson->IMDB-$div, 1);
            }
        }
        $json = json_encode($arrayForJson);
        return self::saveFile($fileName, $json);
    }

    public static function getOscarNumber() {

    }
    public static function oscarCalculator($fileNameInput, $fileNameOutput){
        $json         = self::readForFile($fileNameInput);
        $arrayForJson = json_decode($json);
        foreach ($arrayForJson as $key => $value) {
            $oscar = intval($value->Oscar);
            $reward = self::OSCAR_REWARDS[count(self::OSCAR_REWARDS)-1];
            if (isset(self::OSCAR_REWARDS[$oscar])){
                $reward = self::OSCAR_REWARDS[$oscar];
            }
            if (isset($arrayForJson[$key]->newOwnRating)){
                $arrayForJson[$key]->newOwnRatingWithOscarReward = $arrayForJson[$key]->newOwnRating + $reward;
            } else {
                $arrayForJson[$key]->newOwnRatingWithOscarReward = $arrayForJson[$key]->userRatings + $reward;
            }
        }
        $json = json_encode($arrayForJson);
        return self::saveFile($fileNameOutput, $json);

    }
    
}

