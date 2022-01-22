<?php
namespace app\classes;
use SimpleXMLElement;
class XmlUse
{
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

    public static function testSaveFile($fileName, $exec) {
        return self::saveFile($fileName, $exec);

    }
    public static function read($exec) {
        $xml = simplexml_load_string($exec);
        if (!$xml)
            return 0;

        return $xml;
    }
    public static function findFirstTableInTheString($exec) {
        $pos1  = strpos($exec, '<table>');
        $pos2  = strpos($exec, '</table>');
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
}

