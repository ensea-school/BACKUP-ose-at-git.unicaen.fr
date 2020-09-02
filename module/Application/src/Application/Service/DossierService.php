<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;

/**
 * Description of Intervenant Dossier
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Dossier get($id)
 * @method Dossier[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Dossier newEntity()
 */
class DossierService extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;

    /**
     * @var Dossier[]
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
        return 'd';
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
        foreach ($this->getList($qb) as $dossier) {
            return $dossier;
        }
        $dossier                             = $this->newEntity()->fromIntervenant($intervenant);
        $this->dcache[$intervenant->getId()] = $dossier;

        return $dossier;
    }



    /**
     * Enregistrement d'un dossier.
     *
     * NB: tout le travail est déjà fait via un formulaire en fait!
     * Cette méthode existe surtout pour déclencher l'événement de workflow.
     *
     * @param \Application\Entity\Db\IntervenantDossier $dossier
     */
    public function enregistrerDossier(IntervenantDossier $dossier)
    {
        $this->getEntityManager()->persist($this->getServiceContext()->getUtilisateur());
        $this->getEntityManager()->persist($dossier);
        $this->getEntityManager()->persist($dossier->getIntervenant());

        $this->getEntityManager()->flush();
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


        for ($i = 1; $i <= $x; $i++) {

            $iPrec = $this->getServiceIntervenant()->getPrecedent($intervenant, -$i);

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



    public function getCompletude(IntervenantDossier $intervenantDossier)
    {
        $statutIntervenantDossier = $intervenantDossier->getStatut();

        $completudeDossierIdentieComplementaire = true;
        $completudeDossierAdresse               = true;
        $completudeDossierContact               = true;
        $completudeDossierInsee                 = true;
        $completudeDossierIban                  = true;
        $completudeDossierEmployeur             = true;
        $completudeDossierAutre                 = true;

        //Complétude de l'identite
        $completudeDossierIdentite = ($intervenantDossier->getCivilite() &&
            $intervenantDossier->getNomUsuel() &&
            $intervenantDossier->getPrenom()) ? true : false;

        if ($statutIntervenantDossier->getDossierIdentiteComplementaire()) {
            $completudeDossierIdentieComplementaire = ($intervenantDossier->getDateNaissance() &&
                $intervenantDossier->getPaysNaissance() &&
                (($intervenantDossier->getPaysNaissance()->getLibelle() == 'FRANCE') ? $intervenantDossier->getDepartementNaissance() : true) &&
                $intervenantDossier->getCommuneNaissance()) ? true : false;
        }

        //Complétude de l'adresse
        $completudeAdressePart1 = (($intervenantDossier->getAdressePrecisions() ||
            $intervenantDossier->getAdresseLieuDit() ||
            ($intervenantDossier->getAdresseVoie() && $intervenantDossier->getAdresseNumero()))) ? true : false;

        $completudeAdressePart2 = ($intervenantDossier->getAdresseCommune() &&
            $intervenantDossier->getAdresseCodePostal() &&
            $intervenantDossier->getAdressePays()) ? true : false;

        if ($statutIntervenantDossier->getDossierAdresse()) {
            $completudeDossierAdresse = ($completudeAdressePart1 && $completudeAdressePart2) ? true : false;
        }

        //Complétude de contact
        if ($statutIntervenantDossier->getDossierContact()) {
            $completudeDossierContact = (($intervenantDossier->getEmailPerso() || $intervenantDossier->getEmailPro()) &&
                ($intervenantDossier->getTelPerso() || $intervenantDossier->getTelPro())) ? true : false;
        }

        //Complétude Insee
        if ($statutIntervenantDossier->getDossierInsee()) {
            $completudeDossierInsee = ($intervenantDossier->getNumeroInsee()) ? true : false;
        }

        //Complétude Iban
        if ($statutIntervenantDossier->getDossierIban()) {
            $completudeDossierIban = (($intervenantDossier->getIBAN() && $intervenantDossier->getBIC()) || $intervenantDossier->isRibHorsSepa()) ? true : false;
        }

        //Complètude Employeur
        if ($statutIntervenantDossier->getDossierEmployeur()) {
            $completudeDossierEmployeur = ($intervenantDossier->getEmployeur()) ? true : false;
        }

        //Complétude Autres
        $statut             = $intervenantDossier->getStatut();
        $champsAutres       = $intervenantDossier->getStatut()->getChampsAutres();
        $statutChampsAutres = ($intervenantDossier->getStatut()) ? $intervenantDossier->getStatut()->getChampsAutres() : [];
        $count              = count($champsAutres);
        foreach ($statutChampsAutres as $champ) {
            $method      = 'getAutre' . $champ->getId();
            $obligatoire = $champ->isObligatoire();
            if (empty($intervenantDossier->$method()) && $champ->isObligatoire()) {
                $completudeDossierAutre = false;
                break;
            }
        }

        $completudeDossier = ($completudeDossierIdentite &&
            $completudeDossierIdentieComplementaire &&
            $completudeDossierAdresse &&
            $completudeDossierContact &&
            $completudeDossierInsee &&
            $completudeDossierIban &&
            $completudeDossierEmployeur &&
            $completudeDossierAutre) ? true : false;

        $completude = ['dossier'                       => $completudeDossier,
                       'dossierIdentite'               => $completudeDossierIdentite,
                       'dossierIdentiteComplementaire' => $completudeDossierIdentieComplementaire,
                       'dossierAdresse'                => $completudeDossierAdresse,
                       'dossierContact'                => $completudeDossierContact,
                       'dossierInsee'                  => $completudeDossierInsee,
                       'dossierIban'                   => $completudeDossierIban,
                       'dossierEmployeur'              => $completudeDossierEmployeur,
                       'dossierAutres'                 => $completudeDossierAutre,];

        return $completude;
    }



    public function isComplete(IntervenantDossier $intervenantDossier)
    {
        $completude = $this->getCompletude($intervenantDossier);

        foreach ($completude as $v) {
            if ($v === false) {
                return false;
            }
        }

        return true;
    }



    public function getTauxCompletude(IntervenantDossier $intervenantDossier)
    {
        $completude = $this->isComplete($intervenantDossier);
        //calcul du taux
        $tauxCompletude = 100;
        foreach ($completude as $value) {
            if (!$value) {
                $tauxCompletude -= floor(100 / count($completude));
            }
        }

        return $tauxCompletude;
    }



    /**
     * Suppression (historisation) de l'historique des modifications sur les données perso d'un intervenant.
     *
     * @param Intervenant $intervenant
     * @param Utilisateur $destructeur
     *
     * @return $this
     */
    public
    function purgerDonneesPersoModif(Intervenant $intervenant, Utilisateur $destructeur)
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