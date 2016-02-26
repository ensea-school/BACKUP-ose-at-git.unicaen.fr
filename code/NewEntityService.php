<?php

use UnicaenCode\Form\ElementMaker;
use UnicaenCode\Util;

/**
 * @var $this       \Zend\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 */

?>
    <h1>Création d'un nouveau service d'entité OSE</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$form->add(ElementMaker::selectEntity(
    'entity', 'Entité correspondante', 'Application\Entity\Db'
));
$form->add(ElementMaker::text('alias', 'Alias d\'entité'));
$form->add(ElementMaker::submit('generate', 'Générer le service'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $entity = $form->get('entity')->getValue();

    $sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
    /* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

    $params = $sCodeGenerator->generateServiceParams($fullClassname, $name, false);

    unset($params['Interface']);

    $params = $sCodeGenerator->generateServiceParams([
        'classname'         => 'Application\\Service\\' . $entity . 'Service',
        'name'              => 'application' . $entity,
        'useServiceLocator' => false,
        'generateTrait'     => true,
        'generateInterface' => false,
    ], [
        'Class' => [
            'template' => 'EntityService',
            'entity'   => $entity,
            'alias'    => $form->get('alias')->getValue(),
        ],
    ]);

    ?>

    <h3>Etape 2 : Création des fichiers sources du service</h3>
    <?php
    $sCodeGenerator->generateServiceFiles($params);
    ?>
    <div class="alert alert-info">Les fichiers sont récupérables dans le
        dossier <?php echo $sCodeGenerator->getOutputDir() ?></div>

    <h3>Etape 3 : Déclaration dans le fichier de configuration</h3>
    <?php $sCodeGenerator->generateFile($params['Config'], false); ?>
    <div class="alert alert-warning">
        Vous devez vous-même placer ces informations dans le fichier de configuration de votre
        module.
    </div>

    <?php
}