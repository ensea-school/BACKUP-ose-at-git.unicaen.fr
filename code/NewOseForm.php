<?php

use UnicaenCode\Form\ElementMaker;
use UnicaenCode\Util;

/**
 * @var $this       \Zend\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 */

?>
    <h1>Création d'un nouveau formulaire OSE (hérité d'AbstractForm)</h1>
    <h3>Etape 1 : Paramétrage</h3>

<?php

$form = new \Zend\Form\Form();
$form->add(ElementMaker::selectModule(
    'module', 'Module dans lequel sera placé votre formulaire'
));
$form->add(ElementMaker::text(
    'classname', 'Nom de classe du formulaire (en CamelCase, avec éventuellement un namespace avant : MonNamespace\Exemple)', 'Exemple'
));
$form->add(ElementMaker::checkbox(
    'useHydrator', 'Implémenter un hydrateur spécifique'
));
$form->add(ElementMaker::submit('generate', 'Générer le formulaire'));
$form->setData($controller->getRequest()->getPost());

Util::displayForm($form);

if ($controller->getRequest()->isPost() && $form->isValid()) {

    $module            = $form->get('module')->getValue();
    $useHydrator       = $form->get('useHydrator')->getValue();
    $classname         = $form->get('classname')->getValue();
    $name              = str_replace('\\', '', $classname);
    $targetFullClass   = $module . '\\Form\\' . $classname . 'Form';

    $sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
    /* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

    $params = $sCodeGenerator->generateFormParams($targetFullClass, 'Form', $name, $module, $useHydrator);

    $params['useSubForm'] = false !== strpos($classname,'\\');
    $configFileName       = 'module.config.php';

    ?>

    <h3>Etape 2 : Création des fichiers sources du formulaire</h3>
    <?php
    $sCodeGenerator->setTemplate('Form')->setParams($params)->generateToHtml($params['fileName'])->generateToFile($params['fileName']);

    $p = $sCodeGenerator->generateFormTraitParams($targetFullClass, $name, $module . '\Form');
    $sCodeGenerator->setTemplate('FormAwareTrait')->setParams($p)->generateToHtml($p['fileName'])->generateToFile($p['fileName']);

    $p = $sCodeGenerator->generateFormInterfaceParams($targetFullClass, $name, $module . '\Form');
    $sCodeGenerator->setTemplate('FormAwareInterface')->setParams($p)->generateToHtml($p['fileName'])->generateToFile($p['fileName']);

    ?>
    <div class="alert alert-info">Les fichiers sont récupérables dans le
        dossier <?php echo $sCodeGenerator->getOutputDir() ?></div>

    <h3>Etape 3 : Déclaration dans le fichier de configuration</h3>
    <?php $sCodeGenerator->setTemplate('FormConfig')->setParams($params)->generateToHtml($configFileName); ?>
    <div class="alert alert-warning">
        Vous devez vous-même placer ces informations dans le fichier de configuration de votre
        module.
    </div>

    <?php
}