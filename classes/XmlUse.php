<?php
namespace app\classes;
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
    
}

