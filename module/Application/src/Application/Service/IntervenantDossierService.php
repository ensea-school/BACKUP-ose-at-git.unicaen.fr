<?php

namespace Application\Service;

use Application\Entity\Db\Dossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;

/**
 * Description of Dossier
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class IntervenantDossierService extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;
    use ValidationServiceAwareTrait;

    /**
     * @var IntervenantDossier[]
     */
    private $dcache = [];



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return IntervenantDossier::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'id';
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return IntervenantDossier|null
     */
    public function getByIntervenant(Intervenant $intervenant)
    {
        if (isset($this->dcache[$intervenant->getId()])) {
            return $this->dcache[$intervenant->getId()];
        }

        $qb = $this->finderByIntervenant($intervenant);
        $this->finderByHistorique($qb);
        foreach ($this->getList($qb) as $intervenantDossier) {
            return $intervenantDossier;
        }
        $intervenantDossier                  = $this->newEntity()->fromIntervenant($intervenant);
        $this->dcache[$intervenant->getId()] = $intervenantDossier;

        return $intervenantDossier;
    }



    /**
     * Détermine si l'intervenant courant était connu comme vacataire les années précédentes
     * dans l'application.
     *
     * @param int $x Si x = 3 par exemple, on recherche l'intervenant en N-1, N-2 et N-3.
     *
     * @return Intervenant Intervenant de l'année précédente
     */
    public function intervenantVacataireAnneesPrecedentes(Intervenant $intervenant, $x = 1)
    {
        $sourceCode = $intervenant->getSourceCode();

        for ($i = 1; $i <= $x; $i++) {
            $annee = $this->getServiceContext()->getAnneeNmoins($i);
            $iPrec = $this->getServiceIntervenant()->getBySourceCode($sourceCode, $annee);

            if ($iPrec && $iPrec->getStatut()->estVacataire() && $iPrec->getStatut()->getPeutSaisirService()) {
                return $iPrec;
            }
        }

        return null;
    }



    /**
     * Retourne la validation d'un dossier d'intervenant
     *
     * @param Intervenant $intervenant
     *
     * @return Validation
     */
    public function getValidation(Intervenant $intervenant)
    {
        $validation        = null;
        $serviceValidation = $this->getServiceValidation();
        $qb                = $serviceValidation->finderByType(TypeValidation::CODE_DONNEES_PERSO);
        $serviceValidation->finderByHistorique($qb);
        $serviceValidation->finderByIntervenant($intervenant, $qb);
        $validations = $serviceValidation->getList($qb);
        if (count($validations)) {
            $validation = current($validations);
        }

        return $validation;
    }



    public function isComplete(Intervenant $intervenant)
    {

        $intervenantDossier = $this->getByIntervenant($intervenant);


        $completude = [
            'dossier'          => false,
            'dossierIdentite'  => false,
            'dossierAdresse'   => false,
            'dossierContact'   => false,
            'dossierInsee'     => false,
            'dossierIban'      => false,
            'dossierEmployeur' => false,
            'dossierAutres'    => false,
        ];
        //Complétude de l'identite

        $completudeDossierIdentie = ($intervenantDossier->getCivilite() &&
            $intervenantDossier->getNomUsuel() &&
            $intervenantDossier->getPrenom()) ? true : false;

        //Complétude de l'adresse
        $completudeAdressePart1 = (($intervenantDossier->getAdressePrecisions() ||
            $intervenantDossier->getAdresseLieuDit() ||
            ($intervenantDossier->getAdresseVoie() && $intervenantDossier->getAdresseNumero()))) ? true : false;

        $completudeAdressePart2 = ($intervenantDossier->getAdresseCommune() &&
            $intervenantDossier->getAdresseCodePostal()) ? true : false;

        if ($completudeAdressePart1 && $completudeAdressePart2) {
            $completudeDossierAdresse = true;
        }

        //Complétude de contact
        $completudeDossierContact = (($intervenantDossier->getEmailPerso() || $intervenantDossier->getEmailPro()) &&
            ($intervenantDossier->getTelPerso() || $intervenantDossier->getTelPro)) ? true : false;

        $completude = [
            'dossier'          => false,
            'dossierIdentite'  => $completudeDossierIdentie,
            'dossierAdresse'   => $completudeDossierAdresse,
            'dossierContact'   => $completudeDossierContact,
            'dossierInsee'     => false,
            'dossierIban'      => false,
            'dossierEmployeur' => false,
            'dossierAutres'    => false,
        ];

        return $completude;
    }



    /**
     * Suppression (historisation) de l'historique des modifications sur les données perso d'un intervenant.
     *
     * @param Intervenant $intervenant
     * @param Utilisateur $destructeur
     *
     * @return $this
     */
    public function purgerDonneesPersoModif(Intervenant $intervenant, Utilisateur $destructeur)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->update(\Application\Entity\Db\IndicModifDossier::class, 't')
            ->set("t.histoDestruction", ":destruction")
            ->set("t.histoDestructeur", ":destructeur")
            ->where("t.intervenant = :intervenant")
            ->andWhere("t.histoDestruction IS NULL");

        $qb
            ->setParameter('intervenant', $intervenant)
            ->setParameter('destructeur', $destructeur)
            ->setParameter('destruction', new \DateTime());

        $qb->getQuery()->execute();

        return $this;
    }
}