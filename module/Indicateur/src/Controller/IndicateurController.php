<?php

namespace Indicateur\Controller;

use Application\Controller\AbstractController;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Exception;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Entity\Db\IndicateurDepassementCharges;
use Indicateur\Entity\Db\TypeIndicateur;
use Indicateur\Processus\IndicateurProcessusAwareTrait;
use Indicateur\Service\IndicateurServiceAwareTrait;
use Indicateur\Service\NotificationIndicateurServiceAwareTrait;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\View\Model\JsonModel;
use Laminas\View\Renderer\PhpRenderer;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;


class IndicateurController extends AbstractController
{
    use IndicateurServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use NotificationIndicateurServiceAwareTrait;
    use IndicateurProcessusAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use NoteServiceAwareTrait;
    use MailServiceAwareTrait;

    /**
     * @var TreeRouteStack
     */
    private $httpRouter;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var array
     */
    private $cliConfig;



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @return void
     * @link http://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct(TreeRouteStack $httpRouter, PhpRenderer $renderer, array $cliConfig)
    {
        $this->httpRouter = $httpRouter;
        $this->renderer   = $renderer;
        $this->cliConfig  = $cliConfig;
    }



    /**
     * Liste des indicateurs.
     *
     * @return array
     */
    public function indexAction()
    {
        $dql = "
        SELECT
          ti, i, n
        FROM
          " . TypeIndicateur::class . " ti 
          JOIN ti.indicateur i
          LEFT JOIN i.notification n WITH n.affectation = :affectation
        WHERE
          i.enabled = TRUE
        ORDER BY
          ti.ordre, i.ordre
        ";

        $params      = [
            'affectation' => $this->getServiceContext()->getAffectation(),
        ];
        $indicateurs = $this->em()->createQuery($dql)->execute($params);

        return compact('indicateurs');
    }



    public function calculAction()
    {
        /** @var TypeIndicateur $typeindicateur */
        $typeindicateur = $this->params()->fromRoute('typeIndicateur');

        $dql = "SELECT i FROM " . Indicateur::class . " i WHERE i.enabled = TRUE AND i.typeIndicateur = :type";

        /** @var Indicateur[] $indicateurs */
        $indicateurs = $this->em()->createQuery($dql)->execute(['type' => $typeindicateur]);
        $data        = [];
        foreach ($indicateurs as $indicateur) {
            $count = $this->getServiceIndicateur()->getCount($indicateur);

            $data[$indicateur->getId()] = [
                'count'   => $count,
                'libelle' => $indicateur->getLibelle($count),
            ];
        }

        return new \Laminas\View\Model\JsonModel($data);
    }



    public function resultAction()
    {
        /* @var $indicateur Indicateur */
        $indicateur = $this->getEvent()->getParam('indicateur');
        $result     = $this->getServiceIndicateur()->getResult($indicateur);

        return compact('indicateur', 'result');
    }



    public function exportCsvAction()
    {
        /* @var $indicateur Indicateur */
        $indicateur = $this->getEvent()->getParam('indicateur');
        $result     = $this->getServiceIndicateur()->getCsv($indicateur);

        $csvModel = new CsvModel();
        if (!empty($result)) {
            $heads = [
                'annee-id'                => 'Année universitaire',
                'statut-libelle'          => 'Statut de l\'intervenant',
                'prioritaire'             => 'Prioritaire',
                'intervenant-code-rh'     => 'Code RH',
                'intervenant-code'        => 'Code',
                'intervenant-prenom'      => 'Prénom',
                'intervenant-nom'         => 'Nom usuel',
                'intervenant-email-perso' => 'Email personnel',
                'intervenant-email-pro'   => 'Email professionnel',
                'structure-libelle'       => 'Composante',
            ];

            $head = array_keys($result[0]);
            foreach ($head as $i => $h) {
                $head[$i] = $heads[$h] ?? $h;
            }

            $csvModel->setHeader($head);
        }
        $csvModel->addLines($result);
        $csvModel->setFilename('indicateur-' . $indicateur->getNumero() . '-' . date('yyyy-mm-dd') . '.csv');

        return $csvModel;
    }



