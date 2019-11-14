<?php

namespace Application\Processus;

use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\VolumeHoraireEns;
use Application\Service\AnneeService;
use Application\Service\CheminPedagogiqueService;
use Application\Service\ContextService;
use Application\Service\ElementPedagogiqueService;
use Application\Service\EtapeService;
use Application\Service\VolumeHoraireEnsService;
use Zend\Debug\Debug;
use Zend\Stdlib\Parameters;

/**
 * Description of ReconductionProcessus
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
class ReconductionProcessus extends AbstractProcessus
{
    protected $etapeService;
    protected $elementPedagogiqueService;
    protected $cheminPedagogiqueService;
    protected $volumeHoraireEnsService;
    protected $anneeService;
    protected $contextService;


    public function __construct(EtapeService $etapeService,
                                ElementPedagogiqueService $elementPedagogiqueService,
                                CheminPedagogiqueService $cheminPedagogiqueService,
                                VolumeHoraireEnsService $volumeHoraireEnsService,
                                AnneeService $anneeService,
                                ContextService $contextService)
    {
        $this->etapeService = $etapeService;
        $this->elementPedagogiqueService = $elementPedagogiqueService;
        $this->cheminPedagogiqueService = $cheminPedagogiqueService;
        $this->volumeHoraireEnsService = $volumeHoraireEnsService;
        $this->anneeService = $anneeService;
        $this->contextService = $contextService;
    }

    public function reconduction(Parameters $datas)
    {
        if(empty($datas['element']) && empty($datas['etape']))
        {
            Throw new \Exception('Aucune donnée à reconduire');
        }

        if(empty($datas['etape']))
        {
            $datas['etape'] = [];
        }
        $etapeOfElements = array_keys($datas['element']);
        $etapeManquante = array_diff($etapeOfElements,$datas['etape']);
        $etapes = array_merge($datas['etape'], $etapeManquante);

        if(!empty($etapes))
        {
            $anneeEnCours = $this->contextService->getAnnee();
            $em = $this->getEntityManager();
            $dateDuJour = new \DateTime();
            $dateDuJour->format('d/m/Y');
            foreach($etapes as $idEtape)
            {
                if(in_array($idEtape, $etapeManquante))
                {
                    if(!array_key_exists($idEtape, $datas['mappingEtape']))
                    {
                        Throw new \Exception('Impossible de reconduire les éléments sélectionné');
                    }
                    $idEtapeN1 = $datas['mappingEtape'][$idEtape];
                    $etapeReconduit = $this->etapeService->get($idEtapeN1);
                }
                else{

                    $etapeEnCours = $this->etapeService->get($idEtape);
                    $etapeReconduit= clone $etapeEnCours;
                    $etapeReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                    $etapeReconduit->setSpecifiqueEchanges(false);
                    $etapeReconduit->setHistoCreation($dateDuJour);
                    $em->persist($etapeReconduit);
                }
                //Reconduction des éléments pédagogiques pour cette étape
                if(array_key_exists($idEtape, $datas['element']))
                {
                    foreach($datas['element'][$idEtape] as $idElement)
                    {
                        $elementEnCours = $this->elementPedagogiqueService->get($idElement);
                        $elementReconduit = clone $elementEnCours;
                        $elementReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                        $elementReconduit->setEtape($etapeReconduit);
                        $elementReconduit->setHistoCreation($dateDuJour);
                        //ajout de l'élément à l'étape
                        $etapeReconduit->addElementPedagogique($elementReconduit);
                        $em->persist($elementReconduit);
                        //Reconduction des chemins pédagogiques
                        $cheminsPedagogique = $elementEnCours->getCheminPedagogique();
                        foreach($cheminsPedagogique as $chemin)
                        {
                            $cheminReconduit = clone $chemin;
                            $cheminReconduit->setElementPedagogique($elementReconduit);
                            $cheminReconduit->setEtape($etapeReconduit);
                            $cheminReconduit->setHistoCreation($dateDuJour);
                            $em->persist($cheminReconduit);
                        }
                        //Reconduction des volumes horaires
                        $volumesHoraire = $elementEnCours->getVolumeHoraireEns();
                        foreach($volumesHoraire as $volume)
                        {
                            $volumeReconduit = clone $volume;
                            $volumeReconduit->setHistoCreation($dateDuJour);
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
        }
        else{
            Throw new \Exception('Aucune donnée à reconduire');
        }

      return true;
    }

}