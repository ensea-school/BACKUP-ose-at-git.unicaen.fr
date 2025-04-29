<?php

namespace OffreFormation\Processus;

use Application\Processus\AbstractProcessus;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\CentreCoutEpServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use OffreFormation\Service\Traits\CheminPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\ElementModulateurServiceAwareTrait;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;
use OffreFormation\Service\Traits\VolumeHoraireEnsServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of ReconductionProcessus
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
class ReconductionProcessus extends AbstractProcessus
{

    use EtapeServiceAwareTrait;
    use ElementPedagogiqueServiceAwareTrait;
    use CheminPedagogiqueServiceAwareTrait;
    use VolumeHoraireEnsServiceAwareTrait;
    use AnneeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use CentreCoutEpServiceAwareTrait;
    use ElementModulateurServiceAwareTrait;
    use SourceServiceAwareTrait;
    use BddAwareTrait;

    protected $etapeService;

    protected $elementPedagogiqueService;

    protected $cheminPedagogiqueService;

    protected $volumeHoraireEnsService;

    protected $anneeService;

    protected $contextService;

    protected $centreCoutEpService;


    public function __construct()
    {
        $this->etapeService = $this->getServiceEtape();
        $this->elementPedagogiqueService = $this->getServiceElementPedagogique();
        $this->cheminPedagogiqueService = $this->getServiceCheminPedagogique();
        $this->volumeHoraireEnsService = $this->getServiceVolumeHoraireEns();
        $this->anneeService = $this->getServiceAnnee();
        $this->contextService = $this->getServiceContext();
        $this->centreCoutEpService = $this->getServiceCentreCoutEp();
        $this->elementModulateurService = $this->getServiceElementModulateur();
    }


    public function reconduction($datas)
    {
        if (empty($datas['element']) && empty($datas['etape'])) {
            throw new \Exception('Aucune donnée à reconduire');
        }

        if (empty($datas['etape'])) {
            $datas['etape'] = [];
        }
        $etapeOfElements = array_keys($datas['element']);
        $etapeManquante = array_diff($etapeOfElements, $datas['etape']);
        $etapes = array_merge($datas['etape'], $etapeManquante);

        if (!empty($etapes)) {
            $anneeEnCours = $this->contextService->getAnnee();
            $em = $this->getEntityManager();
            $dateDuJour = new \DateTime();
            $dateDuJour->format('d/m/Y');
            foreach ($etapes as $idEtape) {
                if (in_array($idEtape, $etapeManquante)) {
                    if (!array_key_exists($idEtape, $datas['mappingEtape'])) {
                        throw new \Exception('Impossible de reconduire les éléments sélectionné');
                    }
                    $idEtapeN1 = $datas['mappingEtape'][$idEtape];
                    $etapeReconduit = $this->etapeService->get($idEtapeN1);
                } else {
                    //Check si l'étape reconduite n'a pas déjà été supprimé
                    $etapeEnCours = $this->etapeService->get($idEtape);
                    $etapeReconduit = $this->getServiceEtape()->getByCode(
                        $etapeEnCours->getCode(),
                        $this->contextService->getAnneeSuivante()
                    );
                    if (!$etapeReconduit) {
                        $etapeReconduit = $this->etapeService->newEntity();
                        $etapeReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                        $etapeReconduit->setSpecifiqueEchanges(0);
                        $etapeReconduit->setLibelle($etapeEnCours->getLibelle());
                        $etapeReconduit->setCode($etapeEnCours->getCode());
                        $etapeReconduit->setTypeFormation(($etapeEnCours->getTypeFormation()));
                        $etapeReconduit->setStructure($etapeEnCours->getStructure());
                        $etapeReconduit->setDomaineFonctionnel($etapeEnCours->getDomaineFonctionnel());
                        $etapeReconduit->setSourceCode($etapeEnCours->getSourceCode());
                    } else {
                        //sinon dehistoriser l'ancienne étape détruite
                        $etapeReconduit->dehistoriser();
                        //Force source ose
                        $etapeReconduit->setSource($this->getServiceSource()->getOse());
                    }


                    $em->persist($etapeReconduit);
                }
                //Reconduction des éléments pédagogiques pour cette étape
                if (array_key_exists($idEtape, $datas['element'])) {
                    foreach ($datas['element'][$idEtape] as $idElement) {
                        $elementEnCours = $this->elementPedagogiqueService->get($idElement);
                        //Check si element reconduit n'a pas était déjà supprimmé
                        $elementReconduit = $this->elementPedagogiqueService->getByCode(
                            $elementEnCours->getCode(),
                            $this->contextService->getAnneeSuivante());
                        //Si élément n'a jamais existé en base création d'un nouvelle entité.
                        if (!$elementReconduit) {
                            $elementReconduit = $this->elementPedagogiqueService->newEntity();
                            $elementReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                            $elementReconduit->setEtape($etapeReconduit);
                            $elementReconduit->setLibelle($elementEnCours->getLibelle());
                            $elementReconduit->setSourceCode($elementEnCours->getSourceCode());
                            $elementReconduit->setCode($elementEnCours->getCode());
                            $elementReconduit->setStructure($etapeReconduit->getStructure());
                            $elementReconduit->setFa($elementEnCours->getFa());
                            $elementReconduit->setFc($elementEnCours->getFc());
                            $elementReconduit->setFi($elementEnCours->getFi());
                            $elementReconduit->setTauxFa($elementEnCours->getTauxFa());
                            $elementReconduit->setTauxFi($elementEnCours->getTauxFi());
                            $elementReconduit->setTauxFc($elementEnCours->getTauxFc());
                            $elementReconduit->setTauxFoad($elementEnCours->getTauxFoad());
                            $elementReconduit->setDiscipline($elementEnCours->getDiscipline());
                            $elementReconduit->setPeriode($elementEnCours->getPeriode());
                        } else {
                            //sinon dehistoriser l'ancienne élément détruit
                            $elementReconduit->dehistoriser();
                            //Force source ose
                            $elementReconduit->setSource($this->getServiceSource()->getOse());
                        }
                        //ajout de l'élément à l'étape
                        $etapeReconduit->addElementPedagogique($elementReconduit);
                        $em->persist($elementReconduit);
                        //Reconduction des chemins pédagogiques
                        $cheminsPedagogique = $elementEnCours->getCheminPedagogique();
                        foreach ($cheminsPedagogique as $chemin) {
                            $cheminReconduit = $this->cheminPedagogiqueService->newEntity();
                            $cheminReconduit->setElementPedagogique($elementReconduit);
                            $cheminReconduit->setEtape($etapeReconduit);
                            $cheminReconduit->setSource($chemin->getSource());
                            $cheminReconduit->setOrdre($chemin->getOrdre());
                            $em->persist($cheminReconduit);
                        }
                        //Reconduction des volumes horaires
                        $volumesHoraire = $elementEnCours->getVolumeHoraireEns();
                        foreach ($volumesHoraire as $volume) {
                            $volumeReconduit = $this->volumeHoraireEnsService->newEntity();
                            $volumeReconduit->setSource($this->getServiceSource()->getOse());
                            $volumeReconduit->setSourceCode(uniqid('vher-'));
                            $volumeReconduit->setTypeIntervention($volume->getTypeIntervention());
                            $volumeReconduit->setGroupes($volume->getGroupes());
                            $volumeReconduit->setHeures($volume->getHeures());
                            $volumeReconduit->setElementPedagogique($elementReconduit);
                            $elementReconduit->addVolumeHoraireEns($volumeReconduit);
                            $em->persist($volumeReconduit);
                        }
                        unset($elementReconduit, $elementEnCours);
                    }
                }

                $em->flush();
                unset($etapeEnCours, $etapeReconduit);
            }
        } else {
            throw new \Exception('Aucune donnée à reconduire');
        }

        return true;
    }