    /**
     * Réponse aux requêtes AJAX d'abonnement de l'utilisateur connecté aux notifications concernant un indicateur.
     *
     * @return \Laminas\Http\Response|JsonModel
     */
    public function abonnerAction()
    {
        if (!$this->getRequest()->isXmlHttpRequest()) {
            return $this->redirect()->toRoute('home');
        }

        /** @var Indicateur $indicateur */
        $indicateur = $this->getEvent()->getParam('indicateur');
        $frequence  = $this->params()->fromPost('notification');
        $inHome     = $this->params()->fromPost('in-home') == '1';

        $serviceNotif = $this->getServiceNotificationIndicateur();

        try {
            $notificationIndicateur = $serviceNotif->abonner($indicateur, $frequence, $inHome);
            $status                 = 'success';
            $message                = 'Demande prise en compte';
            if (!$notificationIndicateur) {
                $message .= ' (Abonnement supprimé)';
            }
        } catch (Exception $e) {
            $notificationIndicateur = null;
            $status                 = 'error';
            $message                = "Abonnement impossible: {$e->getMessage()}";
        }

        return new JsonModel([
                                 'status'  => $status,
                                 'message' => $message,
                                 'infos'   => $notificationIndicateur ? $notificationIndicateur->getExtraInfos() : null,
                             ]);
    }



    /**
     * Indicateurs auxquels est abonné l'utilisateur (un Personnel) spécifié dans la requête.
     *
     * @return array
     */
    public function abonnementsAction()
    {
        $dql = "
        SELECT
          i, ti
        FROM
          " . Indicateur::class . " i
          JOIN i.typeIndicateur ti 
          JOIN i.notification n
        WHERE
          i.enabled = TRUE
          AND n.affectation = :affectation
          AND n.inHome = TRUE
        ORDER BY
          ti.ordre, i.ordre
        ";

        $params      = [
            'affectation' => $this->getServiceContext()->getAffectation(),
        ];
        $indicateurs = $this->em()->createQuery($dql)->execute($params);
        $counts      = [];
        foreach ($indicateurs as $indicateur) {
            $counts[$indicateur->getId()] = $this->getServiceIndicateur()->getCount($indicateur);
        }

        return compact('indicateurs', 'counts');
    }



