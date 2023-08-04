<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSender extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    private $data;

    /**
     * MailSender constructor.
     *
     * @param $view
     * @param $subject
     * @param  array  $data
     */
    public function __construct($view, $subject, $data = [])
    {
        $this->view = $view;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * @return MailSender
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->markdown($this->view)
            ->with($this->data);
    }
}
