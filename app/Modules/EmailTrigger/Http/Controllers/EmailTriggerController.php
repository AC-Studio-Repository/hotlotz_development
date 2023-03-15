<?php

namespace App\Modules\EmailTrigger\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\EmailTrigger\Http\Requests\StoreEmailTriggerRequest;
use App\Modules\EmailTrigger\Http\Requests\UpdateEmailTriggerRequest;
use App\Modules\EmailTrigger\Http\Repositories\EmailTriggerRepository;
use App\Modules\EmailTrigger\Http\Resources\CategoryDatatableResource;
use App\Modules\EmailTrigger\Models\EmailTrigger;
use App\Modules\EmailTemplate\Models\EmailTemplate;
// use Yajra\Datatables\Datatables;
use Illuminate\Http\Response;
// use App\Events\EmailTemplateEvent;
use App\Jobs\MailJob;
use App\Helpers\NHelpers;
use DB;

class EmailTriggerController extends Controller
{
    protected $emailTriggerRepository;
    public function __construct(EmailTriggerRepository $emailTriggerRepository){
        $this->emailTriggerRepository = $emailTriggerRepository;
    }

    /**
     * Displays the category index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $email_template = EmailTemplate::all();

        return view('email_trigger::index', [
            'email_trigger' => $email_template
        ]);
    }

    public function sendEmailEventAjax(Request $request)
    {
        $selected_email_template_id = json_decode($request->selected_tempate);

        try {
            ## Send Email by Event ##
            $email_template = EmailTemplate::find((integer)$selected_email_template_id);
            $data = [
                'template' => 'emails.mail',
                'to_email' => 'zinsummi@gmail.com',
                'subject' => $email_template->title,
                'content' => $email_template->content,
                'customer' => 'Test',
            ];
            // event(new EmailTemplateEvent($data));
            // \Log::info('dispatch MailJob');
            // MailJob::dispatch($data);
            return response()->json(array('status' => '1','message'=>'Email Event Send Successfully.'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }
}
