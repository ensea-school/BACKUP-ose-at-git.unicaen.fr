<?php

namespace Application\Processus;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\CheminPedagogiqueServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use Application\Service\Traits\EtapeServiceAwareTrait;
use Application\Service\Traits\VolumeHoraireEnsServiceAwareTrait;
use Zend\Stdlib\Parameters;

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

    protected $etapeService;

    protected $elementPedagogiqueService;

    protected $cheminPedagogiqueService;

    protected $volumeHoraireEnsService;

    protected $anneeService;

    protected $contextService;



    public function __construct()
    {
        $this->etapeService              = $this->getServiceEtape();
        $this->elementPedagogiqueService = $this->getServiceElementPedagogique();
        $this->cheminPedagogiqueService  = $this->getServiceCheminPedagogique();
        $this->volumeHoraireEnsService   = $this->getServiceVolumeHoraireEns();
        $this->anneeService              = $this->getServiceAnnee();
        $this->contextService            = $this->getServiceContext();
    }



    public function reconduction(Parameters $datas)
    {
        if (empty($datas['element']) && empty($datas['etape'])) {
            Throw new \Exception('Aucune donnée à reconduire');
        }

        if (empty($datas['etape'])) {
            $datas['etape'] = [];
        }
        $etapeOfElements = array_keys($datas['element']);
        $etapeManquante  = array_diff($etapeOfElements, $datas['etape']);
        $etapes          = array_merge($datas['etape'], $etapeManquante);

        if (!empty($etapes)) {
            $anneeEnCours = $this->contextService->getAnnee();
            $em           = $this->getEntityManager();
            $dateDuJour   = new \DateTime();
            $dateDuJour->format('d/m/Y');
            foreach ($etapes as $idEtape) {
                if (in_array($idEtape, $etapeManquante)) {
                    if (!array_key_exists($idEtape, $datas['mappingEtape'])) {
                        Throw new \Exception('Impossible de reconduire les éléments sélectionné');
                    }
                    $idEtapeN1      = $datas['mappingEtape'][$idEtape];
                    $etapeReconduit = $this->etapeService->get($idEtapeN1);
                } else {

                    $etapeEnCours   = $this->etapeService->get($idEtape);
                    $etapeReconduit = $this->etapeService->newEntity();
                    $etapeReconduit->setAnnee($this->anneeService->getSuivante($anneeEnCours));
                    $etapeReconduit->setSpecifiqueEchanges(0);
                    $etapeReconduit->setLibelle($etapeEnCours->getLibelle());
                    $etapeReconduit->setCode($etapeEnCours->getCode());
                    $etapeReconduit->setTypeFormation(($etapeEnCours->getTypeFormation()));
                    $etapeReconduit->setStructure($etapeEnCours->getStructure());
                    $etapeReconduit->setDomaineFonctionnel($etapeEnCours->getDomaineFonctionnel());
                    $etapeReconduit->setSourceCode($etapeEnCours->getSourceCode());

                    $em->persist($etapeReconduit);
                }
                //Reconduction des éléments pédagogiques pour cette étape
                if (array_key_exists($idEtape, $datas['element'])) {
                    foreach ($datas['element'][$idEtape] as $idElement) {
                        $elementEnCours   = $this->elementPedagogiqueService->get($idElement);
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
                        //ajout de l'élément à l'étape
                        $etapeReconduit->addElementPedagogique($elementReconduit);
                        $em->persist($elementReconduit);
                        //Reconduction des chemins pédagogiques
                        $cheminsPedagogique = $elementEnCours->getCheminPedagogique();
                        foreach ($cheminsPedagogique as $chemin) {
                            $cheminReconduit = $this->cheminPedagogiqueService->newEntity();
                            $cheminReconduit->setElementPedagogique($elementReconduit);
                            $cheminReconduit->setEtape($etapeReconduit);
                            $cheminReconduit->setSourceCode($chemin->getSourceCode());
                            $cheminReconduit->setSource($chemin->getSource());
                            $cheminReconduit->setOrdre($chemin->getOrdre());
                            $em->persist($cheminReconduit);
                        }
                        //Reconduction des volumes horaires
                        $volumesHoraire = $elementEnCours->getVolumeHoraireEns();
                        foreach ($volumesHoraire as $volume) {
                            $volumeReconduit = $this->volumeHoraireEnsService->newEntity();
                            $volumeReconduit->setSource($volume->getSource());
                            $volumeReconduit->setSourceCode($volume->getSourceCode());
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
            Throw new \Exception('Aucune donnée à reconduire');
        }

        return true;
    }



    public function reconduireCCFormation($datas)
    {
        $anneeN       = $this->getServiceContext()->getAnnee();
        $anneeN1      = $this->getServiceContext()->getAnneeSuivante();
        $serviceEtape = $this->getServiceEtape();
        $em           = $this->getEntityManager();

        foreach ($datas as $code) {
            $etapes = $em->getRepository(Etape::class)->findBy(['code' => $code, 'annee' => [$anneeN, $anneeN1]]);
            foreach ($etapes as $etape) {
                if ($etape->getAnnee()->getLibelle() == $anneeN->getLibelle()) {
                    $etapeN = $etape;
                }
                if ($etape->getAnnee()->getLibelle() == $anneeN1->getLibelle()) {
                    $etapeN1 = $etape;
                }
            }
            $elementsPedagogiqueN = $etapeN->getElementPedagogique();
            foreach ($elementsPedagogiqueN as $ep) {
                $epN1 = $em->getRepository(ElementPedagogique::class)->findOneBy(['code' => $ep->getCode(), 'annee' => $anneeN1]);
                if ($epN1) {
                    $centreCoutN = $ep->getCentreCoutEp();
                    foreach ($centreCoutN as $cc) {
                        $epN1->addCentreCoutEp($cc);
                    }
                    $em->persist($epN1);
                }
                unset($epN1);
            }
            $em->flush();
        }

        return false;
    }

}