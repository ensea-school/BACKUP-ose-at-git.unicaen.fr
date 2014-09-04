<?php

namespace Application\Service;

use Application\Entity\Db\TypeAgrement as TypeAgrementEntity;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementNavigationPagesProvider extends AbstractService
{
    public function __invoke(array &$page, array $params = array())
    {
        $pages = [];
        
        /**
         * Création d'une page par type d'agrément pris en compte
         */
        foreach ($this->getTypesAgrements() as $typeAgrement) { /* @var $typeAgrement TypeAgrementEntity */
            $child = [
                'label'  => (string) $typeAgrement,
                'title'  => "Agrément $typeAgrement",
                'params' => array(
                    // NB: le paramètre 'intervenant' est injecté par la NavigationFactory du module
                    'typeAgrement' => $typeAgrement->getId(),
                ),
                'visible' => true,
            ];
            $child = array_merge($child, $params);
            
            $pages[$typeAgrement->getCode()] = $child;
        }
        
        // s'il n'y aucune page fille (car aucun type d'agrément), on masque la page mère
        if (!count($pages)) {
            $page['visible'] = false;
        }
        // s'il n'y a qu'une page fille (car un seul type d'agrément), on "remplace"
        // la page mère par cette seule page
        elseif (count($pages) === 1) {
            $child['title'] = $page['title'] . " : " . $child['label'];
            $child['label'] = $page['label'] . " : " . $child['label'];
            $page = array_merge($page, $child);
            $pages = [];
        }
            
        return $pages;
    }
    
    /**
     * Fetch tous les types d'agrément existants.
     * 
     * @return array id => TypeAgrement
     */
    public function getTypesAgrements()
    {
        $service = $this->getServiceLocator()->get('ApplicationTypeAgrement'); /* @var $service \Application\Service\TypeAgrement */
        
        return $service->getList();
    }
}