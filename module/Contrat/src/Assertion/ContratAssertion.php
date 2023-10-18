<?php

namespace Contrat\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Parametre;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Contrat\Entity\Db\Contrat;
use Contrat\Service\ContratServiceAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use UnicaenPrivilege\Assertion\AbstractAssertion;

// sous réserve que vous utilisiez les privilèges d'UnicaenAuth et que vous ayez généré votre fournisseur


/**
 * Description of ContratAssertion
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ContratAssertion extends AbstractAssertion
{
    use ParametresServiceAwareTrait;
    use ContratServiceAwareTrait;

    const PRIV_LISTER_FICHIERS   = 'contrat-lister-fichiers';
    const PRIV_SUPPRIMER_FICHIER = 'contrat-supprimer-fichier';
    const PRIV_AJOUTER_FICHIER   = 'contrat-ajouter-fichier';
    const PRIV_EXPORT            = 'contrat-export-all';

    use WorkflowServiceAwareTrait;


    /**
     * @param ResourceInterface $entity
     * @param string            $privilege
     *
     * @return boolean
     */
    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $localPrivs = [
            self::PRIV_LISTER_FICHIERS,
            self::PRIV_AJOUTER_FICHIER,
            self::PRIV_SUPPRIMER_FICHIER,
        ];

        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

        switch (true) {
            case $entity instanceof Contrat:
                switch ($privilege) {
                    case Privileges::CONTRAT_PROJET_GENERATION:
                    case Privileges::CONTRAT_CONTRAT_GENERATION:
                    case self::PRIV_EXPORT:
                        return $this->assertGeneration($entity);
                }
            break;
        }

        // pareil si le rôle ne possède pas le privilège adéquat
        if ($privilege && !in_array($privilege, $localPrivs) && !$role->hasPrivilege($privilege)) return false; // @todo traiter les privilèges locaux!!

        switch (true) {
            case $entity instanceof Contrat:
                switch ($privilege) {
                    case self::PRIV_LISTER_FICHIERS:
                        return $this->assertListerFichiers($entity);

                    case self::PRIV_AJOUTER_FICHIER:
                    case self::PRIV_SUPPRIMER_FICHIER:
                        return $this->assertModifierFichier($entity);

                    case Privileges::CONTRAT_VISUALISATION:
                        return $this->assertVisualisation($entity);

                    case Privileges::CONTRAT_CREATION:
                        return $this->assertCreation($entity);

                    case Privileges::CONTRAT_VALIDATION:
                        return $this->assertValidation($entity);

                    case Privileges::CONTRAT_DEPOT_RETOUR_SIGNE:
                        return $this->assertDepotRetourSigne($entity);

                    case Privileges::CONTRAT_SAISIE_DATE_RETOUR_SIGNE:
                        return $this->assertSaisieDateRetour($entity);

                    case Privileges::CONTRAT_DEVALIDATION:
                        return $this->assertDevalidation($entity);

                    case Privileges::CONTRAT_SUPPRESSION:
                        return $this->assertSuppression($entity);
                }
            break;
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        $intervenant = $this->getMvcEvent()->getParam('intervenant');

        if ($intervenant) {
            $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_CONTRAT, $intervenant);
            $wfOk          = $workflowEtape && $workflowEtape->isAtteignable();
            if (!$wfOk) return false;
        }

        return true;
    }



    protected function assertListerFichiers(Contrat $contrat)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::CONTRAT_VISUALISATION),
            $this->assertVisualisation($contrat),
            !$contrat->estUnProjet(),
        ]);
    }



    protected function assertModifierFichier(Contrat $contrat)
    {
        return $this->asserts([
            $this->getRole()->hasPrivilege(Privileges::CONTRAT_DEPOT_RETOUR_SIGNE),
            empty($contrat->getDateRetourSigne()),
            $this->assertDepotRetourSigne($contrat),
        ]);
    }



    protected function assertVisualisation(Contrat $contrat)
    {
        return $this->assertRole($contrat);
    }



    protected function assertCreation(Contrat $contrat)
    {
        return $this->asserts([
            $this->assertRole($contrat),
            $this->assertWorkflow($contrat),
        ]);
    }



    protected function assertValidation(Contrat $contrat)
    {
        return $this->asserts([
            $this->assertRole($contrat),
            !$contrat->getValidation(),
        ]);
    }



    protected function assertDevalidation(Contrat $contrat)
    {
        if (!$contrat->estUnAvenant()) {
            $contratService = $this->getServiceContrat();
            $devalid = !$contratService->hasAvenant($contrat);
//            $devalid = $contrat->getIntervenant()->getContrat()->count() == 1; // on ne peut dévalider un contrat que si aucun avenant n'existe
        } else {
            $devalid = true;
        }

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        return $this->asserts([
            $this->assertRole($contrat),
            $contrat->getValidation() && !$contratDirect,
            !$contrat->getDateRetourSigne(),
            $devalid,
        ]);
    }



    protected function assertSuppression(Contrat $contrat)
    {
        if (!$contrat->estUnAvenant()) {
            $devalid = $contrat->getIntervenant()->getContrat()->count() == 1; // on ne peut supprimer un contrat que si aucun avenant n'existe
        } else {
            $devalid = true;
        }

        $contratDirectResult = $this->getServiceParametres()->get('contrat_direct');
        $contratDirect       = ($contratDirectResult == Parametre::CONTRAT_DIRECT);

        return $this->asserts([
            $this->assertRole($contrat),
            !$contrat->getValidation() || ($contratDirect && $devalid),
            !$contrat->getDateRetourSigne(),
        ]);
    }



    protected function assertGeneration(Contrat $contrat)
    {
        if ($contrat->estUnProjet()) {
            return $this->getRole()->hasPrivilege(Privileges::CONTRAT_PROJET_GENERATION);
        } else {
            return $this->getRole()->hasPrivilege(Privileges::CONTRAT_CONTRAT_GENERATION);
        }
    }



    protected function assertDepotRetourSigne(Contrat $contrat)
    {
        return $this->asserts([
            $this->assertRole($contrat),
            !$contrat->estUnProjet(),
        ]);
    }



    protected function assertSaisieDateRetour(Contrat $contrat)
    {
        return $this->asserts([
            $this->assertRole($contrat),
            !$contrat->estUnProjet(),
        ]);
    }



    protected function assertRole(Contrat $contrat, $checkStructure = true)
    {
        if ($intervenant = $this->getRole()->getIntervenant()) {
            if (!$this->assertIntervenant($contrat, $intervenant)) return false;
        }

        if ($checkStructure && ($structure = $this->getRole()->getStructure())) {
            if (!$this->assertStructure($contrat, $structure)) return false;
        }

        return true;
    }



    protected function assertIntervenant(Contrat $contrat, Intervenant $intervenant)
    {
        return $contrat->getIntervenant() == $intervenant;
    }



    protected function assertStructure(Contrat $contrat, Structure $structure)
    {
        return $contrat->getStructure() == null || $contrat->getStructure()->inStructure($structure);
    }



    protected function assertWorkflow(Contrat $contrat)
    {
        $workflowEtape = $this->getServiceWorkflow()->getEtape(WfEtape::CODE_CONTRAT, $contrat->getIntervenant(), $contrat->getStructure());

        return $this->asserts(
            $workflowEtape && $workflowEtape->isAtteignable()
        );
    }
}