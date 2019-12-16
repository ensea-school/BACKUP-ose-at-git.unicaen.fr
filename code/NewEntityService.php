<?php

use UnicaenCode\Form\ElementMakerForm;
use UnicaenCode\Util;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Interop\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

?>
    <h1>Création d'un nouveau service d'entité OSE</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$form->add(ElementMakerForm::selectEntity(
    'entity', 'Entité correspondante', 'Application\Entity\Db'
));
$form->add(ElementMakerForm::text('alias', 'Alias d\'entité'));
$form->add(ElementMakerForm::submit('generate', 'Générer le service'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $entity = $form->get('entity')->getValue();

    $sCodeGenerator = Util::codeGenerator();
    /* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

    $params = $sCodeGenerator->generateServiceParams([
        'classname'         => 'Application\\Service\\' . $entity . 'Service',
        'generateTrait'     => true,
        'generateInterface' => false,
    ], [
        'Class' => [
            'template' => 'EntityService',
            'entity'   => $entity,
            'alias'    => $form->get('alias')->getValue(),
        ],
        'Factory' => [
            'template' => 'EntityServiceFactory'
        ],
    ]);

    ?>

    <h3>Etape 2 : Création des fichiers sources du service</h3>
    <?php
    $sCodeGenerator->generateFiles($params);
    ?>
    <div class="alert alert-info">Les fichiers sont récupérables dans le
        dossier <?= $sCodeGenerator->getOutputDir() ?></div>

    <h3>Etape 3 : Déclaration dans le fichier de configuration</h3>
    <?php $sCodeGenerator->generateFile($params['Config'], false); ?>
    <div class="alert alert-warning">
        Vous devez vous-même placer ces informations dans le fichier de configuration de votre
        module.
    </div>

    <?php
}