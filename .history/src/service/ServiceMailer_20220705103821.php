<?php

namespace App\service;

use Twig\Environment;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ServiceMailer {
    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->mailer=$mailer;
        $this->twig=$twig;
    }

    public function sendEmail($user, $subject, $message="creation de compte")
    {
            $email = (new Email())
            ->from('niasskhadim@outlook.com')
            ->to($user, $subject, $message->getLogin())
            ->subject($subject)
            ->html($this->twig->render("mail/index.html.twig",[
            'user'=>$user, $subject, $message
            ]));
            $this->mailer->send($email);
    }
}