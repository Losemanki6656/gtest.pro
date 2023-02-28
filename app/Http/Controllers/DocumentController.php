<?php

namespace App\Http\Controllers;
use App\Models\Document;
use App\Models\UserOrganization;
use App\Models\User;
use App\Models\TypeDocument;
use App\Models\Organization;
use App\Models\Education;
use App\Models\Nationality;
use App\Models\Region;
use App\Models\City;
use App\Models\AcademicDegree;
use App\Models\AcademicTitle;

use App\Models\Language;
use App\Models\Party;
use App\Models\Worker;
use App\Models\WorkerLanguage;
use App\Models\DriverLicense;
use App\Models\WorkerDriverLicense;

use App\Http\Resources\TypeDocumentResource;
use App\Http\Resources\IncomingDocumentCollection;
use App\Http\Resources\DocumentWorkerGetResource;


use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\NationalityResource;
use App\Http\Resources\EducationResource;
use App\Http\Resources\WorkerResource;

use App\Http\Resources\DocumentResResource;

use App\Http\Resources\SendDocumentOrganizationCollection;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
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


    public function check_document()
    {
        $documents = Document::where('send_user_id', auth()->user()->id);

        if(!$documents->count()) {
            return response()->json([
                'status' => false
            ]);
        } else {
            return response()->json([
                'status' => true,
                'documents' => DocumentResResource::collection( 
                    $documents->get() 
                )
            ]);
        }
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
        $documents = Document::where('send_user_id', auth()->user()->id);

        if(!$documents->count()) {
            $status = false;
            $documents = [];
        } else {
           $status = true;
           $documents = $documents->get();
        }

        $organizations = Organization::query()
            ->when(request('search'), function ( $query, $search) {
                return $query->where('name', 'LIKE', '%'. $search .'%');
                
            })
            ->with(['users'])->paginate(10);

        $type_documents = TypeDocument::get();

        $educations = Education::get();
        $regions = Region::get();
        $nationalities = Nationality::get();

        
        $academic_degrees = AcademicDegree::get();
        $academic_titlies = AcademicTitle::get();
        $languages = Language::get();
        $driver_licenses = DriverLicense::get();
        $parties = Party::get();

        return response()->json([
            'status' => $status,
            'documents' => DocumentResResource::collection($documents),
            'organizations' => new SendDocumentOrganizationCollection($organizations),
            'type_documents' => TypeDocumentResource::collection($type_documents),
            'educations' => EducationResource::collection($educations),
            'regions' => RegionResource::collection($regions),
            'nationalities' => NationalityResource::collection($nationalities),
            'academic_degrees' => $academic_degrees,
            'academic_titlies' => $academic_titlies,
            'languages' => $languages,
            'driver_licenses' => $driver_licenses,
            'parties' => $parties
        ]);

    }

    public function send_document_ID($document_id)
    {
        if($document_id == "0") {
            $status = false;
            $document = null;
            $workers = null;
        } else {
           $status = true;
           $document = new DocumentResResource(Document::find($document_id));
           $workers = DocumentWorkerGetResource::collection( Worker::where('document_id', $document_id)->get() );
        }

        $organizations = Organization::query()
            ->when(request('search'), function ( $query, $search) {
                return $query->where('name', 'LIKE', '%'. $search .'%');
                
            })
            ->with(['users'])->paginate(10);
        $type_documents = TypeDocument::get();
        $educations = Education::get();
        $regions = Region::get();
        $nationalities = Nationality::get();
        $academic_degrees = AcademicDegree::get();
        $academic_titlies = AcademicTitle::get();
        $languages = Language::get();
        $driver_licenses = DriverLicense::get();
        $parties = Party::get();

        return response()->json([
            'status' => $status,
            'document' => $document,
            'workers' => $workers,
            'organizations' => new SendDocumentOrganizationCollection($organizations),
            'type_documents' => TypeDocumentResource::collection($type_documents),
            'educations' => EducationResource::collection($educations),
            'regions' => RegionResource::collection($regions),
            'nationalities' => NationalityResource::collection($nationalities),
            'academic_degrees' => $academic_degrees,
            'academic_titlies' => $academic_titlies,
            'languages' => $languages,
            'driver_licenses' => $driver_licenses,
            'parties' => $parties
            
        ]);

    }

    public function send_document_post( $to_user_id, Request $request)
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
            $newDocument->file_status = true;

            if($request->file1) {
                $fileName1 = time() . $request->file1->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName1, File::get($request->file1));
                $filePath1 = 'storage/files/' . $fileName1;
                $newDocument->file1 = $filePath1;
                $newDocument->to_file1 = $request->file1->getClientOriginalName();
            }

            if($request->file2) {
                $fileName2 = time() . $request->file2->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName2, File::get($request->file2));
                $filePath2 = 'storage/files/' . $fileName2;
                $newDocument->file2 = $filePath2;
                $newDocument->to_file2 = $request->file2->getClientOriginalName();
            }

            if($request->file3) {
                $fileName3 = time() . $request->file3->getClientOriginalName();
                Storage::disk('public')->put('files/' . $fileName3, File::get($request->file3));
                $filePath3 = 'storage/files/' . $fileName3;
                $newDocument->file3 = $filePath3;
                $newDocument->to_file3 = $request->file3->getClientOriginalName();
            }

        } 

        $newDocument->comment = $request->comment;
        $newDocument->type_document_id = $request->type_document_id;
        $newDocument->comment = $request->comment;
        $newDocument->to_date = $request->to_date;
        $newDocument->status = false;
        $newDocument->save();

        return response()->json([
            'message' => 'successfully',
            'document_id' => new DocumentResResource($newDocument)
        ]);
    }

    public function add_worker_to_document_GET( Request $request)
    {
        $educations = Education::get();
        $regions = Region::get();
        $nationalities = Nationality::get();

        
        $academic_degrees = AcademicDegree::get();
        $academic_titlies = AcademicTitle::get();

        return response()->json([
            'educations' => EducationResource::collection($educations),
            'regions' => RegionResource::collection($regions),
            'nationalities' => NationalityResource::collection($nationalities),
            'academic_degrees' => $academic_degrees,
            'academic_titlies' => $academic_titlies
        ]);
    }

    public function add_worker_to_document_POST(Request $request)
    {
        $validated = $request->validate([
            'education_id' => ['required'],
            'region_id' => ['required'],
            'city_id' => ['required'],
            'nationality_id' => ['required'],
            'academic_degree_id' => ['required'],
            'academic_title_id' => ['required'],
            'last_name' => ['required'],
            'first_name' => ['required'],
            'middle_name' => ['required'],
            'department_name' => ['required'],
            'staff_name' => ['required'],
            'birth_date' => ['required','date'],
            'rail_date' => ['required','date'],
            'rail_status' => ['required'],
            'passport' => ['required'],
            'jshshir' => ['required','unique:workers'],
            'address_region_id' => ['required'],
            'address_city_id' => ['required'],
            'languages' => ['required'],
            'photo' => ['required','file'],
            'phone' => ['required'],
            'sex' => ['required','boolean'],
            'driver_licenses' => ['required'],
        ]);

        $worker = Worker::create($request->all());

        return 1;

        if($request->languages)
        foreach (explode(',', $request->languages) as $key => $value)
        {
            $lang = new WorkerLanguage();
            $lang->worker_id = $worker->id;
            $lang->language_id = (int)$value;
            $lang->save();
        }

        if($request->driver_licenses)
        foreach (explode(',', $request->driver_licenses) as $key => $value)
        {
            $license = new WorkerDriverLicense();
            $license->worker_id = $worker->id;
            $license->driver_license_id = (int)$value;
            $license->save();
        }

        return response()->json([
            'message' => 'Successfully',
            'worker_id' => $worker->id
        ]);
    }

    public function update_worker_POST(Worker $worker, Request $request)
    {
        // $validated = $request->validate([
        //     'education_id' => ['required'],
        //     'region_id' => ['required'],
        //     'city_id' => ['required'],
        //     'nationality_id' => ['required'],
        //     'academic_degree_id' => ['required'],
        //     'academic_title_id' => ['required'],
        //     'last_name' => ['required'],
        //     'first_name' => ['required'],
        //     'middle_name' => ['required'],
        //     'department_name' => ['required'],
        //     'staff_name' => ['required'],
        //     'birth_date' => ['required','date'],
        //     'rail_date' => ['required','date'],
        //     'rail_status' => ['required'],
        //     'passport' => ['required'],
        //     'jshshir' => ['required','unique:workers'],
        //     'address_region_id' => ['required'],
        //     'address_city_id' => ['required'],
        //     'languages' => ['required'],
        //     'photo' => ['required','file'],
        //     'phone' => ['required'],
        //     'sex' => ['required','boolean'],
        //     'driver_licenses' => ['required'],
        // ]);

        $worker->update($request->all());

        if($request->languages)
        foreach (explode(',', $request->languages) as $key => $value)
        {
            WorkerLanguage::where('worker_id', $worker->id)->delete();
            $lang = new WorkerLanguage();
            $lang->worker_id = $worker->id;
            $lang->language_id = (int)$value;
            $lang->save();
        }

        if($request->driver_licenses)
        foreach (explode(',', $request->driver_licenses) as $key => $value)
        {
            WorkerDriverLicense::where('worker_id', $worker->id)->delete();
            $license = new WorkerDriverLicense();
            $license->worker_id = $worker->id;   
            $license->driver_license_id = (int)$value;
            $license->save();
        }

        return response()->json([
            'message' => 'Successfully Updated',
            'worker_id' => $worker->id
        ]);
    }

    public function delete_worker(Worker $worker)
    {
        WorkerLanguage::where('worker_id', $worker->id)->delete();
        WorkerDriverLicense::where('worker_id', $worker->id)->delete();

        $worker->delete();

        return response()->json([
            'message' => 'Successfully Deleted'
        ]);
    }


    public function update_worker_GET($worker_id)
    {
        $worker =  Worker::with(['languages','driver_licensies'])->find($worker_id);

        return response()->json([
            'worker' => new WorkerResource($worker)
        ]);
    }

    public function filter_cities(Request $request)
    {
        $cities = City::query()
            ->when(request('region_id'), function ( $query, $region_id) {
                return $query->where('region_id', $region_id);
                
            })->get();

        return response()->json([
            'cities' => CityResource::collection($cities)
        ]);
    }

    public function admin_migrate()
    {     
        Schema::disableForeignKeyConstraints();
        Artisan::call('migrate');
        Schema::enableForeignKeyConstraints();

        return 1;
    }
}


