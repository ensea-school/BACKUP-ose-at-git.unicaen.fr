<?php

namespace Application\Assertion;

use Application\Entity\Db\Annee;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\VolumeHoraireEns;
use Application\Provider\Privilege\Privileges;
use Application\Entity\Db\CentreCoutEp;
use Application\Entity\Db\ElementModulateur;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Service\Traits\ContextServiceAwareTrait;
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
    use ContextServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity = null, $privilege = null)
    {
        $role = $this->getRole();
        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof ElementPedagogique:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_EDITION:
                        return $this->assertElementPedagogiqueSaisie($role, $entity);
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertElementPedagogiqueSaisieCentresCouts($role, $entity);
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertElementPedagogiqueSaisieModulateurs($role, $entity);
                    case Privileges::ODF_TAUX_MIXITE_EDITION:
                        return $this->assertElementPedagogiqueSaisieTauxMixite($role, $entity);
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertElementPedagogiqueSaisieVH($role, $entity);
                    case Privileges::ODF_ELEMENT_SYNCHRONISATION:
                        return $this->assertElementPedagogiqueSynchronisation($role, $entity);
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
                    case Privileges::ODF_TAUX_MIXITE_EDITION:
                        return $this->assertEtapeSaisieTauxMixite($role, $entity);
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertEtapeSaisieVH($role, $entity);
                }
            break;
            case $entity instanceof Structure:
                switch ($privilege) {
                    case Privileges::ODF_ETAPE_EDITION:
                    case Privileges::ODF_ELEMENT_EDITION:
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                    case Privileges::ODF_MODULATEURS_EDITION:
                    case Privileges::ODF_TAUX_MIXITE_EDITION:
                    case Privileges::ODF_ELEMENT_VH_EDITION:
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
            case $entity instanceof VolumeHoraireEns:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertVolumeHoraireEnsSaisieVH($role, $entity);
                }
            break;
            case $entity instanceof TypeIntervention:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertTypeInterventionSaisieVH($role, $entity);
                }
            break;
        }

        return true;
    }



    /* ---- Edition étapes & éléments ---- */
    protected function assertElementPedagogiqueSaisie(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->asserts([
            $this->assertStructureSaisie($role, $elementPedagogique->getStructure()),
            $this->assertSourceSaisie($elementPedagogique->getSource(), $elementPedagogique->getAnnee()),
        ]);
    }



    protected function assertElementPedagogiqueSynchronisation(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->asserts([
            $this->assertStructureSaisie($role, $elementPedagogique->getStructure()),
        ]);
    }



    protected function assertEtapeSaisie(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
            && $this->assertSourceSaisie($etape->getSource(), $etape->getAnnee());
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



    /* ---- Taux de mixité ---- */
    protected function assertEtapeSaisieTauxMixite(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    /*
        protected function assertCentreCoutEpSaisieTauxMixite(Role $role, CentreCoutEp $centreCoutEp)
        {
            return $this->assertElementPedagogiqueSaisieTauxMixite($role, $centreCoutEp->getElementPedagogique());
        }
    */


    protected function assertElementPedagogiqueSaisieTauxMixite(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->assertStructureSaisie($role, $elementPedagogique->getStructure());
    }



    /* ---- Volumes horaires d'enseigneement ---- */
    protected function assertEtapeSaisieVH(Role $role, Etape $etape)
    {
        return $this->assertStructureSaisie($role, $etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertElementPedagogiqueSaisieVH(Role $role, ElementPedagogique $elementPedagogique)
    {
        return $this->asserts([
            $this->assertStructureSaisie($role, $elementPedagogique->getStructure()),
            $this->assertSourceSaisie($elementPedagogique->getSource(), $elementPedagogique->getAnnee()),
        ]);
    }



    protected function assertTypeInterventionSaisieVH(Role $role, TypeIntervention $typeIntervention)
    {
        return true;
    }



    protected function assertVolumeHoraireEnsSaisieVH(Role $role, VolumeHoraireEns $volumeHoraireEns)
    {
        return $this->asserts([
            $volumeHoraireEns->getSource() ? $this->assertSourceSaisie($volumeHoraireEns->getSource(), $volumeHoraireEns->getElementPedagogique()->getAnnee()) : true,
            $volumeHoraireEns->getElementPedagogique() ? $this->assertElementPedagogiqueSaisieVH($role, $volumeHoraireEns->getElementPedagogique()) : true,
            $volumeHoraireEns->getTypeIntervention() ? $this->assertTypeInterventionSaisieVH($role, $volumeHoraireEns->getTypeIntervention()) : true,
        ]);
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



    protected function assertSourceSaisie(Source $source, Annee $annee)
    {
        if ($annee->getId() < $this->getServiceContext()->getAnneeImport()->getId()) {
            return true;
        };

        return !$source->getImportable();
    }
}