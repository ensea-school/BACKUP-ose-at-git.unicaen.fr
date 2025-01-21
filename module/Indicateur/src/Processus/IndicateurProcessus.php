<?php

namespace Indicateur\Processus;

use Application\Processus\AbstractProcessus;
use Indicateur\Entity\Db\NotificationIndicateur;
use Application\Service\Traits\ContextServiceAwareTrait;
use Indicateur\Service\IndicateurServiceAwareTrait;
use Indicateur\Service\NotificationIndicateurServiceAwareTrait;
use Laminas\View\Renderer\PhpRenderer;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;


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
    use MailServiceAwareTrait;

    /**
     * @var PhpRenderer
     */
    private $renderer;

    private $host;


    public function __construct(PhpRenderer $renderer, string $host)
    {
        $this->renderer = $renderer;
        $this->host = $host;

    }



    public function envoiNotifications($force = false)
    {
        $nis = $this->getServiceNotificationIndicateur()->getNotifications($force);

        foreach ($nis as $ni) {
            $mail = $this->creerMailNotification($ni);

            if ($mail) {

                $this->getMailService()->send($mail);

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



    protected function creerMailNotification(NotificationIndicateur $notification): ?Email
    {
        $result = $this->getServiceIndicateur()->getResult($notification);
        $count  = count($result);

        if (0 == $count) return null; // pas de notification pour cet indicateur

        $html          = $this->renderer->render('indicateur/indicateur/mail/notification', [
            'notification' => $notification,
            'result'       => $result,
            'host'         => $this->host,
        ]);

        $subject = sprintf(
            "[OSE %s, n°%s: Notif %s] %s",
            $this->getServiceContext()->getAnnee(),
            $notification->getIndicateur()->getNumero(),
            $notification->getFrequenceToString(),
            strip_tags($notification->getIndicateur()->getLibelle($count))
        );

        $from = \AppAdmin::config()['mail']['from'] ?? null;

        $to = $notification->getAffectation()->getUtilisateur()->getEmail();
        $toName = (string)$notification->getAffectation()->getUtilisateur();
        
        $email = new Email();
        $email->from(new Address($from, 'Application OSE'))
             ->to(new Address($to, $toName))
             ->subject($subject)
             ->html($html);

        return $email;
    }
}