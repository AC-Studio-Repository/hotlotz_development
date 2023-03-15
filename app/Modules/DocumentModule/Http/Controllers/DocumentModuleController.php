<?php

namespace App\Modules\DocumentModule\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Konekt\User\Models\User;
use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Modules\DocumentModule\Models\DocumentFile;
use App\Modules\DocumentModule\Models\DocumentModule;
use App\Modules\DocumentModule\Repositories\DocumentModuleRepository;

class DocumentModuleController extends Controller
{
    protected $documentRepository;

    public function __construct(
        DocumentModuleRepository $documentRepository
    ) {
        $this->documentRepository = $documentRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($document_type = null)
    {
        $documents = DocumentModule::where('document_type', $document_type)->paginate(10);

        $data = [
            'documents' => $documents,
            'document_type' => $document_type,
        ];

        return view('document_module::index', $data);
    }

    public function getDocuments($document_type = null, Request $request)
    {
        $type = $request->type;
        if ($type == 'archive') {
            $documents = DocumentModule::onlyTrashed()->where('document_type', $document_type)->paginate(10);
        }else{
            $documents = DocumentModule::where('document_type', $document_type)->paginate(10);
        }

        $data = [
            'documents' => $documents,
            'document_type' => $document_type,
        ];

        if($document_type == 'roster'){
            return view('document_module::roster_index', $data);
        }else{
            return view('document_module::index', $data);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $adminUsers = User::pluck('name', 'id')->all();

        return view('document_module::create',
        [
            'document' => app(DocumentModule::class),
            'adminUsers' => $adminUsers
        ]);
    }
    public function createDocument($doc_type)
    {
        // dd('createDocument');
        $adminUsers = User::pluck('name', 'id')->all();

        return view('document_module::create',
        [
            'document' => app(DocumentModule::class),
            'adminUsers' => $adminUsers,
            'document_type' => $doc_type,
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $file_name = null;
            $file_path = null;
            $full_path = $request->document_url;
            $document_type = $request->document_type;

            $payload = DocumentModule::packData($request);
            $document = $this->documentRepository->create($payload);

            if ($document) {
                if($request->type == 'file' && $request->file('document_file')){
                    $document_file = $request->file('document_file');
                    $folder_path = 'document/'.$document_type.'/'.$document->id;

                    $file_name = $document_file->getClientOriginalName();
                    $file_path = Storage::put($folder_path, $document_file);
                    $full_path = Storage::url($file_path);
                }

                $document->file_name = $file_name;
                $document->file_path = $file_path;
                $document->full_path = $full_path;
                $document->save();

                DB::commit();
                flash()->success(__('Document has been created'));
                return redirect(route('document.documents.get_documents', $document_type));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Document create failed']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  model  $document
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentModule $document)
    {
        dd('show');
        // return view('document_module::show', [
        //     'document' => $document
        // ]);
    }   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  model  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(DocumentModule $document)
    {
        $adminUsers = User::pluck('name', 'id')->all();

        $data = [
            'document' => $document,
            'adminUsers' => $adminUsers,
            'document_type' => $document->document_type
        ];
        return view('document_module::edit', $data);
    }
    
    public function update(Request $request, DocumentModule $document)
    {
        DB::beginTransaction();
        try {
            $file_name = null;
            $file_path = null;
            $full_path = $request->document_url;
            $document_type = $request->document_type;

            $payload = DocumentModule::packData($request);
            $result = $this->documentRepository->update($document->id, $payload);

            if ($result) {
                if($request->type == 'file' && $request->file('document_file')){
                    // dd($request->document_file);
                    $document_file = $request->file('document_file');
                    $folder_path = 'document/'.$document->id;
                    if($document_type != null){
                        $folder_path = 'document/'.$document_type.'/'.$document->id;
                    }

                    $file_name = $document_file->getClientOriginalName();
                    $file_path = Storage::put($folder_path, $document_file);
                    $full_path = Storage::url($file_path);
                }

                if($full_path != null){
                    $document->file_name = $file_name;
                    $document->file_path = $file_path;
                    $document->full_path = $full_path;
                    $document->save();
                }

                DB::commit();
                flash()->success(__(':name has been updated', ['name' => $request->title]));
                return redirect(route('document.documents.get_documents', $document_type ?? ''));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Document update failed']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */    
    public function storeOLD(Request $request)
    {
        if($request->type == 'file' && $request->file('document_file')){
            $path = 'document/'.time();
            $result = StorageHelper::store($path, array($request->file('document_file')), $wipeExisting=true);
            $full_path = $result[0]['data'];
        }

        $document = new DocumentModule;
        $document->title = $request->title;
        $document->publish_date = $request->publish_date;
        $document->type = $request->type;
        $document->document_type = $request->document_type;
        $document->created_by = $request->created_by;
        $document->save();

        $documentFile = new DocumentFile;
        $documentFile->file_path = $path;
        $documentFile->full_path = $full_path;
        $documentFile->document_id = $document->id;
        $documentFile->save();

        flash()->success(__('Document has been created'));
        return redirect(route('document.documents.index'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  model  $document
     * @return \Illuminate\Http\Response
     */

    public function updateOLD(Request $request, DocumentModule $document)
    {
        $checkOldType = $document->type;
        $path = null;
        $full_path = $request->document_url;

        if ($request->type == 'file' && $request->file('document_file')) {
            $path = 'document/'.time();
            $result = StorageHelper::store($path, array($request->file('document_file')), $wipeExisting=true);
            $full_path = $result[0]['data'];
        }
        $document->title = $request->title;
        $document->publish_date = $request->publish_date;
        $document->type = $request->type;
        $document->created_by = $request->created_by;
        $document->save();

        $documentFiles = $document->files;
        foreach ($documentFiles as $file) {
            if ($checkOldType == 'file' && $file->file_path != null) {
                StorageHelper::delete($file->file_path);
            }
            $file->delete();
        }

        $documentFile = new DocumentFile;
        $documentFile->file_path = $path;
        $documentFile->full_path = $full_path;
        $documentFile->document_id = $document->id;
        $documentFile->save();

        flash()->success(__(':name has been updated', ['name' => $request->title]));

        return redirect(route('document.documents.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  model  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentModule $document)
    {
        // if ($document->type == 'file' && Storage::exists($document->file_path)) {
        ////     Storage::delete($document->file_path);
        // }

        // $documentFiles = $document->files;
        // foreach($documentFiles as $file){
        //     if ($document->type == 'file' && $file->file_path != null) {
        //         StorageHelper::delete($file->file_path);
        //     }
        //     $file->delete();
        // }
        
        $document->delete();

        return response()->json([ 'status'=>'success', 'message' => $document->title.' has been deleted']);
    }

    public function restoreDocument($id)
    {
        $document = DocumentModule::withTrashed()->where('id',$id)->first();
        $document->restore();

        flash()->success(__('This item move to Active'));
        return redirect(route('document.documents.get_documents', $document->document_type ?? ''));
    }
}
