<?php
namespace Application\View\Helper;

use UnicaenAuth\View\Helper\UserProfileSelectRadioItem as UnicaenAuthViewHelper;
use UnicaenAuth\View\Helper\UserProfileSelect;
use Application\Service\Structure as StructureService;
use Application\Entity\Db\Structure as StructureEntity;

/**
 * Aide de vue dessinant un item de sélection d'un profil utilisateur.
 * Utilisé par l'aide de vue UserProfileSelect.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier@unicaen.fr>
 * @see UserProfileSelect
 */
class UserProfileSelectRadioItem extends UnicaenAuthViewHelper
{
    /**
     * Retourne le code HTML généré par cette aide de vue.
     *
     * @return string
     */
    public function render()
    {
        $html = parent::render();
             
//        if ($this->role instanceof \Application\Acl\AdministrateurRole) {
//            $selectClass = 'user-profile-select-input-structure';
//
//            $select = new \Zend\Form\Element\Select('structure');
//            $select
//                    ->setEmptyOption("(Aucune)")
//                    ->setValueOptions(array_map(function($v) { return (string) $v; }, $this->getStructures()))
//                    ->setValue($this->structureSelectionnee ? $this->structureSelectionnee->getId() : null)
//                    ->setAttribute('class', $selectClass)
//                    ->setAttribute('title', "Cliquez pour sélectionner la structure associée au profil $this->role");
//
//            $html .= ' ' . $this->getView()->formSelect($select);
//
//            $html .= <<<EOS
//<script>
//    $(function() {
//        $("select.$selectClass").tooltip({ delay: 500, placement: 'right' }).change(function() {
//            var roleSelect = $("input.user-profile-select-input");
//            if (! roleSelect.attr("checked")) {
//                roleSelect.attr("checked", true);
//            }
//            submitProfile(); 
//        });
//    });  
//</script>
//EOS;
//        }
        
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
        
        if ($this->role instanceof \Application\Acl\AdministrateurRole) {
            $id = $this->role->getRoleId();
            $radio->setValueOptions(array($id => $this->role->getRoleName()));
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
        $qb = $this->structureService->finderByRole(true);
        
        return $this->structureService->getList($qb);
    }
    
    /**
     * @var StructureService 
     */
    protected $structureService;
    
    function setServiceStructure(StructureService $structureService)
    {
        $this->structureService = $structureService;
        
        return $this;
    }

    /**
     * @var StructureEntity
     */
    protected $structureSelectionnee;
    
    public function setStructureSelectionnee(StructureEntity $structureSelectionnee = null)
    {
        $this->structureSelectionnee = $structureSelectionnee;
        
        return $this;
    }
}