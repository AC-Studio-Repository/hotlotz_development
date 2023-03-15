<?php

namespace App\Modules\Xero\Repositories;

use XeroAPI\XeroPHP\Api\AccountingApi;
use Webfox\Xero\OauthCredentialManager;
use App\Modules\Customer\Models\Customer;
use XeroAPI\XeroPHP\Models\Accounting\Phone;
use XeroAPI\XeroPHP\Models\Accounting\Address;
use XeroAPI\XeroPHP\Models\Accounting\Contact;
use XeroAPI\XeroPHP\Models\Accounting\TaxType;
use XeroAPI\XeroPHP\Models\Accounting\Contacts;

class XeroContactRepository
{
    public $apiInstance;

    public function __construct(
        OauthCredentialManager $xeroCredentials,
        AccountingApi $apiInstance
    ) {
        $this->xeroCredentials = $xeroCredentials;
        $this->apiInstance = $apiInstance;
    }

    public function init($arg)
    {
        $this->apiInstance = $arg;
    }

    public function createOrGetContact($customer_id)
    {
        $xeroConfig = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration();
        $this->xeroCredentials->refresh();
        $xeroConfig->setAccessToken($this->xeroCredentials->getAccessToken());

        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $customer = Customer::findOrFail($customer_id);
        if (!is_null($customer->contact_id)) {
            $result = $apiInstance->getContact($xeroTenantId, $customer->contact_id);
            \Log::channel('xeroLog')->info('Get Xero Contact By ' . $customer->name);

            return $result->getContacts()[0]->getContactId();
        }

        $arr_contacts = [];

        $buyerTaxType = TaxType::ZERORATEDOUTPUT;

        if ($customer->buyer_gst_status == 1) {
            $buyerTaxType = "OUTPUTY23";
        }

        $sellerTaxType = TaxType::ZERORATEDINPUT;

        if ($customer->seller_gst_registered == 1) {
            $sellerTaxType = TaxType::INPUT;
        }

        $phones = $this->setPhoneObject($customer);

        $addresses = $this->setAddressObject($customer);

        $contact = $this->setContactObject($customer, $phones, $addresses, $buyerTaxType, $sellerTaxType);

        array_push($arr_contacts, $contact);

        $contacts = new Contacts;
        $contacts->setContacts($arr_contacts);

        $result = $apiInstance->createContacts($xeroTenantId, $contacts, true);

        $customer->contact_id = $result->getContacts()[0]->getContactId();
        $customer->save();

        if ($customer->bank_name != null || $customer->bank_account_number != null
            || $customer->bank_account_name != null || $customer->swift != null
            || $customer->account_currency != null || $customer->iban != null
            || $customer->bank_address != null
        ) {
            $this->setContactNote($customer);
        }
        \Log::channel('xeroLog')->info('Get Xero Contact By ' . $customer->name);

        return $result->getContacts()[0]->getContactId();
    }

    public function createOrUpdateContact($payload, $xeroTenantId, $apiInstance, $returnObj=false)
    {
        if ($this->xeroCredentials->isExpired()) {
            Log::channel('xeroLog')->error('Credential Expire in Job');
            $xeroConfig = \XeroAPI\XeroPHP\Configuration::getDefaultConfiguration();
            $this->xeroCredentials->refresh();
            sleep(1);
            $xeroConfig->setAccessToken($this->xeroCredentials->getAccessToken());
            Log::channel('xeroLog')->error('New Credential Refresh in Job');
        }
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $customer = Customer::findOrFail($payload);

        $str = '';

        $arr_contacts = [];

        $buyerTaxType = TaxType::ZERORATEDOUTPUT;

        if ($customer->buyer_gst_status == 1) {
            $buyerTaxType = "OUTPUTY23";
        }

        $sellerTaxType = TaxType::ZERORATEDINPUT;

        if ($customer->seller_gst_registered == 1) {
            $sellerTaxType = TaxType::INPUT;
        }

        $phones = $this->setPhoneObject($customer);

        $addresses = $this->setAddressObject($customer);

        if (!is_null($customer->contact_id)) {
            $contact = $this->setContactObject($customer, $phones, $addresses, $buyerTaxType, $sellerTaxType);

            $result = $apiInstance->updateContact($xeroTenantId, $customer->contact_id, $contact);

            $str = $str . "Update Contacts: " . $result->getContacts()[0]->getName();
        } else {
            $contact = $this->setContactObject($customer, $phones, $addresses, $buyerTaxType, $sellerTaxType);
            array_push($arr_contacts, $contact);

            $contacts = new Contacts;
            $contacts->setContacts($arr_contacts);

            $result = $apiInstance->createContacts($xeroTenantId, $contacts, true);

            $customer->contact_id = $result->getContacts()[0]->getContactId();
            $customer->save();
            return [
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'swift',
                'account_currency',
                'iban',
                'bank_address'
            ];
            if ($customer->bank_name != null || $customer->bank_account_number != null
                || $customer->bank_account_name != null || $customer->swift != null
                || $customer->account_currency != null || $customer->iban != null
                || $customer->bank_address != null
            ) {
                $this->setContactNote($customer);
            }

            $str = $str . "Create Contact: " . $result->getContacts()[0]->getName();
        }

        if ($returnObj) {
            return $result;
        } else {
            return $str;
        }
    }

