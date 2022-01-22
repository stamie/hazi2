<?php

namespace app\controllers;
use app\classes\CurlTop20;
use app\classes\XmlUse;

class TestsController extends \yii\web\Controller
{
    const FILM_URL = 'title/tt0111161/?pf_rd_m=A2FGELUUNOQJNL&pf_rd_p=9703a62d-b88a-4e30-ae12-90fcafafa3fc&pf_rd_r=8YBFN9BAGNFFNPSKX3SG&pf_rd_s=center-1&pf_rd_t=15506&pf_rd_i=top&ref_=chttp_tt_1';
    public function actionCurltesttop250()
    {
        $exec = CurlTop20::loadTop250page();
        return $this->render('curltesttop250', ['exec' => $exec]);
    }
    public function actionCurltestfilmpage(string $url = self::FILM_URL)
    {
        $exec = CurlTop20::loadMovie($url);
        return $this->render('curltestfilmpage', ['exec' => $exec]);
    }
    
    public function actionXmlsavetest(){
        $exec = '<html>
                    <head>
                        <title>Test Page</title>
                    </head>
                    <body>
                        Hello Word!
                    </body>
                
                </html>';
        $filename = 'testsave1';
        $bool = XmlUse::testSaveFile($filename, $exec);
        $filename = str_replace('classes/../', '', XmlUse::DIR_XML).$filename.'.xml';
        if ($bool){
            return $this->render('xmlsavetest_good', ['filename' => $filename]);
        } 
        return $this->render('xmlsavetest_error', ['filename' => $filename]);
    }
    
}
?>
