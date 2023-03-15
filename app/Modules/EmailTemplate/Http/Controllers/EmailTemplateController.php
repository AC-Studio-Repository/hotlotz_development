<?php

namespace App\Modules\EmailTemplate\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\EmailTemplate\Http\Requests\StoreEmailTemplateRequest;
use App\Modules\EmailTemplate\Http\Requests\UpdateEmailTemplateRequest;
use App\Modules\EmailTemplate\Http\Repositories\EmailTemplateRepository;
use App\Modules\EmailTemplate\Models\EmailTemplate;
use App\Events\EmailTemplateActionEvent;
use App\Jobs\MailJob;
use Carbon\Carbon;
use DB;

class EmailTemplateController extends Controller
{
    protected $emailTemplateRepository;
    public function __construct(EmailTemplateRepository $emailTemplateRepository){
        $this->emailTemplateRepository = $emailTemplateRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $email_templates = $this->emailTemplateRepository->all([], false, 100);

        $data = [
            'email_templates' => $email_templates,
        ];
        return view('email_template::index',$data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $email_template = app(EmailTemplate::class);

        $data = [
            'form_type' => 'create',
            'email_template' => $email_template,
        ];
        return view('email_template::create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmailTemplateRequest $request)
    {
        try {
            $email_template = EmailTemplate::create($this->packData($request));
            // dd($email_template);

            $data = [
                'title' => $email_template->title,
                'action' => 'created',
            ];
            event(new EmailTemplateActionEvent($data));

            flash()->success(__(':name has been created', ['name' => $email_template->title]));
            return redirect(route('email_template.email_templates.index'));
            
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(EmailTemplate $email_template)
    {
        // dd($email_template);
        $email_template = $this->emailTemplateRepository->show('id', $email_template->id, [], true);

        $data = [
            'form_type' => 'edit',
            'email_template' => $email_template,
        ];
        return view('email_template::edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmailTemplateRequest $request, $id)
    {
        try {
            // dd($request->all());
            // prepare variables
            $payload = $this->packData($request);
            // update email
            $this->emailTemplateRepository->update($id, $payload, true);

            $email_template = EmailTemplate::find($id);
            $data = [
                'title' => $email_template->title,
                'action' => 'updated',
            ];
            event(new EmailTemplateActionEvent($data));

            ## Send Email by Event ##
            // $email_template = EmailTemplate::find($id);
            // $data = [
            //     'template' => 'emails.mail',
            //     'to_email' => 'maycho.thet@gmail.com',
            //     'subject' => $email_template->title,
            //     'content' => $email_template->content,
            //     'customer' => 'Test',
            // ];
            // event(new EmailTemplateEvent($data));            
            // \Log::info('dispatch MailJob');
            // MailJob::dispatch($data);

            flash()->success(__(':name has been updated', ['name' => EmailTemplate::find($id)->title]));
            return redirect()->route('email_template.email_templates.index')->with('success', 'EmailTemplate Updated Successfully!');

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
            // return redirect()->route('email_template.email_templates.index')->with('fail', 'EmailTemplate Updating Failed!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            // check email can destroy or not
            // $canDestroy = $this->emailTemplateRepository->canDestroy($id);

            // if ($canDestroy) {
                $this->emailTemplateRepository->destroy($id);
                DB::commit();

                return redirect()->route('email_template.email_templates.index')->with('success', 'Email Template Deactivated Successfully!');
            // } else {
            //     return redirect()->route('email_template.email_templates.index')->with('fail', 'Cannot deactivate as this email is associated with childrens!');
            // }
                /* disable canDestory method . Need to update*/
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('email_template.email_templates.index')->with('fail', 'Email Template Deactivating Failed!');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try {
            $this->emailTemplateRepository->restore($id);
            DB::commit();

            return redirect()->route('email_template.email_templates.index')->with('success', 'Email Template Activated Successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('email_template.email_templates.index')->with('fail', 'Email Template Activating Failed!');
        }
    }
    
    protected function packData($request)
    {
        $payload['title'] = isset($request->title)?$request->title:null;
        $payload['description'] = isset($request->description)?$request->description:null;
        $payload['content'] = isset($request->content)?$request->content:null;

        return $payload;
    }
}