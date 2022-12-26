<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\UserOrganization;

use Illuminate\Http\Request;
use Auth;

class DocumentController extends Controller
{
    public function documents()
    {
        $documents = Document::get();

        return response()->json($documents);
    }

    public function send_document(Request $request, $to_user_id)
    {
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
        $newDocument->type_document_id = $request->type_document_id;
        $newDocument->to_date = $request->to_date;
        $newDocument->status = false;
        $newDocument->save();

        return response()->json([
            'message' => 'successfully'
        ]);
    }
}
