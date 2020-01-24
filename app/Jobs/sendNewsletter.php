<?php

namespace App\Jobs;

use App\Mail\newsletter;
use App\Mail\NewsLetterMail;
use App\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class sendNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Product::where('is_email_send',0)->get();
        $subscribers = \App\Newsletter::all();
        foreach ($products as $product) {
            foreach ($subscribers as $subscriber) {

                Mail::to('muhammadwaqar6868@gmail.com')->send(new NewsLetterMail($product));
            }
        }
    }
}
