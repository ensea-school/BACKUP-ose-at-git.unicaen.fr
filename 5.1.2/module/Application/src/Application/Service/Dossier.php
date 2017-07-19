<?php

namespace Application\Service;

use Application\Entity\Db\Dossier as DossierEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Utilisateur as UtilisateurEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use Application\Entity\Db\Validation as ValidationEntity;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;

/**
 * Description of Dossier
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method DossierEntity get($id)
 * @method DossierEntity[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method DossierEntity newEntity()
 */
class Dossier extends AbstractEntityService
{
    use IntervenantAwareTrait;
    use ValidationAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return DossierEntity::class;
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
     * Enregistrement d'un dossier.
     *
     * NB: tout le travail est déjà fait via un formulaire en fait!
     * Cette méthode existe surtout pour déclencher l'événement de workflow.
     *
     * @param \Application\Entity\Db\Dossier     $dossier
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function enregistrerDossier(DossierEntity $dossier, IntervenantEntity $intervenant)
    {
        $this->getEntityManager()->persist($this->getServiceContext()->getUtilisateur());
        $this->getEntityManager()->persist($dossier);
        $this->getEntityManager()->persist($intervenant);

        $this->getEntityManager()->flush();
    }



    /**
     * Suppression d'un dossier.
     *
     * @param \Application\Entity\Db\Dossier     $dossier
     * @param \Application\Entity\Db\Intervenant $intervenant
     */
    public function supprimerDossier(DossierEntity $dossier, IntervenantEntity $intervenant)
    {
        $intervenant->setDossier(null);
        $this->getEntityManager()->remove($dossier);

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
    public function intervenantVacataireAnneesPrecedentes(IntervenantEntity $intervenant, $x = 1)
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
     * @param IntervenantEntity $intervenant
     *
     * @return ValidationEntity
     */
    public function getValidation(IntervenantEntity $intervenant)
    {
        $validation = null;
        $serviceValidation = $this->getServiceValidation();
        $qb                = $serviceValidation->finderByType(TypeValidationEntity::CODE_DONNEES_PERSO);
        $serviceValidation->finderByHistorique($qb);
        $serviceValidation->finderByIntervenant($intervenant, $qb);
        $validations = $serviceValidation->getList($qb);
        if (count($validations)) {
            $validation = current($validations);
        }
        return $validation;
    }



    /**
     * Suppression (historisation) de l'historique des modifications sur les données perso d'un intervenant.
     *
     * @param IntervenantEntity $intervenant
     * @param UtilisateurEntity $destructeur
     *
     * @return $this
     */
    public function purgerDonneesPersoModif(IntervenantEntity $intervenant, UtilisateurEntity $destructeur)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->update(\Application\Entity\Db\IndicModifDossier::class, 't')
            ->set("t.histoDestruction", ":destruction")
            ->set("t.histoDestructeur", ":destructeur")
            ->where("t.intervenant = :intervenant")
            ->andWhere("1 = compriseEntre(t.histoCreation, t.histoDestruction)");

        $qb
            ->setParameter('intervenant', $intervenant)
            ->setParameter('destructeur', $destructeur)
            ->setParameter('destruction', new \DateTime());

        $qb->getQuery()->execute();

        return $this;
    }
}