<?php
namespace app\classes;
// use yii\httpclient\XmlParser;
use SimpleXMLElement;
use DomDocument;
class XmlUse
{
    const NUMBER_OF_FILMS = 20;
    const DIR_XML = __DIR__."/../xmls/";
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
            $myfile = fopen(self::DIR_XML.$fileName.'.json', "w");
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

    public static function saveXMLToJSONfile(SimpleXMLElement $xml) {
        if (isset($xml->body) &&
            isset($xml->body->table) &&
            isset($xml->body->table->tbody) &&
            isset($xml->body->table->tbody->tr)
        ){
            $return = 0;
            $index = 0;
            foreach($xml->body->table->tbody->tr as $tr){
                $return = 1;
                $index++;
                if ($index>=20){
                    break;
                }
            }
            return $return;
        }

    }

}

