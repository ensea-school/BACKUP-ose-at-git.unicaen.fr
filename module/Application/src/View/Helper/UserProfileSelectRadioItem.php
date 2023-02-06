<?php

namespace Application\View\Helper;

use UnicaenApp\Traits\SessionContainerTrait;
use UnicaenAuth\View\Helper\UserProfileSelectRadioItem as UnicaenAuthViewHelper;
use Application\Service\Traits\StructureServiceAwareTrait as StructureServiceAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * Aide de vue dessinant un item de sélection d'un profil utilisateur.
 * Utilisé par l'aide de vue UserProfileSelect.
 *
 * @see UserProfileSelect
 */
class UserProfileSelectRadioItem extends UnicaenAuthViewHelper
{
    use StructureServiceAwareTrait;
    use StructureAwareTrait;
    use SessionContainerTrait;


    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function render()
    {
        $html = parent::render();

        $perimetre = $this->role ? $this->role->getPerimetre() : null;

        if ($this->role->getPeutChangerStructure() && $perimetre && $perimetre->isEtablissement()) {
            $selectClass = 'user-profile-select-input user-profile-select-input-structure toto';

            $select = new \Laminas\Form\Element\Select('structure-' . $this->role->getRoleId());
            $select
                ->setEmptyOption("(Aucune)")
                ->setValueOptions($this->getStructures())
                ->setValue($this->getStructure() ? $this->getStructure()->getId() : null)
                ->setAttribute('class', $selectClass)
                ->setAttribute('onchange', 'Util.userProfileStructureChange(this)')
                ->setAttribute('title', "Cliquez pour sélectionner la structure associée au profil $this->role");

            $html .= $this->getView()->formSelect($select);
        }

        return $html;
    }



    /**
     * Surcharge pour ne pas faire figurer la structure associée au rôle Administrateur
     * car elle figure dans la liste déroulante voisine.
     *
     * @return Radio
     */
    protected function createRadio()
    {
        $radio = parent::createRadio();

        $perimetre = $this->role->getPerimetre();

        if ($perimetre && $perimetre->isEtablissement()) {
            $id = $this->role->getRoleId();
            $radio->setValueOptions([$id => $this->role->getRoleName()]);
        }

        return $radio;
    }



    /**
     * Retourne la liste des structures associées à des rôles.
     *
     * @return array
     */
    private function getStructures()
    {
        $session = $this->getSessionContainer();
        if (!isset($session->structures)) {
            $qb                  = $this->getServiceStructure()->finderByEnseignement();
            $s                   = $this->getServiceStructure()->getList($qb);
            $session->structures = [];
            foreach ($s as $structure) {
                $session->structures[$structure->getId()] = (string)$structure;
            }
        }

        return $session->structures;
    }
}