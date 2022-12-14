<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\UserOrganization;
use App\Models\User;
use App\Models\TypeDocument;
use App\Http\Resources\TypeDocumentResource;
use App\Http\Resources\IncomingDocumentCollection;

use Illuminate\Http\Request;
use Auth;

class DocumentController extends Controller
{
    public function incoming_documents()
    {
        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $documents = Document::paginate($per_page);

        return response()->json([
            'documents' => new IncomingDocumentCollection($documents)
        ]);
    }

    public function outgoing_messages()
    {
        $user = auth()->user()->userorganization;

        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $documents = Document::where('send_user_id',$user->user_id)
            ->with(['rec_user','type_document'])->paginate($per_page);

        return response()->json([
            'documents' => new IncomingDocumentCollection($documents)
        ]);
    }

    public function send_document_get()
    {
        $users = User::whereHas(
            'roles', function($q){
                $q->where('name', 'Admin');
            }
        )->get();

        $type_documents = TypeDocument::get();

        return response()->json([
            'users' => $users,
            'type_documents' => TypeDocumentResource::collection($type_documents)
        ]);
        
    }

    public function send_document(Request $request, $to_user_id)
    {
        $validated = $request->validate([
            'to_date' => ['date']
        ]);

        $user = auth()->user()->userorganization;
        $to_user = UserOrganization::where('user_id', $to_user_id)->first();

        $newDocument = new Document();
        $newDocument->management_id = $user->management_id;
        $newDocument->railway_id = $user->railway_id;
        $newDocument->organization_id = $user->organization_id;
        $newDocument->to_management_id = $to_user->management_id;
        $newDocument->to_railway_id = $to_user->railway_id;
        $newDocument->to_organization_id = $to_user->organization_id;
        $newDocument->send_user_id = $user->user_id;
        $newDocument->rec_user_id = $to_user_id;

        if($request->executor_id)
        $newDocument->executor_id = $user->user_id;

        if($request->file_status)
        {
            if($request->file1) {
                $fileName1 = time() . $request->file1->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName, File::get($request->file1));
                $filePath1 = 'storage/files/' . $fileName1;
            }
    
            if($request->file2) {
                $fileName2 = time() . $request->file2->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName, File::get($request->file2));
                $filePath2 = 'storage/files/' . $fileName2;
            }
    
            if($request->file3) {
                $fileName3 = time() . $request->file3->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName, File::get($request->file3));
                $filePath3 = 'storage/files/' . $fileName3;
            }

            $newDocument->file_status = true;
            $newDocument->file1 = $filePath1;
            $newDocument->file2 = $filePath2;
            $newDocument->file3 = $filePath3;

        } else {

            $newDocument->comment = $request->comment;

        }

        $newDocument->type_document_id = $request->type_document_id;
        $newDocument->to_date = $request->to_date;
        $newDocument->status = false;
        $newDocument->save();

        return response()->json([
            'message' => 'successfully'
        ]);
    }
}
