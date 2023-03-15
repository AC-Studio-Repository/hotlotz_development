<?php

namespace App\Modules\Customer\Observers;

use App\Models\Country;
use Illuminate\Support\Facades\Log;
use App\Events\Xero\XeroContactEvent;
use App\Events\Admin\KycUpdateAlertEvent;
use App\Modules\Customer\Models\Customer;
use App\Events\Client\ProfileUpdateAlertEvent;
use App\Events\Client\BankAccountUpdateAlertEvent;

class CustomerObserver
{
    /**
     * Gets changes columns
     *
     * @return string[]
     */
    public function getChangesColumns()
    {
        return [
            'firstname',
            'lastname',
            'email',
            'buyer_gst_registered',
            'seller_gst_registered',
            'phone',
            'address1',
            'city',
            'state',
            'postal_code',
            'country_id',
        ];
    }

    public function getChangesBankColumns()
    {
        return [
            'bank_name',
            'bank_account_number',
            'bank_account_name',
            'swift',
            'account_currency',
            'iban',
            'bank_address'
        ];
    }

    public function getChangesMailchimpColumns()
    {
        return [
            'email',
            'firstname',
            'lastname',
            'phone',
            'marketing_auction',
            'marketing_marketplace',
            'marketing_chk_events',
            'marketing_chk_congsignment_valuation',
            'marketing_hotlotz_quarterly',
            'exclude_marketing_material'
        ];
    }

    public function getChangesBankAccount()
    {
        return [
            'bank_account_number',
        ];
    }

    public function getChangesProfileColumns()
    {
        return [
            'title',
            'salutation',
            'firstname',
            'lastname',
            'fullname',
            'email',
            'reg_behalf_company',
            'company_name',
            'country_of_residence',
            'buyer_gst_registered',
            'dialling_code',
            'phone',
            'reg_gst_sg',
            'seller_gst_registered',
            'sg_uen_number',
            'gst_number',
        ];
    }

    public function getChangesKyc()
    {
        return [
            'legal_name',
            'date_of_birth',
            'occupation',
            'citizenship_type',
            'citizenship_one',
            'citizenship_two',
            'id_type',
            'nric',
            'nric_document_ids',
            'fin',
            'fin_document_ids',
            'passport',
            'country_of_issue',
            'passport_expiry_date',
            'passport_document_ids',
        ];
    }

