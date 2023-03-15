<?php

namespace App\Console;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\DemoCron::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('demo:cron')
        //          ->everyMinute()
        //          ->appendOutputTo(storage_path().'/logs/test.log');


        ## CheckAuctionsStatus Command
        // $schedule->command('gap:get_auctions_status')
                 // ->hourly();
        ## CheckAuctionsApprove Command
        $schedule->command('gap:check_auctions_approve')
                 ->hourly();
        ## CheckAuctionsPublish Command
        $schedule->command('gap:check_auctions_publish')
                 ->hourly();

        # LifecycleChangeStage Command
        $schedule->command('lifecycle:change_stage')
                 ->hourly();

        // # GetEmailData Command
        $schedule->command('email:get_email_data')
                 ->cron('0 12 * * *');

        // # GetStorageEmailData Command
        $schedule->command('email:get_storage_email_data')
                 ->daily();

        # StorageFeeReminderEmail Command
        $schedule->command('email:storage_fee_reminder_email')
                 ->daily();

        # SecondStorageFeeReminderEmail Command
        $schedule->command('email:second_storage_fee_reminder_email')
                 ->daily();

        $schedule->command('generate:invoice')
                 ->everyTenMinutes();

        $schedule->command('gap:update_closed_auction_status')
                 ->daily();
                 // ->cron('0 0 * * 0');

        // $schedule->command('schedule:log')->everyMinute();

        $schedule->command('weekly:sell_with_us')
                 ->weeklyOn(2, '9:00');
                 // ->weekly()->sundays()->at('17:53');

        $schedule->command('call:check_auctions')
                 ->dailyAt('23:00');

        $schedule->command('daily:items_moved_to_storage_email')
                 ->dailyAt('9:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        $this->load(__DIR__.'/Commands/gap');
        $this->load(__DIR__.'/Commands/xero');
        $this->load(__DIR__.'/Commands/Mailchimp');
        require base_path('routes/console.php');
    }
}
