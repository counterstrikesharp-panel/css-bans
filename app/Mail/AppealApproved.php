<?php

namespace App\Mail;

use App\Models\Appeal\Appeal;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppealApproved extends Mailable
{
    use Queueable, SerializesModels;

    protected $appeal;

    public function __construct(Appeal $appeal)
    {
        $this->appeal = $appeal;
    }

    public function build()
    {
        return $this->subject('Your Ban Appeal has been'." ".ucfirst($this->appeal->status))
            ->view('appeals.email.appeal_status')
            ->with([
                'appeal' => $this->appeal,
            ]);
    }
}
