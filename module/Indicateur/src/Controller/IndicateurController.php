<?php

namespace Indicateur\Controller;

use Application\Controller\AbstractController;
use Application\Entity\Db\TypeVolumeHoraire;
use Indicateur\Entity\Db\IndicateurDepassementCharges;
use Application\Entity\Db\Intervenant;
use Indicateur\Entity\Db\TypeIndicateur;
use Indicateur\Processus\IndicateurProcessusAwareTrait;
use Indicateur\Entity\Db\Indicateur;
use Application\Service\ContextService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Indicateur\Service\IndicateurServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Indicateur\Service\NotificationIndicateurServiceAwareTrait;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Intervenant\Entity\Db\Note;
use Intervenant\Service\NoteServiceAwareTrait;
use Laminas\Form\Element\Checkbox;
use Laminas\Router\Http\TreeRouteStack;
use Laminas\View\Renderer\PhpRenderer;
use Exception;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Mime;
use Laminas\Mime\Part as MimePart;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Unicaen\Console\Console;
use UnicaenApp\View\Model\CsvModel;


class IndicateurController extends AbstractController
{
    use IndicateurServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use ContextServiceAwareTrait;
    use NotificationIndicateurServiceAwareTrait;
    use AffectationServiceAwareTrait;
    use IndicateurProcessusAwareTrait;
    use DossierServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use NoteServiceAwareTrait;

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
     * @return ViewModel
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
     * @return JsonModel
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
     * @return ViewModel
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
        $intervenantsWithNoEmail = [];
        foreach ($result as $intervenantId => $indicRes) {
            if (!in_array($intervenantId, $intervenantsIds)) {
                continue;
            }
            $email = $indicRes['intervenant-email-perso'] ?: $indicRes['intervenant-email-pro'];
            if ($email) {
                $emails[$email] = $indicRes['intervenant-nom'] . ' ' . $indicRes['intervenant-prenom'];
            } else {
                $intervenantsWithNoEmail[$intervenantId] = $indicRes;
            }
        }
        $mailer  = new IndicateurIntervenantsMailer($this, $indicateur, $this->renderer);
        $from    = $mailer->getFrom();
        $subject = $mailer->getDefaultSubject();
        $body    = $mailer->getDefaultBody();

