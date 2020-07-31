<?php

namespace App;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class Mailer
{
    private $mailer;
    private $from;

    public function __construct(MailerInterface $mailer, string $from)
    {
        $this->mailer = $mailer;
        $this->from = $from;
    }

    public function send($to)
    {
        $mail = (new Email())
            ->from($this->from)
            ->to($to)
            ->text('Hello')
            ->html('Hello');

        return $this->mailer->send($mail);
    }
}
