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

echo $this->form()->openTag($form->prepare());
echo $this->formControlGroup($form->get('entity'));
echo $this->formControlGroup($form->get('alias'));
echo $this->formRow($form->get('generate'));
echo $this->form()->closeTag();


if ($controller->getRequest()->isPost() && $form->isValid()) {

    $module      = 'Application';
    $entity      = $form->get('entity')->getValue();
    $name        = 'application'.$entity;
    $nsclassname = $module . '\\Service\\' . $entity . 'Service';

    $params = [
        'module'      => $module,
        'entity'      => $entity,
        'alias'       => $form->get('alias')->getValue(),
        'name'        => $name,
        'classname'   => Util::baseClassName($nsclassname),
        'namespace'   => Util::namespaceClass($nsclassname),
        'wmclassname' => Util::truncatedClassName($module, $nsclassname),
        'author'      => Util::getAuthor(),
    ];

    $fileName       = Util::classNameToFileName($nsclassname);
    $configFileName = 'module.config.php';

    $sCodeGenerator = $controller->getServiceLocator()->get('UnicaenCode\CodeGenerator');
    /* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

    ?>

    <h3>Etape 2 : Création des fichiers sources du service</h3>
    <?php
    $sCodeGenerator->setTemplate('EntityService')->setParams($params)->generateToHtml($fileName)->generateToFile($fileName);

    $p = $sCodeGenerator->generateServiceTraitParams($nsclassname, $name, $module.'\Service');
    $fileName = $p['fileName'];
    $sCodeGenerator->setTemplate('ServiceAwareTrait')->setParams($p)->generateToHtml($fileName)->generateToFile($fileName);

    $p = $sCodeGenerator->generateServiceInterfaceParams($nsclassname, $name, $module.'\Service');
    $fileName = $p['fileName'];
    $sCodeGenerator->setTemplate('ServiceAwareInterface')->setParams($p)->generateToHtml($fileName)->generateToFile($fileName);

    ?>
    <div class="alert alert-info">Les fichiers sont récupérables dans le
        dossier <?php echo $sCodeGenerator->getOutputDir() ?></div>

    <h3>Etape 3 : Déclaration dans le fichier de configuration</h3>
    <?php $sCodeGenerator->setTemplate('ServiceConfig')->setParams($params)->generateToHtml($configFileName); ?>
    <div class="alert alert-warning">
        Vous devez vous-même placer ces informations dans le fichier de configuration de votre
        module.
    </div>

    <?php
}