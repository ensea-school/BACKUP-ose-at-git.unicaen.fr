<?php

namespace Application\Assertion;

use Application\Provider\Privilege\Privileges;
use Application\Entity\Db\CentreCoutEp;
use Application\Entity\Db\ElementModulateur;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use UnicaenImport\Entity\Db\Source;
use Application\Entity\Db\Structure;
use UnicaenAuth\Assertion\AbstractAssertion;
use Application\Acl\Role;
use Zend\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of OffreDeFormationAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class OffreDeFormationAssertion extends AbstractAssertion
{
    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();
        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch(true){
            case $entity instanceof ElementPedagogique:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_EDITION:
                        return $this->assertElementPedagogiqueSaisie($role,$entity);
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertElementPedagogiqueSaisieCentresCouts($role, $entity);
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertElementPedagogiqueSaisieModulateurs($role, $entity);
                }
                break;
            case $entity instanceof Etape:
                switch ($privilege) {
                    case Privileges::ODF_ETAPE_EDITION:
                        return $this->assertEtapeSaisie($role, $entity);
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertEtapeSaisieCentresCouts($role, $entity);
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertEtapeSaisieModulateurs($role, $entity);
                }
                break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::ODF_ETAPE_EDITION:
                    case Privileges::ODF_ELEMENT_EDITION:
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertStructureSaisie($role, $entity);
                }
                break;
            case $entity instanceof CentreCoutEp:
                switch ($privilege) {
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertCentreCoutEpSaisieCentresCouts($role, $entity);
                }
                break;
            case $entity instanceof ElementModulateur:
                switch ($privilege) {
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertElementModulateurSaisieModulateurs($role, $entity);
                }
                break;
        }

        return true;
    }



    /* ---- Edition étapes & éléments ---- */
    protected function assertElementPedagogiqueSaisie(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->assertStructureSaisie($role, $elementPedagogique->getStructure())
        && $this->assertSourceSaisie($elementPedagogique->getSource());
    }



    protected function assertEtapeSaisie(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
        && $this->assertSourceSaisie($etape->getSource());
    }



    /* ---- Centres de coûts ---- */
    protected function assertEtapeSaisieCentresCouts(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
        && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertCentreCoutEpSaisieCentresCouts(Role $role, CentreCoutEp $centreCoutEp)
    {
        return $this->assertElementPedagogiqueSaisieCentresCouts($role, $centreCoutEp->getElementPedagogique());
    }



    protected function assertElementPedagogiqueSaisieCentresCouts(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->assertStructureSaisie($role, $elementPedagogique->getStructure());
    }



    /* ---- Modulateurs ---- */
    protected function assertEtapeSaisieModulateurs(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
        && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertElementPedagogiqueSaisieModulateurs(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->assertStructureSaisie($role, $elementPedagogique->getStructure());
    }



    protected function assertElementModulateurSaisieModulateurs(Role $role, CentreCoutEp $centreCoutEp)
    {
        return $this->assertElementPedagogiqueSaisieCentresCouts($role, $centreCoutEp->getElementPedagogique());
    }



    /* ---- Globaux ---- */
    protected function assertStructureSaisie(Role $role, Structure $structure)
    {
        if ($rs = $role->getStructure()) {
            return $rs === $structure;
        }

        return true;
    }



    protected function assertSourceSaisie(Source $source)
    {
        return ! $source->getImportable();
    }
}