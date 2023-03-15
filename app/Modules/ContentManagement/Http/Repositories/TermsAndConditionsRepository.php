<?php

namespace App\Modules\ContentManagement\Http\Repositories;

use App\Modules\ContentManagement\Models\TermsAndConditions;

class TermsAndConditionsRepository
{
    public function __construct(TermsAndConditions $termsandconditions) {
        $this->termsandconditions = $termsandconditions;
    }

    public function create($payload) {
        return $this->termsandconditions->create($payload);
    }

    public function update($id, $payload, $withTrash = false) {
        return $this->termsandconditions
                    ->find($id)->update($payload);
    }

    public function getTermsDocumentData($id)
    {
        $termsandcondition = TermsAndConditions::find($id);

        $initialpreview = [];
        $initialpreviewconfig = [];
        if($termsandcondition) {
            $ext = pathinfo(asset($termsandcondition->file_path), PATHINFO_EXTENSION);
            if (in_array($ext, ["jpg", "jpeg", "png"])) {
                $ext = 'image';
            }

            $initialpreview[] = $termsandcondition->full_path;
            $initialpreviewconfig[] = [
                'caption'=>'terms_and_condition',
                'type' => $ext,
                // 'size'=>'57071', 'width'=>"263px", 'height'=>"217px",
                'url'=>'/manage/termsandconditions/'.$termsandcondition->id.'/document_delete',
                'key'=>$termsandcondition->id,
                'extra' => ['_token'=>csrf_token()]
            ];
        }

        return array(
            'initialpreview'=>$initialpreview,
            'initialpreviewconfig'=>$initialpreviewconfig,
        );
    }
}