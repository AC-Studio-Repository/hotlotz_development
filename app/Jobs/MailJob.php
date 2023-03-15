<?php

namespace App\Jobs;

use App\Exceptions\QueueFailReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTemplateMail;

class MailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 0;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('======= Start - MailJob =======');

        try {
            $data = $this->data;
            \Log::info('Email Data : '.print_r($data, true));

            Mail::to($data['to_email'])->send(new EmailTemplateMail($data));

            if (Mail::failures()) {
                \Log::info('Sorry! Please try again latter');
                if ($this->attempts() > $this->tries) {
                    \Log::info("extended 10 seconds");
                    $this->release(10);
                }
            } else {
                \Log::info('Great! Successfully send in your mail');
            }
        } catch (\Exception $e) {
            \Log::error("Caught Exception ('{$e->getMessage()}')\n{$e}\n");
            throw new QueueFailReport($e);
        }

        \Log::info('======= End - MailJob =======');
    }

    public function failed(\Exception $exception)
    {
        \Log::error('======= Failed - MailJob '. print_r($this->data, true) .'=======');
    }
}
