<?php

namespace App\Modules\Recaptcha\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use App\Modules\Recaptcha\Helpers\ServerRecaptcha;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->concord->registerHelper('recaptcha', ServerRecaptcha::class);
    }
}
