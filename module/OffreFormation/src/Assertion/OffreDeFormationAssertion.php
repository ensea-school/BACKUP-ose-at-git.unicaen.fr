<?php

namespace OffreFormation\Assertion;

use Administration\Service\ParametresServiceAwareTrait;
use Application\Entity\Db\Annee;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Unicaen\Framework\Authorize\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\CentreCoutEp;
use OffreFormation\Entity\Db\ElementModulateur;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Entity\Db\VolumeHoraireEns;
use UnicaenImport\Entity\Db\Source;


/**
 * Description of OffreDeFormationAssertion
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class OffreDeFormationAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    protected function assertEntity(?ResourceInterface $entity = null, $privilege = null): bool
    {
        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !$this->authorize->isAllowedPrivilege($privilege)) return false;

        // Si c'est bon alors on affine...
        switch (true) {
            case $entity instanceof ElementPedagogique:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_EDITION:
                        return $this->assertElementPedagogiqueSaisie($entity);
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertElementPedagogiqueSaisieCentresCouts($entity);
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertElementPedagogiqueSaisieModulateurs($entity);
                    case Privileges::ODF_TAUX_MIXITE_EDITION:
                        return $this->assertElementPedagogiqueSaisieTauxMixite($entity);
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertElementPedagogiqueSaisieVH($entity);
                    case Privileges::ODF_ELEMENT_SYNCHRONISATION:
                        return $this->assertElementPedagogiqueSynchronisation($entity);
                }
            break;
            case $entity instanceof Etape:
                switch ($privilege) {
                    case Privileges::ODF_ETAPE_EDITION:
                        return $this->assertEtapeSaisie($entity);
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertEtapeSaisieCentresCouts($entity);
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertEtapeSaisieModulateurs($entity);
                    case Privileges::ODF_TAUX_MIXITE_EDITION:
                        return $this->assertEtapeSaisieTauxMixite($entity);
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertEtapeSaisieVH($entity);
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
                        return $this->assertStructureSaisie($entity);
                }
            break;
            case $entity instanceof CentreCoutEp:
                switch ($privilege) {
                    case Privileges::ODF_CENTRES_COUT_EDITION:
                        return $this->assertCentreCoutEpSaisieCentresCouts($entity);
                }
            break;
            case $entity instanceof ElementModulateur:
                switch ($privilege) {
                    case Privileges::ODF_MODULATEURS_EDITION:
                        return $this->assertElementModulateurSaisieModulateurs($entity);
                }
            break;
            case $entity instanceof VolumeHoraireEns:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertVolumeHoraireEnsSaisieVH($entity);
                }
            break;
            case $entity instanceof TypeIntervention:
                switch ($privilege) {
                    case Privileges::ODF_ELEMENT_VH_EDITION:
                        return $this->assertTypeInterventionSaisieVH($entity);
                }
            break;
        }

        return true;
    }



    /* ---- Edition étapes & éléments ---- */
    protected function assertElementPedagogiqueSaisie(ElementPedagogique $elementPedagogique): bool
    {
        return $this->asserts([
            $this->assertStructureSaisie($elementPedagogique->getStructure()),
            $this->assertSourceSaisie($elementPedagogique->getSource(), $elementPedagogique->getAnnee()),
        ]);
    }



    protected function assertElementPedagogiqueSynchronisation(ElementPedagogique $elementPedagogique): bool
    {
        return $this->asserts([
            $elementPedagogique->getSource()->getImportable(),
            $this->assertStructureSaisie($elementPedagogique->getStructure()),
        ]);
    }



    protected function assertEtapeSaisie(Etape $etape): bool
    {
        return $this->assertStructureSaisie($etape->getStructure());
    }



    /* ---- Centres de coûts ---- */
    protected function assertEtapeSaisieCentresCouts(Etape $etape): bool
    {
        return $this->assertStructureSaisie($etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertCentreCoutEpSaisieCentresCouts(CentreCoutEp $centreCoutEp): bool
    {
        return $this->assertElementPedagogiqueSaisieCentresCouts($centreCoutEp->getElementPedagogique());
    }



    protected function assertElementPedagogiqueSaisieCentresCouts(ElementPedagogique $elementPedagogique): bool
    {
        return $this->assertStructureSaisie($elementPedagogique->getStructure());
    }



    /* ---- Taux de mixité ---- */
    protected function assertEtapeSaisieTauxMixite(Etape $etape): bool
    {
        return $this->assertStructureSaisie($etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    /*
        protected function assertCentreCoutEpSaisieTauxMixite(CentreCoutEp $centreCoutEp): bool
        {
            return $this->assertElementPedagogiqueSaisieTauxMixite($centreCoutEp->getElementPedagogique());
        }
    */


    protected function assertElementPedagogiqueSaisieTauxMixite(ElementPedagogique $elementPedagogique): bool
    {
        return $this->assertStructureSaisie($elementPedagogique->getStructure());
    }



    /* ---- Volumes horaires d'enseigneement ---- */
    protected function assertEtapeSaisieVH(Etape $etape): bool
    {
        return $this->assertStructureSaisie($etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertElementPedagogiqueSaisieVH(ElementPedagogique $elementPedagogique): bool
    {
        return $this->asserts([
            $this->assertStructureSaisie($elementPedagogique->getStructure()),
            $this->assertSourceSaisie($elementPedagogique->getSource(), $elementPedagogique->getAnnee()),
        ]);
    }



    protected function assertTypeInterventionSaisieVH(TypeIntervention $typeIntervention): bool
    {
        return true;
    }



    protected function assertVolumeHoraireEnsSaisieVH(VolumeHoraireEns $volumeHoraireEns): bool
    {
        return $this->asserts([
            $volumeHoraireEns->getSource() ? $this->assertSourceSaisie($volumeHoraireEns->getSource(), $volumeHoraireEns->getElementPedagogique()->getAnnee()) : true,
            $volumeHoraireEns->getElementPedagogique() ? $this->assertElementPedagogiqueSaisieVH($volumeHoraireEns->getElementPedagogique()) : true,
            $volumeHoraireEns->getTypeIntervention() ? $this->assertTypeInterventionSaisieVH($volumeHoraireEns->getTypeIntervention()) : true,
        ]);
    }



    /* ---- Modulateurs ---- */
    protected function assertEtapeSaisieModulateurs(Etape $etape): bool
    {
        return $this->assertStructureSaisie($etape->getStructure())
            && $etape->getElementPedagogique()->count() > 0;
    }



    protected function assertElementPedagogiqueSaisieModulateurs(ElementPedagogique $elementPedagogique): bool
    {
        return $this->assertStructureSaisie($elementPedagogique->getStructure());
    }



    protected function assertElementModulateurSaisieModulateurs(CentreCoutEp $centreCoutEp): bool
    {
        return $this->assertElementPedagogiqueSaisieCentresCouts($centreCoutEp->getElementPedagogique());
    }



    /* ---- Globaux ---- */
    protected function assertStructureSaisie(Structure $structure): bool
    {
        if ($rs = $this->getServiceContext()->getStructure()) {
            return $structure->inStructure($rs);
        }

        return true;
    }



    protected function assertSourceSaisie(Source $source, Annee $annee): bool
    {
        $anneeMinimaleImportOdf = (int)$this->getServiceParametres()->get('annee_minimale_import_odf');
        if (0 == $anneeMinimaleImportOdf) {
            $anneeMinimaleImportOdf = $this->getServiceContext()->getAnneeImport()->getId();
        }

        if ($annee->getId() < $anneeMinimaleImportOdf) {
            return true;
        }
        return !$source->getImportable();
    }
}