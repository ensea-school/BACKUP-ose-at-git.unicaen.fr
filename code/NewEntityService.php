<?php

use UnicaenCode\Form\AbstractForm;
use UnicaenCode\Form\ElementMakerForm;
use UnicaenCode\Util;

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */
class EntityServiceForm extends AbstractForm
{
    public function init()
    {
        $this->addSelectEntity(
            'entityClass', 'Entité du service'
        );

        $this->addText(
            'alias', 'Alias d\'entité'
        );

        $this->addCheckbox(
            'awareTrait', 'Générer un trait', true
        );

        $this->addCheckbox(
            'awareInterface', 'Générer une interface', false
        );

        $this->addCheckbox(
            'subDir', 'Les traits, interfaces et Factory seront placés dans des sous-dossiers dédiés', false
        );
    }



    public function getParams(): array
    {
        $params                    = [
            'generator'   => 'Service',
            'type'        => 'Service',
            'entityClass' => $this->get('entityClass')->getValue(),
            'useGetter'   => true,
            'subDir'      => $this->get('subDir')->getValue(),
            'alias'       => $this->get('alias')->getValue(),
        ];
        $params['entityClassname'] = Util::classClassname($params['entityClass']);
        $params['class']           = Util::classModule($params['entityClass']) . '\Service\\' . $params['entityClassname'] . 'Service';

        if ($this->get('awareTrait')->getValue()) {
            $params['awareTrait'] = [];
        }
        if ($this->get('awareInterface')->getValue()) {
            $params['awareInterface'] = [];
        }
        $params['factory'] = [];

        return $params;
    }
}





$cg = \UnicaenCode\Util::codeGenerator();

$cg->start('Création d\'un nouveau service d\'entité OSE');

$form = new EntityServiceForm();
$form->init();

$form->addText('alias', 'Alias d\'entité');


if (empty($params = $cg->formPublish($form))) return;

$params['template']            = 'EntityService';
$params['factory']['template'] = 'EntityServiceFactory';

$cg->end($params);