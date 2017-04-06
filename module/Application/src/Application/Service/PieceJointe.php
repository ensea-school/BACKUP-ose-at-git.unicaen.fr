<?php

namespace Application\Service;

use Application\Entity\Db\TblPieceJointeDemande;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;
use Application\Entity\Db\TypePieceJointe as TypePieceJointeEntity;
use Application\Entity\Db\PieceJointe as PieceJointeEntity;
use Application\Entity\Db\Fichier as FichierEntity;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\TypeValidation as TypeValidationEntity;
use BjyAuthorize\Exception\UnAuthorizedException;


/**
 * Description of PieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PieceJointe extends AbstractEntityService
{
    use TypeValidationAwareTrait;
    use ValidationAwareTrait;

    /**
     * @var float[]
     */
    protected $hps = [];



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return PieceJointeEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'pj';
    }



    /**
     * @param IntervenantEntity     $intervenant
     * @param TypePieceJointeEntity $type
     *
     * @return PieceJointeEntity
     */
    public function getByType(IntervenantEntity $intervenant, TypePieceJointeEntity $type)
    {
        $qb = $this->finderByType($type);
        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenant, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }



    /**
     * Retourne la liste des types de pièces jointes demandées
     *
     * @param IntervenantEntity $intervenant
     *
     * @return TypePieceJointeEntity[]
     */
    public function getTypesPiecesDemandees(IntervenantEntity $intervenant)
    {
        $dql  = "
        SELECT
          pjd, tpj
        FROM
          Application\Entity\Db\TblPieceJointeDemande pjd
          JOIN pjd.typePieceJointe tpj
        WHERE
          pjd.intervenant = :intervenant
        ";
        $lpjd = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant,
        ])->getResult();
        /* @var $lpjd TblPieceJointeDemande[] */
        $hps    = 0;
        $result = [];
        foreach ($lpjd as $pjd) {
            if ($pjd->getHeuresPourSeuil() > 0) {
                $hps = $pjd->getHeuresPourSeuil();
            } // les heures sont toutes les mêmes pour l'intervenant

            $typePieceJointe                   = $pjd->getTypePieceJointe();
            $result[$typePieceJointe->getId()] = $typePieceJointe;
        }
        $this->hps[$intervenant->getId()] = $hps;

        return $result;
    }



    /**
     * @param IntervenantEntity $intervenant
     *
     * @return float
     */
    public function getHeuresPourSeuil(IntervenantEntity $intervenant)
    {
        if (!isset($this->hps[$intervenant->getId()])) {
            $this->getTypesPiecesDemandees($intervenant);
        }

        return $this->hps[$intervenant->getId()];
    }



    /**
     * @param IntervenantEntity $intervenant
     *
     * @return \Application\Entity\Db\PieceJointe[]
     */
    public function getPiecesFournies(IntervenantEntity $intervenant)
    {
        $dql = "
        SELECT
          pjf, pj, tpj, v, f        
        FROM
          Application\Entity\Db\TblPieceJointeFournie pjf
          JOIN pjf.pieceJointe pj
          JOIN pjf.typePieceJointe tpj
          LEFT JOIN pjf.validation v
          LEFT JOIN pjf.fichier f
        WHERE
          pjf.intervenant = :intervenant
        ";
        $lpjf = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant,
        ])->getResult();

        /* @var $lpjf \Application\Entity\Db\TblPieceJointeFournie[] */

        $result = [];
        foreach ($lpjf as $pjf) {
            $pj = $pjf->getPieceJointe();
            $result[$pj->getType()->getId()] = $pj;
        }

        return $result;
    }



    /**
     * Validation d'une PJ.
     *
     * @param \Application\Entity\Db\PieceJointe $pj
     *
     * @return \Application\Entity\Db\Validation
     * @throws UnAuthorizedException
     */
    public function valider(PieceJointeEntity $pj)
    {
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ? $role->getStructure() : $pj->getIntervenant()->getStructure();

        $typeValidation = $this->getServiceTypeValidation()->getByCode(TypeValidationEntity::CODE_PIECE_JOINTE);

        $validation = $this->getServiceValidation()->newEntity($typeValidation);
        $validation->setIntervenant($pj->getIntervenant());
        $validation->setStructure($structure);

        $pj->setValidation($validation);

        $this->getEntityManager()->persist($validation);
        $this->getEntityManager()->persist($pj);
        $this->getEntityManager()->flush();

        return $validation;
    }



    /**
     * Dévalidation d'une PJ.
     *
     * @param \Application\Entity\Db\PieceJointe $pj
     *
     * @return \Application\Entity\Db\Validation Validation historisée
     * @throws UnAuthorizedException
     */
    public function devalider(PieceJointeEntity $pj)
    {
        $validation = $pj->getValidation();

        $this->getServiceValidation()->delete($validation, true);

        $pj->setValidation(null);

        $this->getEntityManager()->flush($pj);

        return $validation;
    }



    /**
     * Création si besoin de la PieceJointe et ajout des Fichiers associés.
     *
     * @param array   $files       Ex: ['tmp_name' => '/tmp/k65sd4d', 'name' => 'Image.png', 'type' => 'image/png', 'size' =>
     *                             321215]
     * @param boolean $deleteFiles Supprimer les fichiers après création de la PJ$
     *
     * @return PieceJointeEntity[]
     */
    public function ajouterFichiers($files, IntervenantEntity $intervenant, TypePieceJointeEntity $type, $deleteFiles = true)
    {
        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }

        // création si besoin d'une PieceJointe
        $pj = $this->getByType($intervenant, $type);
        /* @var $pj PieceJointeEntity */
        if (!$pj) {
            $pj = $this->newEntity()
                ->setType($type)
                ->setIntervenant($intervenant)
                ->setValidation(null);

            $this->getEntityManager()->persist($pj);
        }

        foreach ($files as $file) {
            $path          = $file['tmp_name'];
            $nomFichier    = $file['name'];
            $typeFichier   = $file['type'];
            $tailleFichier = $file['size'];

            $fichier = (new FichierEntity())
                ->setTypeMime($typeFichier)
                ->setNom($nomFichier)
                ->setTaille($tailleFichier)
                ->setContenu(file_get_contents($path))
                ->setValidation(null);

            $pj->addFichier($fichier);

            $this->getEntityManager()->persist($fichier);


            if ($deleteFiles) {
                unlink($path);
            }
        }

        $this->getEntityManager()->flush();

        return $pj;
    }



    /**
     * Suppression d'un Fichier déposé lié à une PJ.
     *
     * @param \Application\Entity\Db\Fichier     $fichier
     * @param \Application\Entity\Db\PieceJointe $pj
     *
     * @return void
     * @throws UnAuthorizedException
     */
    public function supprimerFichier(FichierEntity $fichier, PieceJointeEntity $pj)
    {
        $pj->removeFichier($fichier);
        $this->getEntityManager()->remove($fichier);

        if (!count($pj->getFichier())) {
            $this->delete($pj);
        }

        $this->getEntityManager()->flush();
    }
}