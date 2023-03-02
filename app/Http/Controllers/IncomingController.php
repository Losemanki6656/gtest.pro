<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Worker;
use App\Models\TypeHistory;
use App\Models\Organization;
use App\Models\UserOrganization;
use App\Models\HistoryDocument;

use App\Http\Resources\OutgoingCollection;
use App\Http\Resources\WorkerCollection;
use App\Http\Resources\TypeHistoryResource;
use App\Http\Resources\SendDocumentOrganizationCollection;

class IncomingController extends Controller
{
    public function incoming_messages()
    {
        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $types_redirect = TypeHistory::get();

        $documents = Document::where('status_send', true)
            ->where('rec_user_id', auth()->user()->id)
            ->with(['rec_user','type_document','organization'])
            ->paginate($per_page);

        return response()->json([
            'types_redirect' => TypeHistoryResource::collection($types_redirect),
            'documents' => new OutgoingCollection($documents)
        ]);
    }

    public function workers($document_id)
    {
        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $workers = Worker::with([
            'languages',
            'driver_licensies',
            'education',
            'region',
            'city',
            'address_region',
            'address_city',
            'nationality',
            'academic_degree',
            'academic_title',
            'party'
        ])
        ->paginate($per_page);

        return response()->json([
            'workers' => new WorkerCollection($workers)
        ]);
    }

    public function update_message_to_worker($worker_id, Request $request)
    {
        if( !auth()->user()->hasRole('Admin') ) 
        return response()->json([
            'message' => 'Ruxsat etilmadi'
        ],403);

        $worker = Worker::find($worker_id);

        if($request->status_msg) {
            if($request->message) {
                $worker->file2 = $request->message;
                $worker->status_worker = true;
            } else {
                $worker->status_worker = false;
            }
        } else {
            $worker->status_worker = false;
            $worker->file2 = null;
        }

        $worker->save();

        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    public function ban_to_worker(Worker $worker, Request $request)
    {
        $worker->update($request->all());

        return response()->json([
            'message' => 'Successfully updated'
        ]);
    }

    public function received_users(Request $request)
    {
        if($request->type_redirect_id == 1)
            $organizations = Organization::query()
                ->where('id', auth()->user()->userorganization->organization_id)
                ->when(request('search'), function ( $query, $search) {
                    return $query->where('name', 'LIKE', '%'. $search .'%');
                    
                })
                ->with(['users'])->paginate(10);
        else 
            $organizations = Organization::query()
                ->when(request('search'), function ( $query, $search) {
                    return $query->where('name', 'LIKE', '%'. $search .'%');
                    
                })
                ->with(['users'])->paginate(10);

         return response()->json([
            'organizations' => new SendDocumentOrganizationCollection($organizations)
        ]);
    }

    public function redirect_document(Document $document_id, Request $request)
    {
        $redirects = HistoryDocument::where('send_user_id', auth()->user()->id)->where('rec_user_id', $request->to_user_id)->where('document_id', $document_id->id)->count();

        if($redirects) 
            return response()->json([
                'status' => false,
                'message' => 'Document is redirected to this user'
            ]);

        $user = auth()->user()->userorganization;
        $to_user = UserOrganization::where('user_id', $request->to_user_id)->first();

        $newDocument = new HistoryDocument();
        $newDocument->management_id = $user->management_id;
        $newDocument->railway_id = $user->railway_id;
        $newDocument->organization_id = $user->organization_id;
        $newDocument->to_management_id = $to_user->management_id;
        $newDocument->to_railway_id = $to_user->railway_id;
        $newDocument->to_organization_id = $to_user->organization_id;
        $newDocument->send_user_id = auth()->user()->id;
        $newDocument->rec_user_id = $request->to_user_id;
        $newDocument->document_id = $document_id->id;
        $newDocument->type_document_id = $document_id->type_document_id;
        $newDocument->type_history_id = $request->type_redirect_id;
        $newDocument->to_date = $request->to_date;
        $newDocument->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully redirected'
        ]);
    }

}
