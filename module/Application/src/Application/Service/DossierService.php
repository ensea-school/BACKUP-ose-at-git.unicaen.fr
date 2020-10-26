<?php

namespace Application\Service;

use Application\Entity\Db\Annee;
use Application\Entity\Db\IndicModifDossier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\IntervenantDossier;
use Application\Entity\Db\TblDossier;
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



    public function getCompletude(IntervenantDossier $intervenantDossier)
    {

        $qb = $this->getEntityManager()->getRepository('Application\Entity\Db\TblDossier')->createQueryBuilder('tbld');
        $qb->where("tbld.intervenant = :intervenant");
        $qb->setParameter('intervenant', $intervenantDossier->getIntervenant());
        $tblDossierIntervenant = $qb->getQuery()->setMaxResults(1)->getSingleResult();;
        /**
         * @var TblDossier $tblDossierIntervenant
         */

        $completudeDossierIdentite               = $tblDossierIntervenant->getCompletudeIdentite();
        $completudeDossierIdentiteComplementaire = $tblDossierIntervenant->getCompletudeIdentiteComp();
        $completudeDossierAdresse                = $tblDossierIntervenant->getCompletudeAdresse();
        $completudeDossierContact                = $tblDossierIntervenant->getCompletudeContact();
        $completudeDossierInsee                  = $tblDossierIntervenant->getCompletudeInsee();
        $completudeDossierIban                   = $tblDossierIntervenant->getCompletudeIban();
        $completudeDossierEmployeur              = $tblDossierIntervenant->getCompletudeEmployeur();
        $completudeDossierAutre                  = $tblDossierIntervenant->getCompletudeAutres();
        $completudeDossierStatut                 = $tblDossierIntervenant->getCompletudeStatut();


        $completudeDossier = ($completudeDossierIdentite &&
            $completudeDossierIdentiteComplementaire &&
            $completudeDossierAdresse &&
            $completudeDossierContact &&
            $completudeDossierInsee &&
            $completudeDossierIban &&
            $completudeDossierEmployeur &&
            $completudeDossierAutre &&
            $completudeDossierStatut) ? true : false;

        $completude = ['dossier'                       => $completudeDossier,
                       'dossierIdentite'               => $completudeDossierIdentite,
                       'dossierIdentiteComplementaire' => $completudeDossierIdentiteComplementaire,
                       'dossierAdresse'                => $completudeDossierAdresse,
                       'dossierContact'                => $completudeDossierContact,
                       'dossierInsee'                  => $completudeDossierInsee,
                       'dossierIban'                   => $completudeDossierIban,
                       'dossierEmployeur'              => $completudeDossierEmployeur,
                       'dossierAutres'                 => $completudeDossierAutre,
                       'dossierStatut'                 => $completudeDossierStatut,
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