        $form = new Form();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->add((new Text('from'))->setValue($from));
        $form->add(new Text('cci'));
        $form->add((new Text('nombre'))->setValue(count($emails)));
        $form->add((new Text('subject'))->setValue($subject));
        $form->add((new Textarea('body'))->setValue($body));
        $form->add((new Checkbox('copy'))->setValue(1));
        $form->add((new Hidden('intervenants'))->setValue($intervenantsStringIds));
        $form->getInputFilter()->get('subject')->setRequired(true);
        $form->getInputFilter()->get('body')->setRequired(true);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->setData($post)->isValid()) {
                $mailer->send($emails, $post);
                //Création d'une note email pour chaque intervenant concerné
                foreach ($intervenantsIds as $id) {
                    $intervenant = $this->getServiceIntervenant()->get($id);
                    if ($intervenant) {
                        $this->getServiceNote()->createNoteFromEmail($intervenant, $post['subject'], $post['body']);
                    }
                }
                if ($post['copy']) {
                    //envoi une copie du mail à l'utilisateur si il l'a demandé
                    $utilisateur                                = $this->getServiceContext()->getUtilisateur();
                    $emailUtilisateur[$utilisateur->getEmail()] = $utilisateur->getDisplayName();
                    $mailer->sendCopyEmail($emailUtilisateur, $emails, $post);
                }
                if ($post['cci'] && !empty($post['cci'])) {
                    $emailsCci = explode(';', $post['cci']);
                    foreach ($emailsCci as $emailCci) {
                        $listEmailsCci            = [];
                        $listEmailsCci[$emailCci] = $emailCci;
                        $mailer->sendCopyEmail($listEmailsCci, $emails, $post);
                    }
                }
                $count   = count($intervenantsIds);
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



    /**
     * Notifications par mail des personnes abonnées à des indicateurs.
     *
     * Accessible en ligne de commande, par exemple (on suppose que l'on est situé dans le répertoire de l'appli) :
     *      php public/index.php notifier indicateurs --force
     * Arguments de la ligne de commande :
     * - <code>force</code> (facultatif)
     */
    public function envoiNotificationsAction()
    {
        // S'il s'agit d'une requête de type Console (CLI), le plugin de contrôleur Url utilisé par les indicateurs
        // n'est pas en mesure de construire des URL (car le ConsoleRouter ne sait pas ce qu'est une URL!).
        // On injecte donc provisoirement un HttpRouter dans le circuit.
        $event  = $this->getEvent();
        $router = $event->getRouter();
        $event->setRouter($this->httpRouter);

        // De plus, pour fonctionner, le HttpRouter a besoin du "prefixe" à utiliser pour assembler les URL
        // (ex: "http://localhost/ose"). Ce prefixe est fourni via un HttpUri initialisé à partir de 2 arguments
        // de la ligne de commande : "requestUriHost" (obligatoire) et "requestUriScheme" (facultatif, "http" par défaut).
        $httpUri = (new \Laminas\Uri\Http())
            ->setHost($this->cliConfig['domain'])// ex: "/localhost/ose", "ose.unicaen.fr"
            ->setScheme($this->cliConfig['scheme']);
        $this->httpRouter->setRequestUri($httpUri);


        $request = $this->getRequest();

        $force = (bool)$request->getParam('force');

        $this->getProcessusIndicateur()->envoiNotifications($force);

        // S'il s'agit d'une requête de type Console (CLI), rétablissement du router initial (cf. commentaires plus haut).
        if (Console::isConsole()) {
            $event->setRouter($router);
        }

        exit;
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





/**
 * Classe dédiée à l'envoi des mails aux intervenants retournés par un indicateur.
 */
class IndicateurIntervenantsMailer
{
    use ContextServiceAwareTrait;
    use ParametresServiceAwareTrait;

    /**
     * @var IndicateurController
     */
    private $controller;

    /**
     * @var Indicateur
     */
    private $indicateur;

    /**
     * @var PhpRenderer
     */
    private $renderer;



    public function __construct(IndicateurController $controller, Indicateur $indicateur, PhpRenderer $renderer)
    {
        $this->controller = $controller;
        $this->indicateur = $indicateur;
        $this->renderer   = $renderer;
    }



    public function send($emails, $data)
    {
        foreach ($emails as $email => $name) {
            $message = $this->createMessage($data);
            $message->setTo($email, $name);

            $this->controller->mail()->send($message);
        }
    }



    private function createMessage($data)
    {
        // corps au format HTML
        $html = $data['body'];
        if (!empty($data['emailsIntervenant'])) {
            $htmlLog = "<br/><br/>------------------------------------------------ <br/><br/>";
            $htmlLog = "<p>Email envoyé au(x) destinataire(s) suivant(s) : <br/>";

            foreach ($data['emailsIntervenant'] as $email => $name) {
                $htmlLog .= $name . " / " . $email . "<br/>";
            }
            $htmlLog .= "</p>";
            $html    .= $htmlLog;
        }
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        return (new MailMessage())
            ->setEncoding('UTF-8')
            ->setFrom($this->getFrom(), "Contact Application " . ($app = $this->controller->appInfos()->getNom()))
            ->setSubject($data['subject'])
            ->setBody($body);
    }



    public function getFrom()
    {
        /** @var ContextService $context */
        $context   = $this->controller->getServiceContext();
        $parametre = $this->getServiceParametres();

        $from = trim($parametre->get('indicateur_email_expediteur'));
        if (!empty($from)) {
            return $from;
        }

        $from = $context->getUtilisateur()->getEmail();

        return $from;
    }



    public function getDefaultSubject()
    {
        /** @var ContextService $context */
        $context = $this->controller->getServiceContext();

        $subject = sprintf("%s %s : %s",
            $this->controller->appInfos()->getNom(),
            $context->getAnnee(),
            strip_tags($this->indicateur->getTypeIndicateur())
        );

        return $subject;
    }



    public function getDefaultBody()
    {
        /** @var ContextService $context */
        $context = $this->controller->getServiceContext();

        // corps au format HTML
        $html = $this->renderer->render('indicateur/indicateur/mail/intervenants', [
            'phrase'    => '',
            'signature' => $context->getUtilisateur(),
            'structure' => $context->getStructure(),
        ]);

        return $html;
    }



    public function sendCopyEmail($emailsUtilisateur, $emailsIntervenant, $data, $logs = null)
    {
        $data['emailsIntervenant'] = $emailsIntervenant;
        $message                   = $this->createMessage($data);
        $message->setSubject('COPIE | ' . $data['subject']);
        foreach ($emailsUtilisateur as $email => $name) {
            $message->setTo($email, $name);
        }
        $this->controller->mail()->send($message);
    }
}
