<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$file  = getcwd() . '/cache/GUYANE.ods';
$sheet = 1;
//$file  = getcwd() . '/cache/test.ods';
//$sheet = 0;
/*
$document = new \Unicaen\OpenDocument\Document();
$document->loadFromFile($file);

$s = $document->getCalc()->getSheet($sheet);
//xmlDump($document->getContent());
$c = $s->getCell('BG15');

$rc = array_keys($s->getRefCells($c));
var_dump($rc);
//echo $s->html();
  */
$formules = [
    'T20'  => 'of:=IF([.$I20]="Référentiel";0;IF([.$F20]+[.$G20]=0;0;([.$AJ20]+[.$AP20])/([.$F20]+[.$G20]))*[.F20]+[.$BZ20])',
    'U20'  => 'of:=IF([.$I20]="Référentiel";0;IF([.$F20]+[.$G20]=0;0;([.$AJ20]+[.$AP20])/([.$F20]+[.$G20]))*[.G20])',
    'V20'  => 'of:=IF([.$I20]="Référentiel";0;[.$AV20]+[.$BB20])',
    'W20'  => 'of:=IF([.$I20]="Référentiel";[.$BH20]+[.$BN20]+[.$BT20];0)',
    'X20'  => 'of:=IF([.$I20]="Référentiel";0;IF([.$F20]+[.$G20]=0;0;([.$AL20]+[.$AR20])/([.$F20]+[.$G20]))*[.F20]+[.$CB20])',
    'Y20'  => 'of:=IF([.$I20]="Référentiel";0;IF([.$F20]+[.$G20]=0;0;([.$AL20]+[.$AR20])/([.$F20]+[.$G20]))*[.G20])',
    'Z20'  => 'of:=IF([.$I20]="Référentiel";0;[.$AX20]+[.$BD20])',
    'AA20' => 'of:=0',
    'AB20' => 'of:=IF([.$I20]="Référentiel";[.$BJ20]+[.$BP20]+[.$BV20];0)',
    'AD20' => 'of:=IF(ISERROR([.J20]);1;[.J20])*IF([.$A20]="ES3";[.N20];0)',
    'AE15' => 'of:=SUM([.AD$1:.AD$1048576])',
    'AE20' => 'of:=IF(ISERROR([.J20]);1;[.J20])*IF(AND([.$A20]="ES3";i_structure_code<>"ES3";[.$AE$15]>=12);4/3;1)',
    'AF20' => 'of:=IF(ISERROR([.K20]);1;[.K20])*IF(AND([.$A20]="ES3";i_structure_code<>"ES3";[.$AE$15]>=12);4/3;1)',
    'AH20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]<>"Référentiel";[.$A20]=i_structure_code);[.$N20]*([.$F20]+[.$G20])*[.$AE20];0)',
    'AI15' => 'of:=SUM([.AH$1:.AH$1048576])',
    'AI16' => 'of:=MIN([.AI15];i_service_du)',
    'AI17' => 'of:=i_service_du-[.AI16]',
    'AI20' => 'of:=IF([.AI$15]>0;[.AH20]/[.AI$15];0)',
    'AJ20' => 'of:=[.AI$16]*[.AI20]',
    'AK20' => 'of:=IF([.AI$17]=0;([.AH20]-[.AJ20])/[.$AE20];0)',
    'AL20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.AK20]*[.$AF20];0)',
    'AN20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]<>"Réfé""re""ntiel";[.$A20]<>i_structure_code);[.$N20]*([.$F20]+[.$G20])*[.$AE20];0)',
    'AO15' => 'of:=SUM([.AN$1:.AN$1048576])',
    'AO16' => 'of:=MIN([.AO15];[.AI17])',
    'AO17' => 'of:=[.AI17]-[.AO16]',
    'AO20' => 'of:=IF([.AO$15]>0;[.AN20]/[.AO$15];0)',
    'AP20' => 'of:=[.AO$16]*[.AO20]',
    'AQ20' => 'of:=IF([.AO$17]=0;([.AN20]-[.AP20])/[.$AE20];0)',
    'AR20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.AQ20]*[.$AF20];0)',
    'AT20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]<>"Référentiel";[.$A20]=i_structure_code);[.$N20]*[.$H20]*[.$AE20];0)',
    'AU15' => 'of:=SUM([.AT$1:.AT$1048576])',
    'AU16' => 'of:=MIN([.AU15];[.AO17])',
    'AU17' => 'of:=[.AO17]-[.AU16]',
    'AU20' => 'of:=IF([.AU$15]>0;[.AT20]/[.AU$15];0)',
    'AV20' => 'of:=[.AU$16]*[.AU20]',
    'AW20' => 'of:=IF([.AU$17]=0;([.AT20]-[.AV20])/[.$AE20];0)',
    'AX20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.AW20]*[.$AF20];0)',
    'AZ20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]<>"Référentiel";[.$A20]<>i_structure_code);[.$N20]*[.$H20]*[.$AE20];0)',
    'BA15' => 'of:=SUM([.AZ$1:.AZ$1048576])',
    'BA16' => 'of:=MIN([.BA15];[.AU17])',
    'BA17' => 'of:=[.AU17]-[.BA16]',
    'BA20' => 'of:=IF([.BA$15]>0;[.AZ20]/[.BA$15];0)',
    'BB20' => 'of:=[.BA$16]*[.BA20]',
    'BC20' => 'of:=IF([.BA$17]=0;([.AZ20]-[.BB20])/[.$AE20];0)',
    'BD20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.BC20]*[.$AF20];0)',
    'BF20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]="Référentiel";[.$A20]=i_structure_code);[.$N20]*[.$AE20];0)',
    'BG15' => 'of:=SUM([.BF$1:.BF$1048576])',
    'BG16' => 'of:=MIN([.BG15];[.BA17])',
    'BG17' => 'of:=[.BA17]-[.BG16]',
    'BG20' => 'of:=IF([.BG$15]>0;[.BF20]/[.BG$15];0)',
    'BH20' => 'of:=[.BG$16]*[.BG20]',
    'BI20' => 'of:=IF([.BG$17]=0;([.BF20]-[.BH20])/[.$AE20];0)',
    'BJ20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.BI20]*[.$AF20];0)',
    'BL20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]="Référentiel";[.$A20]<>i_structure_code;[.$A20]<>[.$K$10]);[.$N20]*[.$AE20];0)',
    'BM15' => 'of:=SUM([.BL$1:.BL$1048576])',
    'BM16' => 'of:=MIN([.BM15];[.BG17])',
    'BM17' => 'of:=[.BG17]-[.BM16]',
    'BM20' => 'of:=IF([.BM$15]>0;[.BL20]/[.BM$15];0)',
    'BN20' => 'of:=[.BM$16]*[.BM20]',
    'BO20' => 'of:=IF([.BM$17]=0;([.BL20]-[.BN20])/[.$AE20];0)',
    'BP20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.BO20]*[.$AF20];0)',
    'BR20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]<>"Oui";[.$I20]="Référentiel";[.$A20]=[.$K$10]);[.$N20]*[.$AE20];0)',
    'BS15' => 'of:=SUM([.BR$1:.BR$1048576])',
    'BS16' => 'of:=MIN([.BS15];[.BM17])',
    'BS17' => 'of:=[.BM17]-[.BS16]',
    'BS20' => 'of:=IF([.BS$15]>0;[.BR20]/[.BS$15];0)',
    'BT20' => 'of:=[.BS$16]*[.BS20]',
    'BU20' => 'of:=IF([.BS$17]=0;([.BR20]-[.BT20])/[.$AE20];0)',
    'BV20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.BU20]*[.$AF20];0)',
    'BX20' => 'of:=IF(AND([.$E20]="Oui";[.$D20]="Oui");[.$N20]*[.$AE20];0)',
    'BY15' => 'of:=SUM([.BX$1:.BX$1048576])',
    'BY16' => 'of:=MIN([.BY15];[.BS17])',
    'BY17' => 'of:=[.BS17]-[.BY16]',
    'BY20' => 'of:=IF([.BY$15]>0;[.BX20]/[.BY$15];0)',
    'BZ20' => 'of:=[.BY$16]*[.BY20]',
    'CA20' => 'of:=IF([.BY$17]=0;([.BX20]-[.BZ20])/[.$AE20];0)',
    'CB20' => 'of:=IF(i_depassement_service_du_sans_hc="Non";[.CA20]*[.$AF20];0)',
];

$t = 'T20';
//$t = 'BA15';
//$formules = [$t => $formules[$t]];

foreach ($formules as $f) {
    $formule = new \Unicaen\OpenDocument\Calc\Formule($f);
    $formule->analyse();
    $formule->displayTerms();
    $formule->displayExprs();
}