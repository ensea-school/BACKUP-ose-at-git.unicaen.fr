<?php

use UnicaenCode\Form\ElementMakerForm;
use UnicaenCode\Util;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


?>
    <h1>Création d'un nouveau formulaire</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$form->add(ElementMakerForm::selectModule(
    'module', 'Module dans lequel sera placé votre formulaire'
));
$form->add(ElementMakerForm::select(
    'type', 'Type de formulaire (Form ou Fieldset)', ['Form' => 'Form', 'Fieldset' => 'Fieldset'], 'Form'
));
$form->add(ElementMakerForm::text(
    'classname', 'Nom de classe du formulaire (en CamelCase, avec éventuellement un namespace avant : MonNamespace\Exemple)', 'Exemple'
));
$form->add(ElementMakerForm::checkbox(
    'useHydrator', 'Implémenter un hydrateur spécifique'
));
$form->add(ElementMakerForm::checkbox(
    'generateTrait', 'Générer un trait', true
));
$form->add(ElementMakerForm::checkbox(
    'generateInterface', 'Générer une interface', false
));
$form->add(ElementMakerForm::checkbox(
    'useGetter', 'Générer des getters dans les traits et les interfaces', true
));
$form->add(ElementMakerForm::checkbox(
    'generateFactory', 'Générer une factory', true
));
$form->add(ElementMakerForm::submit('generate', 'Générer le formulaire'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $type      = $form->get('type')->getValue();
    $classname = $form->get('classname')->getValue();

    $sCodeGenerator = Util::codeGenerator()
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