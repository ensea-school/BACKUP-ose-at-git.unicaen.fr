<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use Application\Service\ModeleContratService;
use Unicaen\OpenDocument\Document;

$fichier = '/home/laurent/Téléchargements/testfill.odt';
//$fichier = '/home/laurent/UnicaenCode/srcodt.odt';
//$fichier = '/home/laurent/tt.odt';
//$fichier = '/home/laurent/Téléchargements/Contrat.odt';

$modeleContrat = $sl->get(ModeleContratService::class)->get(13);

$document = new Document();
$document->setTmpDir('/home/laurent/UnicaenCode');
//$document->loadFromFile($fichier);
$document->loadFromData($modeleContrat->getFichier());

$document->getStylist()->addFiligrane('PROJET');

$document->setStylesChanged(true);
xmlDump($document->getStyles());
$document->setPdfOutput(true);
//$document->saveToFile('/home/laurent/UnicaenCode/odtExport.pdf');


//$document->download('exp.pdf');
