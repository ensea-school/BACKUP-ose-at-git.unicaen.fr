<?php

namespace Application\Processus;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Service;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Application\Service\Traits\TypeValidationAwareTrait;
use Application\Service\Traits\ValidationAwareTrait;


/**
 * Description of ValidationProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class ValidationProcessus extends AbstractProcessus
{
    use TypeValidationAwareTrait;
    use ValidationAwareTrait;

    public function getValidationsEnseignement( Intervenant $intervenant, Structure $structure=null )
    {
        $typeValidation = $this->getServiceTypeValidation()->getEnseignement();

        $dql = "
        SELECT
          v
        FROM
          Application\Entity\Db\Validation v
        WHERE
          v.intervenant = :intervenant
          AND v.typeValidation = :typeValidation
          ".($structure ? 'AND v.structure = :structure' : '')."
        ORDER BY
          v.histoCreation
        ";

        $params = compact(
            'intervenant', 'typeValidation'
        );
        if ($structure){
            $params['structure'] = $structure;
        }
        $res = $this->getEntityManager()->createQuery($dql)->setParameters( $params )->getResult();
        $validations = [];
        foreach( $res as $v ){
            $validations[$v->getId()] = $v;
        }
        return $validations;
    }



    /**
     * @param TypeVolumehoraire $typeVolumeHoraire
     * @param Intervenant $intervenant
     * @param Validation|null   $validation
     * @param Structure|null $structure
     * @param boolean $detach
     *
     * @return Service[]
     */
    public function getServices(TypeVolumeHoraire $typeVolumeHoraire, Intervenant $intervenant, Validation $validation = null, Structure $structure = null, $detach=true)
    {
        $services = [];

        $fValidation = $validation ? "AND vvh = :validation" : "AND SIZE(vh.validation)=0";
        $fStructure = $structure ? "AND str = :structure" : '';


        $dql   = "
        SELECT
          s, ep, vh, str, i, evh, tvh
        FROM
          Application\Entity\Db\Service s
          JOIN s.volumeHoraire      vh
          JOIN s.elementPedagogique ep
          JOIN ep.structure         str
          JOIN s.intervenant        i
          JOIN vh.etatVolumeHoraire evh
          JOIN vh.typeVolumeHoraire tvh
          LEFT JOIN vh.validation        vvh
        WHERE
          i = :intervenant
          AND 1 = compriseEntre(s.histoCreation, s.histoDestruction)
          AND 1 = compriseEntre(vh.histoCreation, vh.histoDestruction)
          AND vh.typeVolumeHoraire = :typeVolumeHoraire 
          AND s.intervenant = :intervenant
          $fValidation
          $fStructure
        ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters( compact('intervenant','typeVolumeHoraire') );
        if ($validation) {
            $query->setParameter('validation', $validation);
        }
        if ($structure) {
            $query->setParameter('structure', $structure);
        }
        $res = $query->execute();

        foreach ($res as $service) {
            /* @var $service \Application\Entity\Db\Service */
            if ($detach){
                $this->getEntityManager()->detach($service); // INDISPENSABLE si on requête N fois la même entité avec des critères différents
            }
            $service->setTypeVolumeHoraire($typeVolumeHoraire);
            $services[$service->getId()] = $service;
        }

        return $services;
    }


    
    public function getStructureValidation( Service $service )
    {
        if (!$service->getTypeVolumeHoraire()){
            throw new \LogicException('Le type de volume horaire du service n\a pas été spécifié.');
        }else{
            $typeVolumehoraire = $service->getTypeVolumeHoraire();
        }

        $intervenant = $service->getIntervenant();

        $structureAffectation = null;
        if ($intervenant && $intervenant->estPermanent()){
            $structureAffectation = $intervenant->getStructure();
        }

        $structureEnseignement = null;
        if ($ep = $service->getElementPedagogique()){
            $structureEnseignement = $ep->getStructure();
        }

        if ($typeVolumehoraire->isPrevu()){
            $structureValidation = $structureAffectation ?: $structureEnseignement;
        }elseif($typeVolumehoraire->isRealise()){
            $structureValidation = $structureEnseignement ?: $structureAffectation;
        }

        return $structureValidation;
    }



    /**
     * @param Intervenant $intervenant
     * @param Structure   $structure
     *
     * @return Validation
     */
    public function creerValidationServices( Intervenant $intervenant, Structure $structure )
    {
        $typeValidation = $this->getServiceTypeValidation()->getEnseignement();

        $validation = $this->getServiceValidation()->newEntity($typeValidation)
            ->setIntervenant($intervenant)
            ->setStructure($structure);

        return $validation;
    }



    /**
     * @param TypeVolumeHoraire $typeVolumeHoraire
     * @param Validation       $validation
     *
     * @return self
     */
    public function enregistrerValidationServices( TypeVolumeHoraire $typeVolumeHoraire, Validation $validation )
    {
        $services = $this->getServices($typeVolumeHoraire, $validation->getIntervenant(), null, $validation->getStructure(), false );

        foreach ($services as $s) {
            foreach ($s->getVolumeHoraire() as $vh) { /* @var $vh \Application\Entity\Db\VolumeHoraire */
                $validation->addVolumeHoraire($vh);
            }
        }
        $this->getServiceValidation()->save($validation);

        return $this;
    }



    /**
     * @param Validation $validation
     *
     * @return $this
     */
    public function devaliderServices( Validation $validation )
    {
        $this->getServiceValidation()->delete($validation);

        return $this;
    }
}