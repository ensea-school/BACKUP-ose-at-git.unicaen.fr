<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\Utilisateur;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\IntervenantDossierServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
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
    use IntervenantDossierServiceAwareTrait;
    use ValidationServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;
    use SourceServiceAwareTrait;
    use AnneeServiceAwareTrait;


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



    /**
     * Recalcule la complétude du dossier intervenant pour tous les intervenants d'une année ou un intervenant donné
     *
     * @param Annee       $annee
     * @param Intervenant $intervenant
     *
     * @return Validation
     */

    public function updateCompletudeByAnnee(?Annee $annee = null, ?Intervenant $intervenant = null)
    {
        try {
            $intervenants = [];
            if ($intervenant instanceof Intervenant) {
                //Calcul de la complétude du dossier pour un intervenant
                $intervenants[] = $intervenant;
            } elseif ($annee instanceof Annee) {
                //Calcul de la complétude pour une année complète
                $serviceIntervenant = $this->getServiceIntervenant();
                $qb                 = $serviceIntervenant->finderByAnnee($annee);
                $serviceIntervenant->finderByHistorique($qb);
                $intervenants = $serviceIntervenant->getList($qb);
            }

            foreach ($intervenants as $intervenant) {
                //On récupére le dossier de l'intervenant
                $intervenantDossier = $this->getByIntervenant($intervenant);
                //On regarde si le dossier est déjà validé
                $validation = $this->getValidation($intervenant);
                if ($validation) {
                    //Si les données personnelles sont déjà validés on force la complétude à 1
                    $isComplete = 1;
                } else {
                    $isComplete = $this->isComplete($intervenantDossier);
                }
                //Si la complétude a changé alors on set la complétude et on persist/flush
                if ($isComplete <> $intervenantDossier->getCompletude()) {
                    $intervenantDossier->setCompletude($isComplete);
                    $this->getEntityManager()->persist($intervenantDossier);
                    $this->getEntityManager()->flush();
                }
            }
        } catch (\Exception $e) {
            $e->getMessage();
        }


        return true;
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
        $completudeDossierStatut                = true;

        //Complétude du statut
        if ($statutIntervenantDossier->getCode() == 'AUTRES') {
            $completudeDossierStatut = false;
        }

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
            $completudeEmail = true;
            $completudeTel   = true;
            if ($statutIntervenantDossier->getDossierEmailPerso()) {
                $completudeEmail = ($intervenantDossier->getEmailPerso() && $intervenantDossier->getEmailPro()) ? true : false;
            } else {
                $completudeEmail = ($intervenantDossier->getEmailPerso() || $intervenantDossier->getEmailPro()) ? true : false;
            }

            if ($statutIntervenantDossier->getDossierTelPerso()) {
                $completudeTel = ($intervenantDossier->getTelPerso() && $intervenantDossier->getTelPro()) ? true : false;
            } else {
                $completudeTel = ($intervenantDossier->getTelPerso() || $intervenantDossier->getTelPro()) ? true : false;
            }

            $completudeDossierContact = ($completudeTel && $completudeEmail) ? true : false;
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
        if ($statutChampsAutres) {
            foreach ($statutChampsAutres as $champ) {
                $method      = 'getAutre' . $champ->getId();
                $obligatoire = $champ->isObligatoire();
                if (empty($intervenantDossier->$method()) && $champ->isObligatoire()) {
                    $completudeDossierAutre = false;
                    break;
                }
            }
        }

        $completudeDossier = ($completudeDossierIdentite &&
            $completudeDossierIdentieComplementaire &&
            $completudeDossierAdresse &&
            $completudeDossierContact &&
            $completudeDossierInsee &&
            $completudeDossierIban &&
            $completudeDossierEmployeur &&
            $completudeDossierAutre &&
            $completudeDossierStatut) ? true : false;

        $completude = ['dossier'                       => $completudeDossier,
                       'dossierIdentite'               => $completudeDossierIdentite,
                       'dossierIdentiteComplementaire' => $completudeDossierIdentieComplementaire,
                       'dossierAdresse'                => $completudeDossierAdresse,
                       'dossierContact'                => $completudeDossierContact,
                       'dossierInsee'                  => $completudeDossierInsee,
                       'dossierIban'                   => $completudeDossierIban,
                       'dossierEmployeur'              => $completudeDossierEmployeur,
                       'dossierAutres'                 => $completudeDossierAutre,
                       'dossierStatut'                 => $completudeDossier,
        ];

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



    /**
     * Methode qui compare les données de la fiche intervenant et celle du dossier intervenant pour alimenter
     * la table INDIC_MODIF_DOSSIER (
     *
     * @param Intervenant        $intervenant
     * @param IntervenantDossier $intervenantDossier
     *
     * @return boolean
     */

    public function updateIndicModifDossier(Intervenant $intervenant, IntervenantDossier $intervenantDossier): bool
    {


        $indicModifDossierCollection = $intervenant->getIndicModifDossier();
        $indicModifDossierInProgress = [];
        $sourceOse                   = $this->getServiceSource()->getOse()->getCode();
        $sourceIntervenant           = $intervenant->getSource()->getCode();
        $em                          = $this->getEntityManager();

        /**
         * @var $indicModifDossier IndicModifDossier
         */
        foreach ($indicModifDossierCollection as $indicModifDossier) {
            if (!$indicModifDossier->getHistoDestruction()) {
                $indicModifDossierInProgress[$indicModifDossier->getAttrName()] = $indicModifDossier;
            }
        }

        $newDatas                     = [];
        $oldDatas                     = [];
        $newDatas['NOM_PATRONYMIQUE'] = ($intervenantDossier->getNomPatronymique()) ? $intervenantDossier->getNomPatronymique() : '(aucun)';
        $newDatas['NOM_USUEL']        = ($intervenantDossier->getNomUsuel()) ? $intervenantDossier->getNomUsuel() : '(aucun)';
        $newDatas['CIVILITE']         = ($intervenantDossier->getCivilite()) ? $intervenantDossier->getCivilite()->getLibelleCourt() : '(aucunà';
        $newDatas['PRENOM']           = ($intervenantDossier->getPrenom()) ? $intervenantDossier->getPrenom() : '(aucun)';
        $newDatas['DATE_NAISSANCE']   = ($intervenantDossier->getDateNaissance()) ? $intervenantDossier->getDateNaissance()->format('d/m/Y') : '(aucun)';
        $newDatas['RIB']              = $intervenantDossier->getRib();
        $intervenantDossierAdresse    = $intervenantDossier->getAdresse();
        $newDatas['ADRESSE']          = (!empty($intervenantDossierAdresse)) ? $intervenantDossierAdresse : '(aucun)';

        $oldDatas['NOM_PATRONYMIQUE'] = ($intervenant->getNomPatronymique()) ? $intervenant->getNomPatronymique() : '(aucun)';
        $oldDatas['NOM_USUEL']        = ($intervenant->getNomUsuel()) ? $intervenant->getNomUsuel() : '(aucun)';
        $oldDatas['CIVILITE']         = ($intervenant->getCivilite()) ? $intervenant->getCivilite()->getLibelleCourt() : '(aucun)';
        $oldDatas['PRENOM']           = ($intervenant->getPrenom()) ? $intervenant->getPrenom() : '(aucun)';
        $oldDatas['DATE_NAISSANCE']   = ($intervenant->getDateNaissance()) ? $intervenant->getDateNaissance()->format('d/m/Y') : '(aucun)';
        $oldDatas['RIB']              = $intervenant->getRib();
        $intervenantAdresse           = $intervenant->getAdresse();
        $oldDatas['ADRESSE']          = (!empty($intervenantAdresse)) ? $intervenantAdresse : '(aucun)';

        //On calcule les champs différents
        $diffDatas = array_diff_assoc($newDatas, $oldDatas);
        //On calcule les champs identiques
        $equalDatas = array_intersect_assoc($newDatas, $oldDatas);

        if (!empty($diffDatas)) {
            foreach ($diffDatas as $field => $value) {
                {
                    if (trim(strtolower($newDatas[$field])) <> trim(strtolower($oldDatas[$field])) && !empty($newDatas[$field])) {
                        $indicModifDossierField = (array_key_exists($field, $indicModifDossierInProgress)) ? $indicModifDossierInProgress[$field] : new IndicModifDossier();
                        $indicModifDossierField->setAttrName($field);
                        $estCreationDossier = (array_key_exists($field, $indicModifDossierInProgress)) ? 0 : 1;
                        $indicModifDossierField->setAttrOldValue($oldDatas[$field])
                            ->setAttrNewValue($newDatas[$field])
                            ->setAttrOldSourceName($sourceIntervenant)
                            ->setAttrNewSourceName($sourceOse)
                            ->setEstCreationDossier($estCreationDossier)
                            ->setIntervenant($intervenant);
                        $em->persist($indicModifDossierField);
                    }
                }
            }
        }

        //On historise les éventuelles entrées dans IndicModifDossier si les différences n'existent plus
        if (!empty($equalDatas)) {
            foreach ($equalDatas as $field => $value) {
                if (array_key_exists($field, $indicModifDossierInProgress)) {
                    $indicModifDossierField = $indicModifDossierInProgress[$field];
                    $indicModifDossierField->historiser();
                    $em->persist($indicModifDossierField);
                }
            }
        }

        $this->getEntityManager()->flush();

        return true;
    }
}