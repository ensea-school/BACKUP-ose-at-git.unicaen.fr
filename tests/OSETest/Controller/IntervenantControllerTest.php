<?php

namespace OSETest\Controller;

use Zend\Http\Request as HttpRequest;
use OSETest\Entity\Db\Asset as DbAsset;
use Application\Entity\Db\StatutIntervenant;

/**
 * Description of IntervenantControllerTest
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
//class IntervenantControllerTest extends BaseTest
//{
//    public function testSearch()
//    {
//        $this->markTestSkipped("On verra plus tard...");
//        
//        
//        
//        $em = $this->getEntityManager();
//        
//        $source = $em->find('Application\Entity\Db\Source', $id = DbAsset::SOURCE_TEST);
//        if (!$source) {
//            $source = DbAsset::source()->setId($id);
//            $em->persist($source);
//        }
//        DbAsset::setSource($source);
//        
//        if (!($user = $this->getEntityManager()->find('\Application\Entity\Db\Utilisateur', 1))) {
//            $this->markTestIncomplete("Utilisateur 1 introuvable.");
//        }
//            
//        // insertion de quelques intervenants de tests
//        $statutPerm = $em->getRepository('Application\Entity\Db\StatutIntervenant')->findOneBySourceCode(StatutIntervenant::ENS_CH);
//        $statutExt  = $em->getRepository('Application\Entity\Db\StatutIntervenant')->findOneBySourceCode(StatutIntervenant::SALAR_PRIVE);
//        $typeStructure = $em->find('Application\Entity\Db\TypeStructure', 'SCM');
//        $etablissement = DbAsset::etablissement()
//                ->setHistoCreateur($user)
//                ->setHistoModificateur($user);
//        $structure = DbAsset::structure($typeStructure, $etablissement)
//                ->setHistoCreateur($user)
//                ->setHistoModificateur($user);
//        $civilite = $em->find('Application\Entity\Db\Civilite', $id = 'M.');
//        if (!$civilite) {
//            $civilite = DbAsset::civilite();
//        }
//        $corps = $em->find('Application\Entity\Db\Corps', $id = 417);
//        if (!$corps) {
//            $corps = DbAsset::corps()
//                    ->setId($id)
//                    ->setHistoCreateur($user)
//                    ->setHistoModificateur($user);
//        }
//        $regime = $em->find('Application\Entity\Db\RegimeSecu', $id = '60');
//        if (!$regime) {
//            $regime = DbAsset::newRegimeSecu()
//                    ->setId($id)
//                    ->setHistoCreateur($user)
//                    ->setHistoModificateur($user);
//        }
//        $i1 = DbAsset::newIntervenantPermanent($civilite, $statutPerm, $structure, $corps)
//                ->setNomUsuel("Gauthier")
//                ->setNomPatronymique("Gauthier")
//                ->setPrenom("Bertrand")
//                ->setHistoCreateur($user)
//                ->setHistoModificateur($user);
//        $i2 = DbAsset::intervenantExterieur($civilite, $statutExt, $structure, $regime)
//                ->setNomUsuel("Gautier")
//                ->setNomPatronymique("Hochon")
//                ->setPrenom("Jean-Paul")
//                ->setHistoCreateur($user)
//                ->setHistoModificateur($user);
//        $i3 = DbAsset::intervenantExterieur($civilite, $statutExt, $structure, $regime)
//                ->setNomUsuel("Gaudé")
//                ->setNomPatronymique("Gaudé")
//                ->setPrenom("Laurent")
//                ->setHistoCreateur($user)
//                ->setHistoModificateur($user);
//        $em->persist($typeStructure);
//        $em->persist($etablissement);
//        $em->persist($structure);
//        $em->persist($civilite);
//        $em->persist($corps);
//        $em->persist($regime);
//        $em->persist($i1);
//        $em->persist($i2);
//        $em->persist($i3);
//        $em->flush();
//        
//        /**
//         * SANS DOUTE INUTILE, VAUT MIEUX TESTER LA RECHERCHE
//         */
//        
//        $params = array('term' => 'gau');
//        $this->dispatch('/application/intervenant/search', HttpRequest::METHOD_GET, $params);
////        var_dump($this->getResponse());
//        $this->assertMatchedRouteName('application/default');
//        
////        $em->remove($i1);
////        $em->remove($i2);
////        $em->remove($i3);
////        $em->remove($structure);
////        $em->remove($etablissement);
////        $em->remove($corps);
////        $em->flush();
//    }
//    
//    
//}