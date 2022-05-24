<?php

namespace Intervenant\Service;


use Application\Service\AbstractService;

use Laminas\Mail\Message as MailMessage;
use Laminas\Mime\Message;
use Laminas\Mime\Mime;
use Laminas\Mime\Part;

use Intervenant\Form\MailerIntervenantFormAwareTrait;
use Intervenant\Form\NoteSaisieFormAwareTrait;
use UnicaenApp\Controller\Plugin\Mail;

/**
 * Description of StatutServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MailService extends AbstractService
{

    use NoteServiceAwareTrait;
    use NoteSaisieFormAwareTrait;
    use MailerIntervenantFormAwareTrait;
    use MailServiceAwareTrait;

    /**
     * @var Mail
     */
    private Mail $mail;



    /**
     * @return Mail
     */
    public function getMail(): Mail
    {
        return $this->mail;
    }



    /**
     * @param Mail $mail
     */
    public function setMail(Mail $mail): void
    {
        $this->mail = $mail;
    }



    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }



    /**
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $content
     *
     * @return void
     */
    public function envoyerMail(string $from, string $to, string $subject, string $content)
    {

        $body = new Message();

        $text          = new Part($content);
        $text->type    = Mime::TYPE_HTML;
        $text->charset = 'utf-8';
        $body->addPart($text);
        $message = new MailMessage();

        $message->setEncoding('UTF-8')
            ->setFrom($from)
            ->setSubject($subject)
            ->addTo($to)
            ->setBody($body);

        //Envoi du mail
        $this->getMail()->send($message);
    }
}