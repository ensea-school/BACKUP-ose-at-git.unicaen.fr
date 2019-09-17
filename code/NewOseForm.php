<?php

use UnicaenCode\Form\ElementMaker;
use UnicaenCode\Util;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Interop\Container\ContainerInterface
 */


?>
    <h1>Création d'un nouveau formulaire</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$form->add(ElementMaker::selectModule(
    'module', 'Module dans lequel sera placé votre formulaire'
));
$form->add(ElementMaker::select(
    'type', 'Type de formulaire (Form ou Fieldset)', ['Form' => 'Form', 'Fieldset' => 'Fieldset'], 'Form'
));
$form->add(ElementMaker::text(
    'classname', 'Nom de classe du formulaire (en CamelCase, avec éventuellement un namespace avant : MonNamespace\Exemple)', 'Exemple'
));
$form->add(ElementMaker::checkbox(
    'useHydrator', 'Implémenter un hydrateur spécifique'
));
$form->add(ElementMaker::checkbox(
    'generateTrait', 'Générer un trait', true
));
$form->add(ElementMaker::checkbox(
    'generateInterface', 'Générer une interface', false
));
$form->add(ElementMaker::checkbox(
    'useGetter', 'Générer des getters dans les traits et les interfaces', true
));
$form->add(ElementMaker::checkbox(
    'generateFactory', 'Générer une factory', true
));
$form->add(ElementMaker::submit('generate', 'Générer le formulaire'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $type              = $form->get('type')->getValue();
    $classname         = $form->get('classname')->getValue();

    $sCodeGenerator = $sl->get('UnicaenCode\CodeGenerator');
    /* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

    $params = $sCodeGenerator->generateFormParams([
        'type'              => $type,
        'classname'         => $form->get('module')->getValue() . '\\Form\\' . $classname . $type,
        'name'              => ($type == 'Fieldset' ? 'fieldset' : '') . str_replace('\\', '', $classname),
        'useHydrator'       => $form->get('useHydrator')->getValue(),
        'generateTrait'     => $form->get('generateTrait')->getValue(),
        'generateInterface' => $form->get('generateInterface')->getValue(),
        'generateFactory'   => $form->get('generateFactory')->getValue(),
        'useGetter'         => $form->get('useGetter')->getValue(),
    ], [
        'Class' => [
            'template'   => 'OseForm',
            'useSubForm' => false !== strpos($classname, '\\'),
        ],
    ]);

    ?>

    <h3>Etape 2 : Création des fichiers sources du formulaire</h3>
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