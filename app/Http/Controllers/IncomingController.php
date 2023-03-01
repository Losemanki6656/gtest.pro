<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use App\Models\Worker;

use App\Http\Resources\OutgoingCollection;
use App\Http\Resources\WorkerCollection;

class IncomingController extends Controller
{
    public function incoming_messages()
    {
        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $documents = Document::where('status_send', true)
            ->where('rec_user_id', auth()->user()->id)
            ->with(['rec_user','type_document'])
            ->paginate($per_page);

        return response()->json([
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

}