    /**
     * Handle the post "saved" event.
     *
     * @param  \App\Modules\Customer\Models\Customer $customer
     * @return void
     */
    public function saved(Customer $customer)
    {
        // dd($customer->getOriginal('bank_account_number'));
        // dd($customer->isDirty('bank_account_number'));
        // dd($customer->getChanges());
        if ($customer->wasRecentlyCreated) {
            \Log::channel('xeroLog')->info('Customer Create Observer Work');
            // event(new XeroContactEvent($customer->id));
            event(new \App\Events\Mailchimp\AddOrUpdateEvent($customer->id));
        } else {
            if ($customer->wasChanged($this->getChangesColumns())) {
                if ($customer->contact_id != null) {
                    \Log::channel('xeroLog')->info('Customer Update Observer Work');
                    event(new XeroContactEvent($customer->id));
                }
            }
            if ($customer->wasChanged($this->getChangesBankColumns())) {
                if ($customer->contact_id != null) {
                    if($customer->country_id == '702'){
                        $updateContact = resolve('App\Modules\Xero\Repositories\XeroContactRepository');
                        $updateContact->setContactNote($customer);
                    }
                }
            }

            if ($customer->wasChanged($this->getChangesMailchimpColumns())) {
                event(new \App\Events\Mailchimp\AddOrUpdateEvent($customer->id));
            }

            if ($customer->wasChanged($this->getChangesBankAccount()) && $customer->bank_account_number != null) {

                $old_bank_acc_number = $customer->getOriginal('bank_account_number');
                \Log::info('Customer Old Bank Account Number is '.$old_bank_acc_number);
                $old_data['old_bank_account_number'] = $old_bank_acc_number;

                $info_data['bank_account_number']['label'] = 'Bank Account Number';
                $info_data['bank_account_number']['old'] = $old_bank_acc_number;
                $info_data['bank_account_number']['update'] = $customer->bank_account_number;

                \Log::info('Customer updates Bank Account Number to '.$customer->bank_account_number);
                event( new BankAccountUpdateAlertEvent($customer->id, $old_data, $info_data) );
            }

            if ($customer->wasChanged($this->getChangesProfileColumns())) {

                $old_data = [];
                $info_data = [];

                if($customer->wasChanged('salutation') && $customer->salutation != null)
                {
                    $old_salutation = $customer->getOriginal('salutation');
                    $old_data['old_salutation'] = $old_salutation;
                    $old_data['old_title'] = $old_salutation;

                    $info_data['salutation']['label'] = 'Salutation';
                    $info_data['salutation']['old'] = $old_salutation;
                    $info_data['salutation']['update'] = $customer->salutation;
                }

                if($customer->wasChanged('firstname') && $customer->firstname != null)
                {
                    $old_firstname = $customer->getOriginal('firstname');
                    $old_data['old_firstname'] = $old_firstname;
                    $info_data['firstname']['label'] = 'First Name';
                    $info_data['firstname']['old'] = $old_firstname;
                    $info_data['firstname']['update'] = $customer->firstname;
                }

                if($customer->wasChanged('lastname') && $customer->lastname != null)
                {
                    $old_lastname = $customer->getOriginal('lastname');
                    $old_data['old_lastname'] = $old_lastname;
                    $info_data['lastname']['label'] = 'Last Name';
                    $info_data['lastname']['old'] = $old_lastname;
                    $info_data['lastname']['update'] = $customer->lastname;
                }

                if($customer->wasChanged(['firstname','lastname']) && $customer->firstname != null && $customer->lastname != null)
                {
                    $old_fullname = $customer->getOriginal('fullname');
                    $old_data['old_fullname'] = $old_fullname;
                }

                if($customer->wasChanged('email') && $customer->email != null)
                {
                    $old_email = $customer->getOriginal('email');
                    $old_data['old_email'] = $old_email;
                    $info_data['email']['label'] = 'Email';
                    $info_data['email']['old'] = $old_email;
                    $info_data['email']['update'] = $customer->email;
                }

                if($customer->wasChanged('reg_behalf_company') && $customer->reg_behalf_company != null)
                {
                    $old_reg_behalf_company = $customer->getOriginal('reg_behalf_company');
                    $old_data['old_reg_behalf_company'] = $old_reg_behalf_company;
                    $info_data['reg_behalf_company']['label'] = 'Registering on behalf of a Company?';
                    $info_data['reg_behalf_company']['old'] = ($old_reg_behalf_company == 1)?'Yes':'No';
                    $info_data['reg_behalf_company']['update'] = ($customer->reg_behalf_company == 1)?'Yes':'No';
                }

                if($customer->wasChanged('company_name') && $customer->company_name != null)
                {
                    $old_company_name = $customer->getOriginal('company_name');
                    $old_data['old_company_name'] = $old_company_name;
                    $info_data['company_name']['label'] = 'Company Name';
                    $info_data['company_name']['old'] = $old_company_name;
                    $info_data['company_name']['update'] = $customer->company_name;
                }

                if($customer->wasChanged('country_of_residence') && $customer->country_of_residence != null)
                {
                    $old_country_of_residence = $customer->getOriginal('country_of_residence');
                    $old_data['old_country_of_residence'] = $old_country_of_residence;

                    $old_country = Country::where('id', '=', $old_country_of_residence)->first();
                    $info_data['country_of_residence']['label'] = 'Country of Residence';
                    $info_data['country_of_residence']['old'] = $old_country->name;
                    $info_data['country_of_residence']['update'] = $customer->country->name;
                }

                if($customer->wasChanged('buyer_gst_registered') && $customer->buyer_gst_registered != null)
                {
                    $old_buyer_gst_registered = $customer->getOriginal('buyer_gst_registered');
                    $old_data['old_buyer_gst_registered'] = $old_buyer_gst_registered;
                    $info_data['buyer_gst_registered']['label'] = 'Buyer GST Registered?';
                    $info_data['buyer_gst_registered']['old'] = ($old_buyer_gst_registered == 1)?'Yes':'No';
                    $info_data['buyer_gst_registered']['update'] = ($customer->buyer_gst_registered == 1)?'Yes':'No';
                }

                if($customer->wasChanged('dialling_code') && $customer->dialling_code != null)
                {
                    $old_dialling_code = $customer->getOriginal('dialling_code');
                    $old_data['old_dialling_code'] = $old_dialling_code;
                    $info_data['dialling_code']['label'] = 'Dialling Code';
                    $info_data['dialling_code']['old'] = $old_dialling_code;
                    $info_data['dialling_code']['update'] = $customer->dialling_code;
                }

                if($customer->wasChanged('phone') && $customer->phone != null)
                {
                    $old_phone = $customer->getOriginal('phone');
                    $old_data['old_phone'] = $old_phone;
                    $info_data['phone']['label'] = 'Phone';
                    $info_data['phone']['old'] = $old_phone;
                    $info_data['phone']['update'] = $customer->phone;
                }

                if($customer->wasChanged('reg_gst_sg') && $customer->reg_gst_sg != null)
                {
                    $old_reg_gst_sg = $customer->getOriginal('reg_gst_sg');
                    $old_data['old_reg_gst_sg'] = $old_reg_gst_sg;
                    $info_data['reg_gst_sg']['label'] = 'Registered for GST in Singapore?';
                    $info_data['reg_gst_sg']['old'] = ($old_reg_gst_sg == 1)?'Yes':'No';
                    $info_data['reg_gst_sg']['update'] = ($customer->reg_gst_sg == 1)?'Yes':'No';
                }

                if($customer->wasChanged('seller_gst_registered') && $customer->seller_gst_registered != null)
                {
                    $old_seller_gst_registered = $customer->getOriginal('seller_gst_registered');
                    $old_data['old_seller_gst_registered'] = $old_seller_gst_registered;
                    $info_data['seller_gst_registered']['label'] = 'Seller GST Registered?';
                    $info_data['seller_gst_registered']['old'] = ($old_seller_gst_registered == 1)?'Yes':'No';
                    $info_data['seller_gst_registered']['update'] = ($customer->seller_gst_registered == 1)?'Yes':'No';
                }

                if($customer->wasChanged('sg_uen_number') && $customer->sg_uen_number != null)
                {
                    $old_sg_uen_number = $customer->getOriginal('sg_uen_number');
                    $old_data['old_sg_uen_number'] = $old_sg_uen_number;
                    $info_data['sg_uen_number']['label'] = 'Singapore UEN Number';
                    $info_data['sg_uen_number']['old'] = $old_sg_uen_number;
                    $info_data['sg_uen_number']['update'] = $customer->sg_uen_number;
                }

                if($customer->wasChanged('gst_number') && $customer->gst_number != null)
                {
                    $old_gst_number = $customer->getOriginal('gst_number');
                    $old_data['old_gst_number'] = $old_gst_number;
                    $info_data['gst_number']['label'] = 'GST Number';
                    $info_data['gst_number']['old'] = $old_gst_number;
                    $info_data['gst_number']['update'] = $customer->gst_number;
                }

                \Log::info('Customer Profile Update Alert '.$customer->ref_no);
                \Log::info('Customer Old Data : '.print_r($old_data,true));
                event( new ProfileUpdateAlertEvent($customer->id, $old_data, $info_data) );
            }

            if ( $customer->wasChanged($this->getChangesKyc()) ) {
                \Log::info('KYC Update Alert for Customer '.$customer->ref_no);
                \Log::channel('emailLog')->info('KYC Update Alert for Customer '.$customer->ref_no);
                event( new KycUpdateAlertEvent($customer->id) );
            }
        }
    }
}
