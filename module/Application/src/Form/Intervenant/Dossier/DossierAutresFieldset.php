<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element\Select;

/**
 * Description of DossierAutresFieldset
 *
 */
class DossierAutresFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use StatutServiceAwareTrait;

    const AUTRE_TEXT  = 'texte';
    const SELECT_FIXE = 'select-fixe';
    const SELECT_SQL  = 'select-sql';



    public function init()
    {
        $this->addElements();
    }



    private function addElements()
    {

        $listChampsAutres = $this->getOption('listChampsAutres');
        if ($listChampsAutres) {

            foreach ($listChampsAutres as $champ) {

                $this->add([
                    'name'       => 'champ-autre-' . $champ->getId(),
                    'required'   => false,
                    'options'    => [
                        'label'         => $champ->getLibelle(),
                        'label_options' => ['disable_html_escape' => true],
                    ],
                    'attributes' => [
                        'class' => 'dossierElement',
                    ],
                    'type'       => ($champ->getType()->getCode() == 'texte') ? 'text' : 'select',
                ]);

                if ($champ->getType()->getCode() == self::SELECT_SQL) {
                    if (!empty($champ->getSqlValue())) {
                        $datas = ['' => '(Sélectionnez ' . $champ->getLibelle() . ')'] + $this->getServiceDossierAutre()->getValueOptionsBySql($champ);
                        $this->get('champ-autre-' . $champ->getId())
                            ->setValueOptions($datas);
                    }
                }

                if ($champ->getType()->getCode() == self::SELECT_FIXE) {
                    if (!empty($champ->getJsonValue())) {
                        $datas = ['' => '- NON RENSEIGNÉ -'] + $this->getServiceDossierAutre()->getValueOptionByJson($champ);
                        $this->get('champ-autre-' . $champ->getId())
                            ->setValueOptions($datas);
                    }
                }

                $champAutreElement = $this->get('champ-autre-' . $champ->getId());

                if (!empty($champ->getDescription())) {
                    $champAutreElement->setAttribute('info_icon', $champ->getDescription());
                }
                if ($champ->isObligatoire()) {
                    $champAutreElement->setLabel($champ->getLibelle() . ' <span class="text-danger">*</span>');
                }
            }
        }


        return $this;
    }



    public function getInputFilterSpecification()
    {
        $specs    = [];
        $elements = $this->getElements();
        foreach ($elements as $element) {
            /**
             * @var $element Select
             */
            $type = $element->getAttribute('type');
            if ($type == 'select') {
                $specs [$element->getAttribute('name')] =
                    [
                        'required'    => false,
                        'allow_empty' => true,
                    ];
            }
        }

        return $specs;
    }
}