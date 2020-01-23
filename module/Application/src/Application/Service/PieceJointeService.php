<?php

namespace Application\Service;

use Application\Entity\Db\TblPieceJointeDemande;
use Application\Service\Traits\TypeValidationServiceAwareTrait;
use Application\Service\Traits\ValidationServiceAwareTrait;
use Application\Entity\Db\TypePieceJointe;
use Application\Entity\Db\PieceJointe;
use Application\Entity\Db\Fichier;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\TypeValidation;
use BjyAuthorize\Exception\UnAuthorizedException;


/**
 * Description of PieceJointe
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class PieceJointeService extends AbstractEntityService
{
    use TypeValidationServiceAwareTrait;
    use ValidationServiceAwareTrait;

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
        return PieceJointe::class;
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
     * @param Intervenant     $intervenant
     * @param TypePieceJointe $type
     *
     * @return PieceJointe
     */
    public function getByType(Intervenant $intervenant, TypePieceJointe $type)
    {
        $qb = $this->finderByType($type);
        $this->finderByHistorique($qb);
        $this->finderByIntervenant($intervenant, $qb);

        return $qb->getQuery()->getOneOrNullResult();
    }



    /**
     * Retourne la liste des types de pièces jointes demandées
     *
     * @param Intervenant $intervenant
     *
     * @return TypePieceJointe[]
     */
    public function getTypesPiecesDemandees(Intervenant $intervenant)
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
     * @param Intervenant $intervenant
     *
     * @return float
     */
    public function getHeuresPourSeuil(Intervenant $intervenant)
    {
        if (!isset($this->hps[$intervenant->getId()])) {
            $this->getTypesPiecesDemandees($intervenant);
        }

        return $this->hps[$intervenant->getId()];
    }



    /**
     * @param Intervenant $intervenant
     *
     * @return \Application\Entity\Db\PieceJointe[]
     */
    public function getPiecesFournies(Intervenant $intervenant)
    {

        $dql  = "
        SELECT
          pjf, pj, tpj, v, f        
        FROM
          Application\Entity\Db\TblPieceJointeFournie pjf
          JOIN pjf.pieceJointe pj
          JOIN pjf.typePieceJointe tpj
          LEFT JOIN pjf.validation v
          LEFT JOIN pjf.fichier f
        WHERE
          pjf.codeIntervenant = :intervenant
        ";
        //TODO modifier ici la requette pour qu'elle prenne en compte la validité de la PJ
        $lpjf = $this->getEntityManager()->createQuery($dql)->setParameters([
            'intervenant' => $intervenant->getCode(),
            //'annee'       => $intervenant->getAnnee()->getId(),
        ])->getResult();

        /* @var $lpjf \Application\Entity\Db\TblPieceJointeFournie[] */

        $result = [];
        foreach ($lpjf as $pjf) {
            $pj                              = $pjf->getPieceJointe();
            $fichier = $pjf->getFichier();
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
    public function valider(PieceJointe $pj)
    {
        $role      = $this->getServiceContext()->getSelectedIdentityRole();
        $structure = $role->getStructure() ? $role->getStructure() : $pj->getIntervenant()->getStructure();

        $typeValidation = $this->getServiceTypeValidation()->getByCode(TypeValidation::CODE_PIECE_JOINTE);

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
    public function devalider(PieceJointe $pj)
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
     * @return PieceJointe[]
     */
    public function ajouterFichiers($files, Intervenant $intervenant, TypePieceJointe $type, $deleteFiles = true)
    {
        if (!$files) {
            throw new \LogicException("Aucune donnée sur les fichiers spécifiée.");
        }

        // création si besoin d'une PieceJointe
        $pj = $this->getByType($intervenant, $type);
        /* @var $pj PieceJointe */
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

            $fichier = (new Fichier())
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
    public function supprimerFichier(Fichier $fichier, PieceJointe $pj)
    {
        $pj->removeFichier($fichier);
        $this->getEntityManager()->remove($fichier);

        if (!count($pj->getFichier())) {
            $this->delete($pj);
        }

        $this->getEntityManager()->flush();
    }
}