    protected function setPhoneObject($customer)
    {
        $phones = [];

        if ($customer->phone) {
            $phone = new Phone;
            $phone->setPhoneType(Phone::PHONE_TYPE__DEFAULT)
                ->setPhoneNumber($customer->phone);

            array_push($phones, $phone);
        }
        if ($customer->fax_number) {
            $phone = new Phone;
            $phone->setPhoneType(Phone::PHONE_TYPE_FAX)
                ->setPhoneNumber($customer->fax_number);

            array_push($phones, $phone);
        }
        if ($customer->mobile_phone) {
            $phone = new Phone;
            $phone->setPhoneType(Phone::PHONE_TYPE_MOBILE)
                ->setPhoneNumber($customer->mobile_phone);

            array_push($phones, $phone);
        }

        return $phones;
    }

    protected function setAddressObject($customer)
    {
        $addresses = [];

        $address = new Address;
        $address->setAddressType(Address::ADDRESS_TYPE_POBOX)
            ->setAddressLine1($customer->address1)
            ->setCity($customer->city)
            ->setRegion($customer->state)
            ->setPostalCode($customer->postal_code)
            ->setCountry(isset($customer->country) ? $customer->country->name : null);

        array_push($addresses, $address);

        return $addresses;
    }

    protected function setContactObject($customer, $phones, $addresses, $buyerTaxType, $sellerTaxType)
    {
        $bank = new \XeroAPI\XeroPHP\Models\Accounting\BatchPaymentDetails;
        $bank->setBankAccountNumber($customer->bank_account_number)
            ->setBankAccountName($customer->bank_account_name);

        $contact = new Contact;

        $contact->setName($customer->xerofullname)
            ->setAccountNumber($customer->ref_no)
            ->setFirstName($customer->firstname)
            ->setLastName($customer->lastname)
            ->setEmailAddress($customer->email)
            ->setPhones($phones)
            ->setAddresses($addresses)
            ->setTaxNumber($customer->vat_number)
            ->setAccountsReceivableTaxType($buyerTaxType)
            ->setAccountsPayableTaxType($sellerTaxType)
            ->setSalesDefaultAccountCode('200')
            ->setPurchasesDefaultAccountCode('300')
            ->setDefaultCurrency('SGD')
            ->setBankAccountDetails($customer->bank_account_number)
            ->setBatchPayments($bank);

        return $contact;
    }

    public function setContactNote($customer)
    {
        $xeroTenantId = $this->xeroCredentials->getTenantId();
        $apiInstance = $this->apiInstance;

        $arr_history_records = [];

        $note = '';
        $note .= 'Bank Name - ' . $customer->bank_name ?? 'N/A';
        $note .= ', Bank Account Number - ' . $customer->bank_account_number ?? 'N/A';
        $note .= ', Bank Account Name - ' . $customer->bank_account_name ?? 'N/A';
        $note .= ', Swift Code - ' . $customer->swift ?? 'N/A';
        $note .= ', Account Currency - ' . $customer->account_currency ?? 'N/A';
        $note .= ', Additional Note - ' . $customer->iban ?? 'N/A';
        $note .= ', Bank Address - ' . $customer->bank_address ?? 'N/A';

        $history_record = new \XeroAPI\XeroPHP\Models\Accounting\HistoryRecord;
        $history_record->setDateUtc('2222-01-01T00:00:00');
        $history_record->setDetails($note);

        $arr_history_records[] = $history_record;

        $history_records = new \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords;
        $history_records->setHistoryRecords($arr_history_records);

        $apiInstance->createContactHistory($xeroTenantId, $customer->contact_id, $history_records);
    }
}
