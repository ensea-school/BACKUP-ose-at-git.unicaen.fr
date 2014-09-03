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
        
//        $label = isset($params['label'])
        
        /**
         * Création d'une page par type d'agrément pris en compte
         */
        foreach ($this->getTypesAgrements() as $typeAgrement) { /* @var $typeAgrement TypeAgrementEntity */
            $child = [
                'label'  => (string) $typeAgrement,
                'title'  => "Agrément &laquo; $typeAgrement &raquo;",
                'params' => array(
                    // NB: le paramètre 'intervenant' est injecté par la NavigationFactory du module
                    'typeAgrement' => $typeAgrement->getId(),
                ),
                'visible' => true,
            ];
            $pages[$typeAgrement->getCode()] = array_merge($child, $params);
        }
//        var_dump($pages);
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