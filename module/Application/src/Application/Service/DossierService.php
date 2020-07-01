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
     * @return Dossier|null
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
     * @param \Application\Entity\Db\Dossier $dossier
     */
    public function enregistrerDossier(Dossier $dossier)
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
            $intervenantDossier->getAdresseCodePostal() &&
            $intervenantDossier->getAdressePays()) ? true : false;

        $completudeDossierAdresse = ($completudeAdressePart1 && $completudeAdressePart2) ? true : false;

        //Complétude de contact
        $completudeDossierContact = (($intervenantDossier->getEmailPerso() || $intervenantDossier->getEmailPro()) &&
            ($intervenantDossier->getTelPerso() || $intervenantDossier->getTelPro)) ? true : false;

        //Complétude Insee
        $completudeDossierInsee = ($intervenantDossier->getNumeroInsee()) ? true : false;

        //Complétude Iban
        $completudeDossierIban = true;
        //Complètude Employeur
        $completudeDossierEmployeur = true;
        //Complétude Autres
        $completudeDossierAutre = true;

        $completudeDossier = ($completudeDossierIdentie &&
            $completudeDossierAdresse &&
            $completudeDossierContact &&
            $completudeDossierInsee &&
            $completudeDossierIban &&
            $completudeDossierEmployeur &&
            $completudeDossierAutre) ? true : false;

        $completude = [
            'dossier'          => $completudeDossier,
            'dossierIdentite'  => $completudeDossierIdentie,
            'dossierAdresse'   => $completudeDossierAdresse,
            'dossierContact'   => $completudeDossierContact,
            'dossierInsee'     => $completudeDossierInsee,
            'dossierIban'      => $completudeDossierIban,
            'dossierEmployeur' => $completudeDossierEmployeur,
            'dossierAutres'    => $completudeDossierAutre,
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