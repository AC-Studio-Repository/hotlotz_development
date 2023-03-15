<?php

namespace App\Modules\DocumentModule\Repositories;

use DB;
use App\User;
use App\Modules\DocumentModule\Models\DocumentModule;

class DocumentModuleRepository
{
    public function __construct(DocumentModule $document)
    {
        $this->document = $document;
    }

    public function create($payload)
    {
        return $this->document->create($payload);
    }

    public function update($id, $payload, $withTrash = false)
    {
        return $this->document
                    ->when($withTrash, function ($query) {
                        return $query->withTrashed();
                    })
                    ->find($id)->update($payload);
    }

    public function getCutomerAddress($document_id)
    {
        $result = DB::table('document_addresses')
                ->where('document_addresses.document_id', '=', $document_id)
                ->join('addresses', 'addresses.id', 'document_addresses.address_id')
                ->select('addresses.*', 'document_addresses.*', 'addresses.id as address_id')
                ->orderBy('document_addresses.address_id', 'desc')
                ->get();

        $address = [];
        if(!$result->isEmpty()) {
            foreach ($result as $key => $value) {
                $country = Country::where('id', '=', $value->country_id)->first();
                $address[] = [
                    'address_id' => $value->address_id,
                    'country_id' => $value->country_id,
                    'country_name' => $country->name,
                    'postalcode' => $value->postalcode,
                    'city' => $value->city,
                    'type' => $value->type,
                    'address' => $value->address,
                    'address2' => $value->address2,
                    'firstname' => $value->firstname,
                    'lastname' => $value->lastname,
                    'state' => $value->state,
                    'zip' => $value->zip_code,
                    'phone' => $value->daytime_phone,
                    'address_nickname' => $value->address_nickname,
                    'delivery_instruction' => $value->delivery_instruction,
                    'is_primary' => $value->is_primary
                ];
            }

            $address = collect($address);
        }

        return $address;
    }

}
