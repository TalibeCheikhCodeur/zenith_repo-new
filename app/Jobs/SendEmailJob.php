<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\NotifMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\InterventionNotification;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    protected $emails;
    /**
     * Create a new job instance.
     */
    public function __construct($details, $emails)
    {
        $this->details = $details;
        $this->emails = $emails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->emails as $email) {
            $user = User::where('email', $email)->first(); // Trouver l'utilisateur par email

            if ($user) {
                $user->notify(new InterventionNotification($this->details));
            }
        }
    }
}
