<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Subscriber;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

      protected $subject;
      protected $message;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($subject, $message)
    {
      $this->subject = $subject;
      $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $subscriber = Subscriber::all();
      foreach ($subscriber as $data)
      {
          send_email($data->email, 'Subscriber', $this->subject, $this->message);
      }
    }
}
