<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        return array();
    }
    
    public function demoAction()
    {
        if (($data = $this->params()->fromPost())) {
            var_dump($data);
            $intervenant = $this->getServiceLocator()->get('importServiceIntervenant');
            $result = $intervenant->get( $data['tf']['id'] );
            var_dump($result);

            $result = $intervenant->getAdresses( $data['tf']['id'] );
            var_dump($result);
        }
        return array();
        
//        $e = new \Application\Entity\Db\Intervenant();
//        $e->setCCivilite(new \Application\Entity\Db\Civilite());
//        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */
//        $em->persist($e);
//        $em->flush();
        
        
        
    }
    
    public function searchAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            
//            $service = $this->getServiceLocator()->get('ldapServicePeople'); /* @var $service \UnicaenLdap\Service\People */
//            $filter = \UnicaenLdap\Filter\Filter::orFilter(
//                    \UnicaenLdap\Filter\Filter::equals('supannEmpId', sprintf('%08s', $term)),
//                    \UnicaenLdap\Filter\Filter::contains('cn', $term));
//            $entities = $service->setOu(array('people'))->search($filter/*, 'cn'*/);
//            foreach ($entities as $uid => $item) { /* @var $item \UnicaenLdap\Entity\People */
//                $result[] = array(
//                    'id'    => $uid,
//                    'label' => $item->cn,
//                    'extra' => $item->mail . sprintf(' (%s)', $item->supannAliasLogin),
//                );
//            };

            $filter = \UnicaenLdap\Filter\Filter::contains('cn', $term);
            
            $ms = $this->getServiceLocator()->get('ldap_structure_mapper'); /* @var $ms \UnicaenApp\Mapper\Ldap\Structure */
            $mp = $this->getServiceLocator()->get('ldap_people_mapper'); /* @var $mp \UnicaenApp\Mapper\Ldap\People */
            $entities = $mp->findAllByName(null, null, $filter->toString());
            
            foreach ($entities as $uid => $item) { /* @var $item \UnicaenApp\Entity\Ldap\People */
                $result[] = array(
                    'id'    => $uid,
                    'label' => $item->getCn(),
                    'extra' => $item->getSupannEmpId() . sprintf(' (%s)', implode(' ; ', $item->getAffectationsAdmin($ms, true, true))),
                );
            };
            
            return new \Zend\View\Model\JsonModel(array_values($result));
        }
        
        exit;
    }
}
