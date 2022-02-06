<?php
namespace app\classes;
class RootClass
{
    const NUMBER_OF_FILMS = 20;
    const DIR_XML  = __DIR__."/../xmls/";
    const DIR_JSON = __DIR__."/../json/";

    protected static function saveFile($fileName, $exec) {
        if ($fileName && $fileName != ''){
            $myfile = fopen($fileName, "w");
            if (!$myfile)
                return 0;
            fwrite($myfile, $exec);
            fclose($myfile);
            return 1;
        }
        return 0;
    }


    public static function readForFile($filePath) {
        $file = fopen($filePath, "r");
        if (!$file)
            return 0;
        $exec = fread($file,filesize($filePath));
        fclose($file);
        return $exec;
    }
}
?>