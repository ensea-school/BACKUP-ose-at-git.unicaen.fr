<?php

namespace OSETest\Rule;

use Application\Rule\RuleInterface;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use OSETest\BaseTestCase;
use OSETest\Bootstrap;

/**
 * Classe mère des tests de règles métiers.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class BaseRuleTest extends BaseTestCase
{
    /**
     * @var RuleInterface
     */
    protected $rule;

    /**
     * @return string
     */
    abstract protected function getRuleName();

    /**
     * 
     */
    protected function setUp()
    {
        $this->rule = Bootstrap::getServiceManager()->get($this->getRuleName());
    }
    
    public function testRuleSingleton()
    {
        $rule1 = Bootstrap::getServiceManager()->get($this->getRuleName());
        $rule2 = Bootstrap::getServiceManager()->get($this->getRuleName());
        $this->assertSame($rule1, $rule2, "Le service manager ne fournit pas une instance unique de la règle.");
    }
    
    /**
     * 
     * @param Intervenant $intervenant
     * @param string $message
     */
    protected function assertIntervenantNotInResult(Intervenant $intervenant, $message = null)
    {
        $id = $intervenant->getId();
        
        // la règle porte sur un intervenant précis
        if ($this->rule->getIntervenant()) {
            $result = $this->rule->execute();
            $this->assertEquals([], $result, $message);
            $this->assertNotNull($this->rule->getMessage(), $message);
        } 
        // la règle ne porte sur aucun intervenant précis
        else {
            $result = $this->rule->execute();
            $this->assertArrayNotHasKey($id, $result, $message);
            $this->assertNotContains(['id' => $id], $result, $message);
            $this->assertNull($this->rule->getMessage(), $message);
        }
    }
    
    /**
     * 
     * @param Intervenant $intervenant
     * @param string $message
     */
    protected function assertIntervenantInResult(Intervenant $intervenant, $message = null)
    {
        $id = $intervenant->getId();
        
        // la règle porte sur un intervenant précis
        if ($this->rule->getIntervenant()) {
            $result = $this->rule->setIntervenant($intervenant)->execute();
            $this->assertEquals([$id => ['id' => $id]], $result, $message);
            $this->assertNull($this->rule->getMessage(), $message);
        } 
        // la règle ne porte sur aucun intervenant précis
        else {
            $result = $this->rule->setIntervenant(null)->execute();
            $this->assertArrayHasKey($id, $result, $message);
            $this->assertEquals(['id' => $id], $result[$id], $message);
            $this->assertNull($this->rule->getMessage(), $message);
        }
    }

    /**
     * Ajoute ou modifie du service à un intervenant.
     * 
     * @param Intervenant $intervenant Intervenant concerné
     * @param array $heures Heures à affecter aux volumes horaires, ex: ['TP' => 20, 'TD' => 5.5]
     * @param Service $service Service existant éventuel à modifier
     * @return Service
     */
    protected function setServiceIntervenant(Intervenant $intervenant, array $heures = [], Service $service = null)
    {
        if (null === $service) {
            $service = $this->getEntityProvider()->getService($intervenant);
            $intervenant->addService($service);
            $this->getEntityManager()->flush($intervenant);
        }
            
        // collecte des types de VH existant
        $types = [];
        foreach ($service->getVolumeHoraire() as $vh) {
            $code = $vh->getTypeIntervention()->getCode();
            $types[$code] = $vh->getTypeIntervention();
        }
        
        foreach ($heures as $code => $h) {
            if (!isset($types[$code])) {
                $typeIntervention = $this->getEntityProvider()->getTypeInterventionByCode($code);
                $vh = $this->getEntityProvider()->getVolumeHoraire($service, $h, $typeIntervention);
                $service->addVolumeHoraire($vh);
            }
            $vh->setHeures($heures[$code]);
            $this->getEntityManager()->flush($vh);
        }
        
        $this->getEntityManager()->flush($service);
        
        return $service;
    }
}