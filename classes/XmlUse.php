<?php
namespace app\classes;
// use yii\httpclient\XmlParser;
use SimpleXMLElement;
use DomDocument;
use app\classes\RootClass;
use app\classes\CurlTop20;
class XmlUse extends RootClass
{
    const NUMBER_OF_FILMS = 20;
    const DIR_XML  = __DIR__."/../xmls/";
    const DIR_JSON = __DIR__."/../json/";

    protected static function saveFile($fileName, $exec) {
        if ($fileName && $fileName != ''){
            $myfileName = self::DIR_XML.$fileName.'.xml';
            return parent::saveFile($myfileName, $exec);
        }
        return 0;

    }
    private static function saveJSONFile($fileName, string $exec) {
        if ($fileName && $fileName != ''){
            $myfileName = self::DIR_JSON.$fileName.'.json';
            return parent::saveFile($myfileName, $exec);
        }
        return 0;

    }
    
    public static function testSaveFile($fileName, $exec) {
        return self::saveFile($fileName, $exec);

    }
    public static function read($exec) { 
        $html = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $exec);
        $html = str_replace('&nbsp;', '', $html);
        $html = str_replace(',', '', $html);
        $dom = new DOMDocument;
        $dom->loadHTML($html);
        if (!$dom) {
            return 0;
        }

        $xml = simplexml_import_dom($dom);

        if (!$xml){
            return 0;
        } 
        return $xml;
    }
    public static function findFirstTableInTheString($exec) {
        $pos1  = strpos($exec, 'table');
        $pos2  = strpos($exec, 'table', ($pos1+1));
        $pos1--;
        $pos2--;
        $table = substr($exec, $pos1, ($pos2-$pos1+strlen('</table>')));
        return $table;

    }
    
    public static function findFirstTableInTheXmlFile($filePath) {
        $exec = XmlUse::readForFile($filePath);
        $xml  = XmlUse::findFirstTableInTheString($exec);
        return $xml;
    }

    public static function saveFirstTableToTheFile(string $filePathInput, string $filenameOutput) {
        $xml = self::findFirstTableInTheXmlFile($filePathInput);
        return self::saveFile($filenameOutput, $xml);
    }

    private static function getOscar($href) {
        $oscarNumber = 0;
        $html        = CurlTop20::loadMovie($href);
        $pattern     = '/Won [\d]+ Oscar/';
        preg_match($pattern, $html, $matches, PREG_OFFSET_CAPTURE); 
        if (is_array($matches) && count($matches) > 0 && isset($matches[0]) && isset($matches[0][0])){ 
            $oscarNumber = $matches[0][0];
            $oscarNumber = str_replace(['Won ', ' Oscar'], '',$oscarNumber);
            $oscarNumber = intval($oscarNumber);
        }
        return $oscarNumber;
    }
    public static function processigTr($tr) {
        $trArray = [];
        foreach($tr as $td){
           switch ($td->attributes()["class"]) {
            case "ratingColumn imdbRating":
                $string = $td->strong->attributes()['title']->__toString();
                $string = str_replace(" user ratings", "", $string);
                $stringToArray = explode(" based on ", $string);
                $trArray["IMDB"]        = floatval($stringToArray[0]);
                $trArray["userRatings"] = intval(str_replace(' ', '', $stringToArray[1]));
                break;
            case "titleColumn":
                $trArray["title"] = $td->a->__toString();
                $trArray["href"]  = $td->a->attributes()['href']->__toString();
                $trArray["Oscar"] = self::getOscar($trArray["href"]);
                
                break;
           }

        }

        return $trArray;
    }
    public static function saveXMLToArray(SimpleXMLElement $xml) {
        if (isset($xml->body) &&
            isset($xml->body->table) &&
            isset($xml->body->table->tbody) &&
            isset($xml->body->table->tbody->tr)
        ){
            //$return = 0;
            $index = 0;
            $tBodyToArray = [];
            foreach($xml->body->table->tbody->tr as $tr){
                $trToArray = self::processigTr($tr); //exit;
                $tBodyToArray[$index] = $trToArray;
                $index++;
                if ($index>=20){
                    break;
                }
            }
            return $tBodyToArray;
        }
        return 0;

    }
    public static function saveXMLToJSONFile(SimpleXMLElement $xml, $fileName) {
        if (isset($xml->body) &&
            isset($xml->body->table) &&
            isset($xml->body->table->tbody) &&
            isset($xml->body->table->tbody->tr)
        ){
            $XMLToArray = self::saveXMLToArray($xml);
            if ($XMLToArray) {
                $json = json_encode($XMLToArray);
                return self::saveJSONFile($fileName, $json);
            }

        }
        return 0;

    }
}

