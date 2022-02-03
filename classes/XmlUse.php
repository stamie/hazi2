<?php
namespace app\classes;
// use yii\httpclient\XmlParser;
use SimpleXMLElement;
use DomDocument;
class XmlUse
{
    const NUMBER_OF_FILMS = 20;
    const DIR_XML  = __DIR__."/../xmls/";
    const DIR_JSON = __DIR__."/../json/";
    
    private static function saveFile($fileName, $exec) {
        if ($fileName && $fileName != ''){
            $myfile = fopen(self::DIR_XML.$fileName.'.xml', "w");
            if (!$myfile)
                return 0;
            fwrite($myfile, $exec);
            fclose($myfile);
            return 1;
        }
        return 0;

    }
    private static function saveJSONFile($fileName, string $exec) {
        if ($fileName && $fileName != ''){
            $myfile = fopen(self::DIR_JSON.$fileName.'.json', "w");
            if (!$myfile)
                return 0;
            fwrite($myfile, $exec);
            fclose($myfile);
            return 1;
        }
        return 0;

    }
    private static function saveJSON($fileName, array $array) {
        if ($fileName && $fileName != ''){
            $exec = json_encode($array);
            return self::saveJSONFile($fileName, $exec);
        }
        return 0;

    }
    public static function testSaveFile($fileName, $exec) {
        return self::saveFile($fileName, $exec);

    }
    public static function read($exec) { 
        $html = preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $exec);
        $html = str_replace('&nbsp;', '', $html);
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
    public static function readForFile($filePath) {
        $file = fopen($filePath, "r");
        if (!$file)
            return 0;
        $exec = fread($file,filesize($filePath));
        fclose($file);
        return $exec;
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

