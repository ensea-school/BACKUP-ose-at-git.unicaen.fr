<?php

namespace Mission\Assertion;

use Application\Acl\Role;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Provider\Privilege\Privileges;
use Mission\Entity\Db\Candidature;
use Mission\Entity\Db\OffreEmploi;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of MissionAssertion
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class OffreEmploiAssertion extends AbstractAssertion implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;

    /* ---- Routage général ---- */
    public function __invoke (array $page) // gestion des visibilités de menus
    {
        return $this->assertPage($page);
    }



    protected function assertPage (array $page)
    {
        switch ($page['route']) {
            case 'offre-emploi':
                $role = $this->getRole();
                if (!$role) {
                    $query = 'SELECT id FROM offre_emploi WHERE histo_destruction IS NULL';
                    $conn  = $this->getEntityManager()->getConnection();

                    if (false === $conn->executeQuery($query)->fetchOne()) {
                        // Aucune offre => pas de lien public
                        return false;
                    } else {
                        // Liste des offres d'emploi visible par tous
                        return true;
                    }


                    return true;
                }
                if (!$role->getIntervenant()) {
                    //Pas visible par les gestionnaires
                    return false;
                }

                return true;
        }

        return true;
    }



    protected function assertEntity (ResourceInterface $entity = null, $privilege = null)
    {
        /** @var Role $role */
        $role = $this->getRole();

        if ($privilege && !$role->hasPrivilege($privilege)) return false;

        switch (true) {
            case $entity instanceof OffreEmploi:
                switch ($privilege) {
                    case Privileges::MISSION_OFFRE_EMPLOI_VISUALISATION:
                        return $this->assertOffreEmploiVisualisation($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_MODIFIER:
                        return $this->assertOffreEmploiEdition($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_VALIDER:
                        return $this->assertOffreEmploiValidation($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_POSTULER:
                        return $this->assertOffreEmploiPostuler($role, $entity);
                    case Privileges::MISSION_CANDIDATURE_VISUALISATION:
                        return $this->assertCandidatureVisualisation($role, $entity);
                    case Privileges::MISSION_CANDIDATURE_VALIDER:
                        return $this->assertCandidatureValider($role, $entity);
                    case Privileges::MISSION_OFFRE_EMPLOI_SUPPRESSION:
                        return $this->assertOffreEmploiSupprimer($role, $entity);
                }
            break;
        }

        return true;
    }



    protected function assertOffreEmploiVisualisation (Role $role, OffreEmploi $offre)
    {
        return $this->asserts(
            $this->assertStructure($role, $offre->getStructure()),
        );
    }



    protected function assertStructure (Role $role, ?Structure $structure): bool
    {

        if (!$structure) {
            return true;
        }

        if (!$role->getStructure()) {
            return true;
        }

        return $role->getStructure()->getCode() == $structure->getCode();
    }



    protected function assertOffreEmploiEdition (Role $role, OffreEmploi $offre)
    {


        return $this->asserts([
            $this->haveRole(),
            $offre->canSaisie(),
            //$offre->haveCandidats(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function haveRole ()
    {
        $role = $this->getRole();

        if ($role instanceof Role) {
            return true;
        }

        return false;
    }



    protected function assertOffreEmploi (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->assertStructure($role, $offre->getStructure()),
        ]);
    }



    protected function assertOffreEmploiValidation (Role $role, OffreEmploi $offre)
    {

        return $this->asserts([
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function assertOffreEmploiPostuler (Role $role, OffreEmploi $offre)
    {

        //On vérifier que l'on a bien un contexte avec un intervenant
        if (!$this->haveIntervenant()) {
            return false;
        }


        return true;
    }



    protected function haveIntervenant ()
    {
        $role = $this->getRole();
        if ($role instanceof Role) {
            if ($role->getIntervenant() instanceof Intervenant) {
                return true;
            }
        }

        return false;
    }



    protected function assertCandidatureVisualisation (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
        ]);
    }



    protected function assertCandidatureValider (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
        ]);
    }



    protected function assertOffreEmploiSupprimer (Role $role, OffreEmploi $offre)
    {
        return $this->asserts([
            $this->haveRole(),
            $this->assertOffreEmploi($role, $offre),
        ]);
    }



    protected function haveCandidature ()
    {

    }

}