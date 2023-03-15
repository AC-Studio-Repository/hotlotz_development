<?php

namespace App\Modules\Customer\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

use Konekt\Customer\Models\Customer as Customer;

use Konekt\Customer\Models\CustomerType as CustomerType;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Customer::class,
        CustomerType::class,
    ];
}
