<?php

namespace App\Modules\EmailTrigger\Http\Repositories;

use App\Modules\EmailTrigger\Models\EmailTrigger;

class EmailTriggerRepository
{
    public function __construct(EmailTrigger $emailTrigger) {
        $this->emailTrigger = $emailTrigger;
    }
}