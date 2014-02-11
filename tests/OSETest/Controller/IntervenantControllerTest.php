<?php

namespace OSETest\Controller;

use Zend\Http\Request as HttpRequest;
use OSETest\Entity\Db\Asset as DbAsset;

/**
 * Description of IntervenantControllerTest
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IntervenantControllerTest extends BaseTest
{
    public function testSearch()
    {
        $em = $this->getEntityManager();
        
        $source = $em->find('Application\Entity\Db\Source', $id = DbAsset::SOURCE_TEST);
        if (!$source) {
            $source = DbAsset::source()->setId($id);
            $em->persist($source);
        }
        DbAsset::setSource($source);
        
        // insertion de quelques intervenants de tests
        $typeStructure = $em->find('Application\Entity\Db\TypeStructure', 'SCM');
        $etablissement = DbAsset::etablissement()
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
        $structure = DbAsset::structure($typeStructure, $etablissement)
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
        $corps = $em->find('Application\Entity\Db\Corps', $id = 1);
        if (!$corps) {
            $corps = DbAsset::corps()
                    ->setId($id)
                    ->setHistoCreateur(1)
                    ->setHistoModificateur(1);
        }
        $regime = $em->find('Application\Entity\Db\RegimeSecu', $id = '60');
        if (!$regime) {
            $regime = DbAsset::regimeSecu()
                    ->setId($id)
                    ->setHistoCreateur(1)
                    ->setHistoModificateur(1);
        }
        $i1 = DbAsset::intervenantPermanent($structure, $corps)
                ->setNomUsuel("Gauthier")
                ->setNomPatronymique("Gauthier")
                ->setPrenom("Bertrand")
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
        $i2 = DbAsset::intervenantExterieur($structure, $regime)
                ->setNomUsuel("Gautier")
                ->setNomPatronymique("Hochon")
                ->setPrenom("Jean-Paul")
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
        $i3 = DbAsset::intervenantExterieur($structure, $regime)
                ->setNomUsuel("Gaudé")
                ->setNomPatronymique("Gaudé")
                ->setPrenom("Laurent")
                ->setHistoCreateur(1)
                ->setHistoModificateur(1);
        $em->persist($typeStructure);
        $em->persist($etablissement);
        $em->persist($structure);
        $em->persist($corps);
        $em->persist($regime);
        $em->persist($i1);
        $em->persist($i2);
        $em->persist($i3);
        $em->flush();
        
        /**
         * SANS DOUTE INUTILE, VAUT MIEUX TESTER LA RECHERCHE
         */
        
        $params = array('term' => 'gau');
        $this->dispatch('/application/intervenant/search', HttpRequest::METHOD_GET, $params);
//        var_dump($this->getResponse());
        $this->assertMatchedRouteName('application/default');
        
//        $em->remove($i1);
//        $em->remove($i2);
//        $em->remove($i3);
//        $em->remove($structure);
//        $em->remove($etablissement);
//        $em->remove($corps);
//        $em->flush();
    }
    
    
}