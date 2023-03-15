<?php

use Monolog\Handler\StreamHandler;
use Monolog\Handler\SyslogUdpHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'stderr'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/laravel.log'),
                'level' => 'debug',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'papertrail' => [
            'driver' => 'monolog',
            'level' => 'debug',
            'handler' => SyslogUdpHandler::class,
            'handler_with' => [
                'host' => env('PAPERTRAIL_URL'),
                'port' => env('PAPERTRAIL_PORT'),
            ],
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'formatter' => env('LOG_STDERR_FORMATTER'),
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'xeroLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/xero.log'),
                'level' => 'debug',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,
            ],
        ],

        'stripelog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/stripe.log'),
                'level' => 'debug',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,
            ],
        ],

        'checkAuctionLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/check_auction_queue.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'gapLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/gap.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'lifecycleLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/lifecycle.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'getAuctionsStatusLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/get_auctions_status.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'storageFeeReminderEmailLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/storage_fee_reminder_email.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'lotReorderingLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/lot_reordering.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'emailLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/email.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

         'scheduleLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/schedule.log'),
                'level' => 'debug',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,
            ],
        ],

        'checkoutItem' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/checkoutItem.log'),
                'level' => 'info',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,

            ],
        ],

        'mailchimpLog' => [
            'driver' => 'monolog',
            'handler' => Monolog\Handler\RotatingFileHandler::class,
            'with' => [
                'filename' => storage_path('logs/mailchimp.log'),
                'level' => 'debug',
                'maxFiles' => 14,
            ],
            'formatter' => Monolog\Formatter\LineFormatter::class,
            'formatter_with' => [
                'dateFormat' => 'c',
                'ignoreEmptyContextAndExtra' => true,
            ],
        ],

    ],

];
