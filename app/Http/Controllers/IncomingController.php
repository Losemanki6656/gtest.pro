<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;

use App\Http\Resources\OutgoingCollection;

class IncomingController extends Controller
{

    public function incoming_messages()
    {
        if(request('per_page')) $per_page = request('per_page'); else $per_page = 10;

        $documents = Document::
        // where('status_send', true)
            where('rec_user_id', auth()->user()->id)
            ->with(['rec_user','type_document'])
            ->paginate($per_page);

        return response()->json([
            'documents' => new OutgoingCollection($documents)
        ]);
    }

}
