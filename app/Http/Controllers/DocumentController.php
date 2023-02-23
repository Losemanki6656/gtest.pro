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
use App\Models\WorkerLanguage;
use App\Models\DriverLicense;
use App\Models\WorkerDriverLicense;

use App\Http\Resources\TypeDocumentResource;
use App\Http\Resources\IncomingDocumentCollection;


use App\Http\Resources\CityResource;
use App\Http\Resources\RegionResource;
use App\Http\Resources\NationalityResource;
use App\Http\Resources\EducationResource;

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
                'documents' => $documents->get()
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
            'languages' => $languages
            
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

    public function add_worker_to_document_POST($document_id, Request $request)
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
            'jshshir' => ['required'],
            'address_region_id' => ['required'],
            'address_city_id' => ['required'],
            'languages' => ['required','array'],
            'photo' => ['required','file'],
            'phone' => ['required'],
            'sex' => ['required','boolean'],
            'driver_licenses' => ['required'],
        ]);

        $worker = new Worker();
        $worker->document_id = $document_id;
        $worker->education_id = $request->education_id;
        $worker->region_id = $request->region_id;
        $worker->city_id = $request->city_id;
        $worker->address_region_id = $request->address_region_id;
        $worker->address_city_id = $request->address_city_id;
        $worker->nationality_id = $request->nationality_id;
        $worker->academic_degree_id = $request->academic_degree_id;
        $worker->academic_title_id = $request->academic_title_id;
        $worker->party_id = $request->party_id;
        $worker->last_name = $request->last_name;
        $worker->first_name = $request->first_name;
        $worker->middle_name = $request->middle_name;
        
        if($request->photo) {
            $photo = time() . $request->photo->getClientOriginalName();
            Storage::disk('public')->put('worker-photos/' . $photo, File::get($request->photo));
            $path = 'storage/files/' . $photo;
            $worker->photo = $path;
        }

        $worker->phone = $request->phone;
        $worker->address = $request->address;
        $worker->sex = $request->sex;
        $worker->institut = $request->institut;
        $worker->speciality = $request->speciality;
        $worker->incent = $request->incent;
        $worker->department_name = $request->department_name;
        $worker->staff_name = $request->staff_name;
        $worker->birth_date = $request->birth_date;
        $worker->rail_date = $request->rail_date;
        $worker->rail_status = $request->rail_status;
        $worker->old_job_name = $request->old_job_name;
        $worker->del_rail_comment = $request->del_rail_comment;
        $worker->passport = $request->passport;
        $worker->jshshir = $request->jshshir;
        $worker->deputy = $request->deputy;
        $worker->military = $request->military;
        $worker->military_rank = $request->military_rank;

        if($request->file1) {

            $file1 = time() . $request->file1->getClientOriginalName();
            Storage::disk('public')->put('worker-photos/' . $file1, File::get($request->file1));
            $path_file1 = 'storage/files/' . $file1;
            $worker->file1 = $path_file1;
        }

        $worker->other_doc = $request->comment;
        $worker->save();

        return 1;

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

