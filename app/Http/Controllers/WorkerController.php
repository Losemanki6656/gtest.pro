<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Career;
use App\Models\Relative;
use App\Models\WorkerRelative;


use App\Http\Resources\CareerResource;
use App\Http\Resources\WorkerRelativeResource;
use App\Http\Resources\RelativeResource;

class WorkerController extends Controller
{
    

    public function careers($worker)
    {
        $careers = Career::where('worker_id', $worker)->get();

        return response()->json([
            'careers' => CareerResource::collection($careers)
        ]);
    }

    public function create_careers(Request $request)
    {
        Career::create($request->all());

        return response()->json([
            'message' => "Successfully created"
        ]);
    }

    public function update_careers(Career $career, Request $request)
    {
        $career->update($request->all());

        return response()->json([
            'message' => "Successfully updated"
        ]);
    }

    public function delete_careers(Career $career)
    {
        $career->delete();

        return response()->json([
            'message' => "Successfully deleted"
        ]);
    }


    public function relatives($worker)
    {
        $worker_relatives = WorkerRelative::where('worker_id', $worker)->get();
        $relatives = Relative::get();

        return response()->json([
            'relatives' => RelativeResource::collection($relatives),
            'worker_relatives' => WorkerRelativeResource::collection($worker_relatives)
        ]);
    }

    public function create_relative(Request $request)
    {
        WorkerRelative::create($request->all());

        return response()->json([
            'message' => "Successfully created"
        ]);
    }

    public function update_relative(WorkerRelative $worker_relative, Request $request)
    {
        $worker_relative->update($request->all());

        return response()->json([
            'message' => "Successfully updated"
        ]);
    }

    public function delete_relative(WorkerRelative $worker_relative)
    {
        $worker_relative->delete();

        return response()->json([
            'message' => "Successfully deleted"
        ]);
    }
    
}
