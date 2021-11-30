<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MMRMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $attach;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $attach)
    {
        $this->date = $date;
        $this->attach = $attach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Monthly Maintenance Record')
                    ->view('fleets.mmr-email')
                    ->attach($this->attach, 
                                [
                                    // 'as' => 'MMR-' . $this->date . '.pdf',
                                    'mime' => 'application/pdf',
                                ]
                            );
    }
}