    public function envoiMailIntervenantsAction()
    {
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */

        $intervenantsStringIds = $this->params()->fromQuery('intervenants', $this->params()->fromPost('intervenants', null));
        if ($intervenantsStringIds) {
            $intervenantsIds = explode('-', $intervenantsStringIds);
        } else {
            $intervenantsIds = [];
        }

        $result = $this->getServiceIndicateur()->getResult($indicateur);

        $emails                  = [];
        $emailsPro               = [];
        $intervenantsWithNoEmail = [];
        foreach ($result as $intervenantId => $indicRes) {
            if (!in_array($intervenantId, $intervenantsIds)) {
                continue;
            }
            $emailPro = $indicRes['intervenant-email-pro'];
            $email    = $indicRes['intervenant-email-perso'] ?: $indicRes['intervenant-email-pro'];
            if ($email) {
                $emails[$email] = $indicRes['intervenant-nom'] . ' ' . $indicRes['intervenant-prenom'];
                if ($email != $emailPro && !empty($emailPro)) {
                    $emailsPro[$emailPro] = $indicRes['intervenant-nom'] . ' ' . $indicRes['intervenant-prenom'];
                }
            } else {
                $intervenantsWithNoEmail[$intervenantId] = $indicRes;
            }
        }


        $from    = $this->getServiceIndicateur()->getFrom();
        $fromName = "Contact Application " . $this->appInfos()->getNom();
        $subject = sprintf("%s %s : %s",
                           $this->appInfos()->getNom(),
                           $this->getServiceContext()->getAnnee(),
                           strip_tags($indicateur->getTypeIndicateur())
        );

        $body    = $this->getServiceIndicateur()->getDefaultBody();

        $form = new Form();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->add((new Text('from'))->setValue($from));
        $form->add(new Text('cci'));
        $form->add((new Text('nombre'))->setValue(count($emails)));
        $form->add((new Text('subject'))->setValue($subject));
        $form->add((new Textarea('body'))->setValue($body));
        $form->add((new Checkbox('copy'))->setValue(1));
        $form->add((new Checkbox('cc-pro'))->setValue(0));
        $form->add((new Hidden('intervenants'))->setValue($intervenantsStringIds));
        $form->getInputFilter()->get('subject')->setRequired(true);
        $form->getInputFilter()->get('body')->setRequired(true);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->setData($post)->isValid()) {
                //Cas on je veux envoyer l'email également sur l'email pro de l'intervenant
                if ($post['cc-pro']) {
                    $emailsList = array_merge($emails, $emailsPro);
                } else {
                    $emailsList = $emails;
                }
                $email = $this->getServiceIndicateur()->createMessage($post, $emailsList, $subject, $fromName);
                $this->getMailService()->send($email);

                //$mailer->send($emailsList, $post);
                //Création d'une note email pour chaque intervenant concerné
                foreach ($intervenantsIds as $id) {
                    $intervenant = $this->getServiceIntervenant()->get($id);
                    if ($intervenant) {
                        $this->getServiceNote()->createNoteFromEmail($intervenant, $post['subject'], $post['body']);
                    }
                }
                if ($post['copy']) {
                    $emailUtilisateur = $this->getServiceContext()->getUtilisateur()->getEmail();
                    if (!empty($emailUtilisateur)) {
                        $email = $this->getServiceIndicateur()->createMessage($post, $emailsList, $subject, $fromName, [$emailUtilisateur]);
                        $this->getMailService()->send($email);
                    }

                }
                if ($post['cci'] && !empty($post['cci'])) {
                    $emailsCci = explode(';', $post['cci']);
                    $email = $this->getServiceIndicateur()->createMessage($post, $emailsList, $subject, $fromName,$emailsCci);
                    $this->getMailService()->send($email);

                }
                $count   = count($emailsList);
                $pluriel = $count > 1 ? 's' : '';
                $this->flashMessenger()->addSuccessMessage("Le mail a été envoyé à $count intervenant$pluriel");
                $this->redirect()->toRoute('indicateur/result', ['indicateur' => $indicateur->getId()]);
            }
        }

        return [
            'title'    => "Envoyer un mail aux intervenants",
            'count'    => count($intervenantsIds),
            'sansMail' => $intervenantsWithNoEmail,
            'form'     => $form,
        ];
    }



    public function depassementChargesAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant           = $this->getEvent()->getParam('intervenant');
        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code');
        $typeVolumeHoraire     = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $periodeCode = $this->params()->fromRoute('periode-code');
        $periode     = $this->getServicePeriode()->getByCode($periodeCode);

        if (!$intervenant) {
            throw new \Exception('Un intervenant doit être spécifié');
        }

        $params = compact('typeVolumeHoraire', 'periode', 'intervenant');
        if ($structure = $this->getServiceContext()->getStructure()) {
            $params['structure'] = $structure->getId();
            $sFilter             = ' AND idc.structure = :structure';
        } else {
            $sFilter = '';
        }

        $dql = "
        SELECT
          idc, s, ep, ti        
        FROM
          " . IndicateurDepassementCharges::class . "   idc
          JOIN idc.structure                        s
          JOIN idc.elementPedagogique               ep
          JOIN idc.typeIntervention                 ti
        WHERE
          idc.intervenant = :intervenant
          AND idc.typeVolumeHoraire = :typeVolumeHoraire
          AND (idc.periode = :periode OR idc.periode IS NULL)
          $sFilter
        ORDER BY
          s.libelleCourt, ep.libelle, ti.ordre
        ";


        $idcs  = $this->em()->createQuery($dql)->setParameters($params)->getResult();
        $title = 'Dépassement d\'heures (' . $typeVolumeHoraire . ') par rapport aux charges <small>' . $intervenant . '</small>';

        return compact('title', 'intervenant', 'idcs', 'typeVolumeHoraireCode');
    }
}
