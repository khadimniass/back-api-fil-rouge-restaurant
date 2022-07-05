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

    public function sendEmail($user,$subject="creation de compte")
    {
            $email = (new Email())
            ->from('niasskhadim@outlook.com')
            ->to($user->getLogin())
            ->subject($subject)
            ->html($this->twig->render("mail/index.html.twig",[
            'user'=>$user
            ]));
            $this->mailer->send($email);
    }
}