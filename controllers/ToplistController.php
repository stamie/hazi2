<?php

namespace app\controllers;
use app\classes\CurlTop20;
use app\classes\XmlUse;
use app\classes\JsonProcess;
class ToplistController extends \yii\web\Controller
{
    public function actionScrapper() //Adatok lekérése (JSON-ba)
    {
        $exec = CurlTop20::loadTop250page();
        $filename      = 'Table';

        $fileOutput     = $filename;
        $exp1           = ".xml";
        $exp2           = ".html";
        $filePathInput = XmlUse::DIR_XML . $fileOutput;

        $JsonFileNameInput  = __DIR__.'/../json/'.$filename.'.json'; 
        $JsonFileNameOutput = $filename.'_2';
        $bool = XmlUse::testSaveFile($filename, $exec);
        if ($bool) {
            $table = XmlUse::findFirstTableInTheXmlFile($filePathInput.$exp1);
            $exec = "<!DOCTYPE html><html><body>$table</body></html>";
            if (XmlUse::testSaveFile($fileOutput, $exec)) {//xml formátumba menti el 
                $html = XmlUse::readForFile($filePathInput.$exp1); 
                $xml  = XmlUse::read($html);
                $return = XmlUse::saveXMLToJSONFile($xml, $fileOutput); //Json formátumba menti el
                $bool = $return;
                if ($bool == 1){
                    return $this->render('jsonprocessing_good', ['xml' => $bool]);
                }
            }
        }
        return $this->render('index', ['exec' => $exec]);
    }
    public function actionPenalizer() //Büntetők számítása
    {
        $exec = CurlTop20::loadTop250page();
        $filename      = 'Table';

        $fileOutput     = $filename;
        $exp1           = ".xml";
        $exp2           = ".html";
        $filePathInput = XmlUse::DIR_XML . $fileOutput;

        $JsonFileNameInput  = __DIR__.'/../json/'.$filename.'.json'; 
        $JsonFileNameOutput = $filename.'_2';
        $bool = XmlUse::testSaveFile($filename, $exec);
        if ($bool) {
            $table = XmlUse::findFirstTableInTheXmlFile($filePathInput.$exp1);
            $exec = "<!DOCTYPE html><html><body>$table</body></html>";
            if (XmlUse::testSaveFile($fileOutput, $exec)) {//xml formátumba menti el 
                $html = XmlUse::readForFile($filePathInput.$exp1); 
                $xml  = XmlUse::read($html);
                $return = XmlUse::saveXMLToJSONFile($xml, $fileOutput); //Json formátumba menti el
                if ($return){
                    $bool = JsonProcess::processingJSONFileForPenalizer($JsonFileNameInput, $JsonFileNameOutput);
                    if ($bool == 1){
                        return $this->render('jsonprocessing_good', ['xml' => $bool]);
                    }
                }
            }
        }
        return $this->render('index', ['exec' => $exec]);
    }

    public function actionOscar() //Oszkár jutalmak számítása
    {
        $exec = CurlTop20::loadTop250page();
        $filename      = 'Table';

        $fileOutput     = $filename;
        $exp1           = ".xml";
        $exp2           = ".html";
        $filePathInput = XmlUse::DIR_XML . $fileOutput;

        $JsonFileNameInput  = __DIR__.'/../json/'.$filename.'.json'; 
        $JsonFileNameOutput = $filename.'_2';
        $bool = XmlUse::testSaveFile($filename, $exec);
        if ($bool) {
            $table = XmlUse::findFirstTableInTheXmlFile($filePathInput.$exp1);
            $exec = "<!DOCTYPE html><html><body>$table</body></html>";
            if (XmlUse::testSaveFile($fileOutput, $exec)) {//xml formátumba menti el 
                $html = XmlUse::readForFile($filePathInput.$exp1); 
                $xml  = XmlUse::read($html);
                $return = XmlUse::saveXMLToJSONFile($xml, $fileOutput); //Json formátumba menti el
                if ($return){
                    $bool = JsonProcess::processingJSONFileForPenalizer($JsonFileNameInput, $JsonFileNameOutput);
                    if ($bool == 1){
                        return $this->render('jsonprocessing_good', ['xml' => $bool]);
                    }
                }
            }
        }
        return $this->render('index', ['exec' => $exec]);
    }

}