    /**
     * Reconduit les centres de coûts des élements pédagogiques d'une sélection d'étapes
     *
     * @param array $etapes
     *
     * @return integer
     */


    public function reconduireCCFormation($etapes)
    {
        //Récupération des étapes dont il faut reconduire les cc
        $etapes_codes = array_keys($etapes);
        $sql = '
        SELECT 
            *            
        FROM 
            v_reconduction_centre_cout
        WHERE
          annee_id = ?
          AND etape_code IN (?)';

        $connection = $this->getEntityManager()->getConnection();
        $ccepN = $connection->fetchAllAssociative($sql, [$this->getServiceContext()->getAnnee()->getId(), $etapes_codes], [ParameterType::INTEGER, Connection::PARAM_STR_ARRAY]);
        $nbCCReconduit = 0;
        foreach ($ccepN as $key => $value) {
            //Récupération de la dernière incrémentation ID CCEP
            $nextSequence = $this->getNextSequence('CENTRE_COUT_EP_ID_SEQ');
            $stmt = $connection->insert('centre_cout_ep',
                ['id'                     => $nextSequence,
                 'centre_cout_id'         => $value['CENTRE_COUT_ID'],
                 'element_pedagogique_id' => $value['NEW_EP_ID'],
                 'type_heures_id'         => $value['TYPE_HEURES_ID'],
                 'source_id'              => $this->getServiceSource()->getOse()->getId(),
                 'source_code'            => uniqid($value['CENTRE_COUT_ID'] . '_' . $value['TYPE_HEURES_ID'] . '_' . $value['NEW_EP_ID']),
                 'histo_createur_id'      => $this->getServiceContext()->getUtilisateur()->getId(),
                 'histo_modificateur_id'  => $this->getServiceContext()->getUtilisateur()->getId(),
                ]);

            $nbCCReconduit++;
        }

        return $nbCCReconduit;
    }


    public function reconduireModulateurFormation($etapes)
    {
        //Récupération des étapes dont il faut reconduire les cc
        $etapes_codes = array_keys($etapes);
        $sql = '
        SELECT 
            *            
        FROM 
            v_reconduction_modulateur
        WHERE
            annee_id = ?
            AND etape_code IN (?)';

        $connection = $this->getEntityManager()->getConnection();
        $mepN = $connection->fetchAllAssociative($sql, [$this->getServiceContext()->getAnnee()->getId(), $etapes_codes], [ParameterType::INTEGER, Connection::PARAM_STR_ARRAY]);
        $nbMReconduit = 0;


        foreach ($mepN as $key => $value) {
            //Récupération de la dernière incrémentation ID EM
            $nextSequence = $this->getNextSequence('ELEMENT_MODULATEUR_ID_SEQ');

            $stmt = $connection->insert('element_modulateur',
                ['id'                    => $nextSequence,
                 'element_id'            => $value['NEW_EP_ID'],
                 'modulateur_id'         => $value['MODULATEUR_ID'],
                 'histo_createur_id'     => $this->getServiceContext()->getUtilisateur()->getId(),
                 'histo_modificateur_id' => $this->getServiceContext()->getUtilisateur()->getId(),
                ]);
            $nbMReconduit++;
        }

        $this->getBdd()->materializedView()->refresh('MV_MODULATEUR');

        return $nbMReconduit;
    }


    public function getNextSequence($sequenceName = '')
    {
        $connection = $this->getEntityManager()->getConnection();
        $stmt = $connection->executeQuery('SELECT ' . $sequenceName . '.NEXTVAL val FROM DUAL');
        $result = $stmt->fetchAssociative();

        return $result['VAL'];
    }

}
