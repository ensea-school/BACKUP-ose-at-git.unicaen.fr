<?php

namespace Application\Controller;

use Application\Controller\Plugin\Context;
use Application\Entity\Db\NotificationIndicateur as NotificationIndicateurEntity;
use Application\Service\Indicateur as IndicateurService;
use Application\Service\Indicateur\DateAwareIndicateurImplInterface;
use Application\Service\NotificationIndicateur as NotificationIndicateurService;
use DateTime;
use Doctrine\ORM\EntityManager;
use Zend\Console\Request as ConsoleRequest;
use Zend\Mail\Message as MailMessage;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Mime;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Uri\Http as HttpUri;
use Zend\View\Renderer\PhpRenderer;

/**
 * Opérations autour des notifications.
 *
 * @method EntityManager em()
 * @method Context              context()
 * 
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NotificationController extends AbstractActionController
{

    /**
     * Visualisation de tous les abonnements aux indicateurs.
     * 
     * @return \Zend\View\Model\ViewModel
     */
    public function indicateursAction()
    {
        $nis = $this->getServiceNotificationIndicateur()->findNotificationsIndicateurs(false);
        
        $viewModel = new \Zend\View\Model\ViewModel();
        $viewModel
                ->setVariable('nis', $nis)
                ->setVariable('serviceIndicateur', $this->getServiceIndicateur());
        
        return $viewModel;
    }
    
    /**
     * Réponse aux requêtes AJAX d'obtention du "title" (intitulé contenant le nombre de résultats trouvés)
     * d'un indicateur.
     * 
     * @return \Zend\View\Model\JsonModel
     */
    public function indicateurFetchTitleAction()
    {
        $niId = $this->params()->fromQuery('id');
        if (!$niId) {
            exit;
        }
        
        $ni             = $this->getServiceNotificationIndicateur()->get($niId);
        $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($ni->getIndicateur(), $ni->getStructure());
        
        if ($indicateurImpl->getResultCount()) {
            $url = $this->url()->fromRoute('indicateur/result', [
                'indicateur' => $ni->getIndicateur()->getId(),
                'structure'  => $ni->getStructure() ? $ni->getStructure()->getId() : null,
            ]);
            $title = <<<EOS
<a href="$url" title="Cliquez pour vous rendre à la page concernée">$indicateurImpl</a>
EOS;
        } 
        else {
            $title = (string) $indicateurImpl;
        }

        return new \Zend\View\Model\JsonModel([
            'title' => $title,
        ]);
    }
    
    /**
     * Notifications par mail des personnes abonnées à des indicateurs.
     * 
     * Accessible en ligne de commande, par exemple (on suppose que l'on est situé dans le répertoire de l'appli) :
     *      php public/index.php notifier indicateurs --force --requestUriHost=/localhost/ose --requestUriScheme=http
     * Arguments de la ligne de commande : 
     * - <code>force</code> (facultatif)
     * - <code>requestUriHost</code> (obligatoire),
     * - <code>requestUriScheme</code> (facultatif, "http" par défaut).
     */
    public function notifierIndicateursAction()
    {
        $request  = $this->getRequest();
        $renderer = $this->getServiceLocator()->get('view_manager')->getRenderer();  /* @var $renderer PhpRenderer */
        $force    = (bool) $request->getParam('force');
        $nis      = $this->getServiceNotificationIndicateur()->findNotificationsIndicateurs($force);
        
        if ($request instanceof ConsoleRequest) {
            // S'il s'agit d'une requête de type Console (CLI), le plugin de contrôleur Url utilisé par les indicateurs
            // n'est pas en mesure de construire des URL (car le ConsoleRouter ne sait pas ce qu'est une URL!).
            // On injecte donc provisoirement un HttpRouter dans le circuit.
            $event      = $this->getEvent();
            $router     = $event->getRouter();
            $httpRouter = $this->getServiceLocator()->get('HttpRouter'); /* @var $httpRouter TreeRouteStack */
            $event->setRouter($httpRouter);
            
            // De plus, pour fonctionner, le HttpRouter a besoin du "prefixe" à utiliser pour assembler les URL
            // (ex: "http://localhost/ose"). Ce prefixe est fourni via un HttpUri initialisé à partir de 2 arguments 
            // de la ligne de commande : "requestUriHost" (obligatoire) et "requestUriScheme" (facultatif, "http" par défaut).
            $httpUri = (new HttpUri())
                    ->setHost($request->getParam('requestUriHost'))              // ex: "/localhost/ose", "ose.unicaen.fr"
                    ->setScheme($request->getParam('requestUriScheme', "http")); // ex: "http", "https"
            $httpRouter->setRequestUri($httpUri);
        }
        
        foreach ($nis as $ni) { /* @var $ni NotificationIndicateurEntity */
            $indicateurImpl = $this->getServiceIndicateur()->getIndicateurImpl($ni->getIndicateur(), $ni->getStructure());
            
            // certains indicateurs ont besoin d'un critère de date 
            if ($indicateurImpl instanceof DateAwareIndicateurImplInterface) {
                $indicateurImpl->setDate($ni->getDateDernNotif());
            }
        
            // pas de notification si l'indicateur ne renvoit rien
            if (! (int) $indicateurImpl->getResultCount()) {
                continue;
            }
            
            // corps au format HTML
            $html = $renderer->render('application/notification/mail/indicateur', [
                'indicateurImpl' => $indicateurImpl,
                'ni'             => $ni,
            ]);
            $part          = new MimePart($html);
            $part->type    = Mime::TYPE_HTML;
            $part->charset = 'UTF-8';
            $body          = new MimeMessage();
            $body->addPart($part);
        
            // init
            $message       = new MailMessage();
            $message->setEncoding('UTF-8')
                    ->setFrom('ne_pas_repondre@unicaen.fr', "Application " . ($app = $this->appInfos()->getNom()))
                    ->setSubject(sprintf("[%s Notif %s] %s", $app, $ni->getFrequenceToString(), $indicateurImpl->getTitle()))
                    ->setBody($body)
                    ->addTo($ni->getPersonnel()->getEmail(), "" . $ni->getPersonnel());
                    
            // envoi
            $this->mail()->send($message);
            
            if (!$force) {
                // enregistrement de la date de dernière notification
                $now = new DateTime();
                $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
                $ni->setDateDernNotif($now);
                $this->em()->flush($ni);
            }
        }
        
        // S'il s'agit d'une requête de type Console (CLI), rétablissement du router initial (cf. commentaires plus haut).
        if ($request instanceof Request) {
            $event->setRouter($router);
        }
        
        exit;
    }
    
    /**
     * @return IndicateurService
     */
    private function getServiceIndicateur()
    {
        return $this->getServiceLocator()->get('IndicateurService');
    }
    
    /**
     * @return NotificationIndicateurService
     */
    private function getServiceNotificationIndicateur()
    {
        return $this->getServiceLocator()->get('NotificationIndicateurService');
    }
}