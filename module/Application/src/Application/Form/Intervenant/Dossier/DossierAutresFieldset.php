<?php

namespace Application\Form\Intervenant\Dossier;

use Application\Entity\Db\DossierAutre;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierAutreServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;

/**
 * Description of DossierAutresFieldset
 *
 */
class DossierAutresFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use DossierAutreServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

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
        foreach ($listChampsAutres as $champ) {

            $this->add([
                'name'    => 'champ-autre-' . $champ->getId(),
                'options' => [
                    'label'         => $champ->getLibelle(),
                    'label_options' => ['disable_html_escape' => true],
                ],
                'type'    => ($champ->getType()->getCode() == 'texte') ? 'text' : 'select',
            ]);

            if ($champ->getType()->getCode() == self::SELECT_SQL) {
                if (!empty($champ->getSqlValue())) {
                    $datas = ['' => '- NON RENSEIGNÉ -'] + $this->getServiceDossierAutre()->getValueOptionsBySql($champ);
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


        return $this;
    }



    public function getInputFilterSpecification()
    {
        return [];
    }
}