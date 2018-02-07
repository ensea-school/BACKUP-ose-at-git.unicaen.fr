<?php

namespace Application\Controller;

use Application\Entity\Db\IndicateurDepassementCharges;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Processus\Traits\IndicateurProcessusAwareTrait;
use Application\Entity\Db\Indicateur;
use Application\Service\ContextService;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\DossierServiceAwareTrait;
use Application\Service\Traits\IndicateurServiceAwareTrait;
use Application\Service\Traits\IntervenantServiceAwareTrait;
use Application\Service\Traits\NotificationIndicateurServiceAwareTrait;
use Application\Filter\IntervenantEmailFormatter;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireServiceAwareTrait;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\View\Renderer\PhpRenderer;
use Exception;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;



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
    public function __construct( TreeRouteStack $httpRouter, PhpRenderer $renderer, array $cliConfig )
    {
        $this->httpRouter = $httpRouter;
        $this->renderer = $renderer;
        $this->cliConfig = $cliConfig;
    }



    /**
     * Liste des indicateurs.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $indicateurs   = $this->getServiceIndicateur()->getList();
        $notifications = $this->getServiceNotificationIndicateur()->getList(
            $this->getServiceNotificationIndicateur()->finderByRole()
        );

        $abonnements = [];
        foreach ($notifications as $notification) {
            $abonnements[$notification->getIndicateur()->getId()] = $notification;
        }

        return compact('indicateurs', 'abonnements');
    }



    public function resultAction()
    {
        $role       = $this->getServiceContext()->getSelectedIdentityRole();
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());

        $structure = $role->getStructure() ?: $this->getEvent()->getParam('structure');

        /* @var $structure Structure */

        return compact('indicateur', 'structure');
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
        $sab = $this->getServiceNotificationIndicateur();
        $saf = $this->getServiceAffectation();
        $sid = $this->getServiceIndicateur();

        $qb = $sab->finderByRole(); // filtre selon le rôle courant
        $sab->join($sid, $qb, 'indicateur', true);
        $sab->finderByInHome(true, $qb);

        $sab->join($saf, $qb, 'affectation');
        $saf->finderByHistorique($qb);

        $sid->orderBy($qb);

        $notifications = $sab->getList($qb);

        $indicateurs = [];
        foreach( $notifications as $notification ){
            $indicateurs[] = $notification->getIndicateur()->setServiceIndicateur($sid);
        }

        return compact('indicateurs');
    }



    public function envoiMailIntervenantsAction()
    {
        $indicateur = $this->getEvent()->getParam('indicateur');
        /* @var $indicateur Indicateur */
        $indicateur->setServiceIndicateur($this->getServiceIndicateur());

        $intervenantsStringIds = $this->params()->fromQuery('intervenants', $this->params()->fromPost('intervenants', null));
        if ($intervenantsStringIds){
            $intervenantsIds = explode('-', $intervenantsStringIds);
        }else{
            $intervenantsIds = [];
        }


        $result       = $indicateur->getResult();
        $intervenants = [];
        foreach ($result as $index => $indicRes) {
            $intervenant = $indicRes->getIntervenant();
            if (empty($intervenantsIds) || in_array($intervenant->getId(), $intervenantsIds)) {
                $intervenants[$intervenant->getId()] = $intervenant;
            }
        }

        $formatter = new IntervenantEmailFormatter();
        $formatter->setServiceDossier( $this->getServiceDossier() );
        $emails    = $formatter->filter($intervenants);
        if (($intervenantsWithNoEmail = $formatter->getIntervenantsWithNoEmail())) {
            throw new \LogicException(
                "Aucune adresse mail trouvée pour l'intervenant suivant: " . implode(", ", Util::collectionAsOptions($intervenantsWithNoEmail)));
        }

        $mailer  = new IndicateurIntervenantsMailer($this, $indicateur, $this->renderer);
        $from    = $mailer->getFrom();
        $subject = $mailer->getDefaultSubject();
        $body    = $mailer->getDefaultBody();

        $form = new Form();
        $form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->add((new Text('from'))->setValue($from));
        $form->add((new Text('nombre'))->setValue(count($emails)));
        $form->add((new Text('subject'))->setValue($subject));
        $form->add((new Textarea('body'))->setValue($body));
        $form->add((new Hidden('intervenants'))->setValue($intervenantsStringIds));
        $form->getInputFilter()->get('subject')->setRequired(true);
        $form->getInputFilter()->get('body')->setRequired(true);

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            if ($form->setData($post)->isValid()) {
                $mailer->send($emails, $post);
                $count = count($intervenants);
                $pluriel = $count > 1 ? 's' : '';
                $this->flashMessenger()->addSuccessMessage("Le mail a été envoyé à $count intervenant$pluriel");
                $this->redirect()->toRoute('indicateur/result', ['indicateur' => $indicateur->getId()]);
            }
        }

        return [
            'title'   => "Envoyer un mail aux intervenants",
            'count'   => count($emails),
            'form'    => $form,
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
        $event      = $this->getEvent();
        $router     = $event->getRouter();
        $event->setRouter($this->httpRouter);

        // De plus, pour fonctionner, le HttpRouter a besoin du "prefixe" à utiliser pour assembler les URL
        // (ex: "http://localhost/ose"). Ce prefixe est fourni via un HttpUri initialisé à partir de 2 arguments
        // de la ligne de commande : "requestUriHost" (obligatoire) et "requestUriScheme" (facultatif, "http" par défaut).
        $httpUri = (new \Zend\Uri\Http())
            ->setHost($this->cliConfig['domain'])              // ex: "/localhost/ose", "ose.unicaen.fr"
            ->setScheme($this->cliConfig['scheme']);
        $this->httpRouter->setRequestUri($httpUri);


        $request = $this->getRequest();

        $force = (bool)$request->getParam('force');

        $this->getProcessusIndicateur()->envoiNotifications($force);

        // S'il s'agit d'une requête de type Console (CLI), rétablissement du router initial (cf. commentaires plus haut).
        if ($this->getRequest() instanceof \Zend\Console\Request) {
            $event->setRouter($router);
        }

        exit;
    }



    public function depassementChargesAction()
    {
        /** @var Intervenant $intervenant */
        $intervenant = $this->getEvent()->getParam('intervenant');

        $typeVolumeHoraireCode = $this->params()->fromRoute('type-volume-horaire-code');
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getByCode($typeVolumeHoraireCode);

        $periodeCode = $this->params()->fromRoute('periode-code');
        $periode = $this->getServicePeriode()->getByCode($periodeCode);

        if (!$intervenant){
            throw new \Exception('Un intervenant doit être spécifié');
        }

        $params = compact('typeVolumeHoraire','periode', 'intervenant');
        if ($structure = $this->getServiceContext()->getStructure()){
            $params['structure'] = $structure->getId();
            $sFilter = ' AND idc.structure = :structure';
        }else{
            $sFilter = '';
        }

        $dql = "
        SELECT
          idc, s, ep, ti        
        FROM
          ".IndicateurDepassementCharges::class."   idc
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


        $idcs = $this->em()->createQuery($dql)->setParameters($params)->getResult();
        $title = 'Dépassement d\'heures ('.$typeVolumeHoraire.') par rapport aux charges <small>'.$intervenant.'</small>';

        return compact('title','intervenant', 'idcs');
    }
}







/**
 * Classe dédiée à l'envoi des mails aux intervenants retournés par un indicateur.
 */
class IndicateurIntervenantsMailer
{
    use ContextServiceAwareTrait;

    /**
     * @var AbstractController
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



    public function __construct(AbstractController $controller, Indicateur $indicateur, PhpRenderer $renderer)
    {
        $this->controller = $controller;
        $this->indicateur = $indicateur;
        $this->renderer = $renderer;
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
        $html          = $data['body'];
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
        $context = $this->controller->getServiceContext();

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
            strip_tags($this->indicateur->getType())
        );

        return $subject;
    }



    public function getDefaultBody()
    {
        /** @var ContextService $context */
        $context = $this->controller->getServiceContext();

        // corps au format HTML
        $html = $this->renderer->render('application/indicateur/mail/intervenants', [
            'phrase'    => $this->indicateur->getMessage(),
            'signature' => $context->getUtilisateur(),
            'structure' => $context->getStructure(),
        ]);

        return $html;
    }
}
