<?php

namespace Indicateur\Processus;

use Application\Processus\AbstractProcessus;
use Indicateur\Entity\Db\NotificationIndicateur;
use Application\Service\Traits\ContextServiceAwareTrait;
use Indicateur\Service\IndicateurServiceAwareTrait;
use Indicateur\Service\NotificationIndicateurServiceAwareTrait;
use Laminas\View\Renderer\PhpRenderer;
use UnicaenApp\Controller\Plugin\Mail;
use Laminas\Mime\Part as MimePart;
use Laminas\Mime\Mime;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message as MimeMessage;


/**
 * Description of IndicateurProcessus
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IndicateurProcessus extends AbstractProcessus
{
    use NotificationIndicateurServiceAwareTrait;
    use ContextServiceAwareTrait;
    use IndicateurServiceAwareTrait;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    /**
     * @var Mail
     */
    private $mail;



    public function __construct(PhpRenderer $renderer, Mail $mail)
    {
        $this->renderer = $renderer;
        $this->mail     = $mail;
    }



    public function envoiNotifications($force = false)
    {
        $nis = $this->getServiceNotificationIndicateur()->getNotifications($force);

        foreach ($nis as $ni) {
            $message = $this->creerMailNotification($ni);

            if ($message) {
                $this->mail->send($message);

                if (!$force) {
                    // enregistrement de la date de dernière notification
                    $now = new \DateTime();
                    $now->setTime($now->format('H'), 0, 0); // raz minutes et secondes
                    $ni->setDateDernNotif($now);
                    $this->getEntityManager()->persist($ni);
                    $this->getEntityManager()->flush($ni);
                }
            }
        }

        return $nis;
    }



    protected function creerMailNotification(NotificationIndicateur $notification): ?MailMessage
    {
        $result = $this->getServiceIndicateur()->getResult($notification);
        $count  = count($result);

        if (0 == $count) return null; // pas de notification pour cet indicateur

        $html          = $this->renderer->render('indicateur/indicateur/mail/notification', [
            'notification' => $notification,
            'result'       => $result,
        ]);
        $part          = new MimePart($html);
        $part->type    = Mime::TYPE_HTML;
        $part->charset = 'UTF-8';
        $body          = new MimeMessage();
        $body->addPart($part);

        // init
        $message = new MailMessage();
        $message->setEncoding('UTF-8')
            ->setFrom(\OseAdmin::instance()->config()->get('mail', 'from'), "Application OSE")
            ->setSubject(sprintf(
                "[OSE %s, n°%s: Notif %s] %s",
                $this->getServiceContext()->getAnnee(),
                $notification->getIndicateur()->getNumero(),
                $notification->getFrequenceToString(),
                strip_tags($notification->getIndicateur()->getLibelle($count))
            ))
            ->setBody($body)
            ->addTo($notification->getAffectation()->getUtilisateur()->getEmail(), (string)$notification->getAffectation()->getUtilisateur());

        return $message;
    }
}