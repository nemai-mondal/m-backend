<?php

namespace App\Http\Resources;

use App\Models\Department;
use App\Models\Designation;
use App\Models\EmpAdhaar;
use App\Models\EmpDocument;
use App\Models\EmpDrivingLicense;
use App\Models\EmpFamily;
use App\Models\EmpJoining;
use App\Models\EmpOrganization;
use App\Models\EmpPan;
use App\Models\EmpPassport;
use App\Models\EmpProfessionalDetail;
use App\Models\EmpSeparation;
use App\Models\EmpVoterCard;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                    =>  $this->id ?? "",
            // 'pan'                   =>  $this->pan,
            'pan'                   =>  $this->getPan(), //Added document
            'email'                 =>  $this->email ?? "",
            'shift'                 =>  $this->shift,
            'roles'                 =>  $this->roles ?? [],
            'image'                 =>  $this->getMedia("profile-picture")->first()->original_url ?? "",
            'adhaar'                =>  $this->getAdhaar(), //Added document
            'skills'                =>  $this->skill,
            'assets'                =>  $this->asset,
            'status'                =>  $this->status ?? "",
            'joining'               =>  $this->getJoiningDetails($this->id),
            'project'               =>  $this->projects,
            // 'passport'              =>  $this->passport,
            'passport'              =>  $this->getPassport(), //Added document
            'honorific'             =>  $this->honorific ?? "",
            'languages'             =>  $this->language,
            'addresses'             =>  $this->address,
            'last_name'             =>  $this->last_name ?? "",
            'documents'             =>  $this->getFamilyDocuments($this->id),
            'attendance'            =>  $this->attendance ?? [],
            // 'separation'            =>  $this->separation,
            'separation'            =>  $this->getSeparation(), //Added document
            'employeedocument'            =>  $this->getEmployeeDocument(), //Added document
            'department'            =>  $this->getDepartment(),
            // 'voter_card'            =>  $this->voterCard,
            'voter_card'            =>  $this->getVoter(), //Added document
            'first_name'            =>  $this->first_name ?? "",
            'middle_name'           =>  $this->middle_name ?? "",
            'employee_id'           =>  $this->employee_id ?? "",
            'designation'           =>  $this->getDesignation(),
            'permissions'           =>  $this->getAllPermissions() ?? [],
            'organizations'         =>  $this->organizations,
            'shift_started'         =>  $this->getLoginStatus($this->id),
            'qualifications'        =>  $this->qualification,
            // 'family_details'        =>  $this->family,
            'family_details'            =>  $this->getFamilyDetails(), //Added document
            'employment_type'       =>  $this->employmentType,
            // 'driving_license'       =>  $this->drivingLicense,
            'driving_license'       =>  $this->getDrivinigLicense(), //Added document
            'personal_details'      =>  $this->employeePersonalDetail,
            'password_updated'      =>  $this->password_updated ?? false,
            'onboard_confirmed'     =>  $this->onboard_confirmed ?? false,
            'reporting_manager'     =>  $this->getReportingManager(),
            'professional_details'  =>  $this->employeeProfessionalDetail ?? [],
            'created_at'            =>  $this->created_at ?? "",
            'updated_at'            =>  $this->updated_at ?? "",
        ];
    }

    public function getAdhaar()
    {

        $adhaar = EmpAdhaar::where('user_id', $this->id)->first();

        if (isset($adhaar) && $adhaar != null) {
            // $adhaar['file'] =  $adhaar->getMedia("identity-adhaar")->first()->original_url;


            $media = $adhaar->getMedia("identity-adhaar")->first();

            if ($media) {
                $adhaar['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $adhaar['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-adhaar'");
            }
        }


        return $adhaar ?? [];
    }

    public function getPan()
    {

        $pan = EmpPan::where('user_id', $this->id)->first();

        if (isset($pan) && $pan != null) {
            // $pan['file'] =  $pan->getMedia("identity-pan")->first()->original_url;


            $media = $pan->getMedia("identity-pan")->first();

            if ($media) {
                $pan['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $pan['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-pan'");
            }
        }

        return $pan ?? [];
    }

    public function getVoter()
    {

        $voter_card = EmpVoterCard::where('user_id', $this->id)->first();

        if (isset($voter_card) && $voter_card != null) {
            // $voter_card['file'] =  $voter_card->getMedia("identity-voter-card")->first()->original_url;

            $media = $voter_card->getMedia("identity-voter-card")->first();

            if ($media) {
                $voter_card['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $voter_card['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-pan'");
            }

        }

        return $voter_card ?? [];
    }

    public function getDrivinigLicense()
    {

        $driving_license = EmpDrivingLicense::where('user_id', $this->id)->first();

        if (isset($driving_license) && $driving_license != null) {
            // $driving_license['file'] =  $driving_license->getMedia("identity-driving-license")->first()->original_url;


            $media = $driving_license->getMedia("identity-driving-license")->first();

            if ($media) {
                $driving_license['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $driving_license['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-pan'");
            }
        
        
        }

        return $driving_license ?? [];
    }

    public function getPassport()
    {

        $passport = EmpPassport::where('user_id', $this->id)->first();

        if (isset($passport) && $passport != null) {
            // $passport['file'] =  $passport->getMedia("identity-passport")->first()->original_url;


            $media = $passport->getMedia("identity-passport")->first();

            if ($media) {
                $passport['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $passport['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-pan'");
            }
        
        }

        return $passport ?? [];
    }

    public function getSeparation()
    {

        $separation = EmpSeparation::where('user_id', $this->id)->first();

        if (isset($separation) && $separation != null) {
            // $separation['file'] =  $separation->getMedia("employee-separation")->first()->original_url;

            $media = $separation->getMedia("identity-separation")->first();

            if ($media) {
                $separation['file'] = $media->original_url;
            } else {
                // Handle the case where no media is found
                // For example:
                $separation['file'] = null;
                // or throw an exception
                // throw new \Exception("No media found with tag 'identity-pan'");
            }
        }

        return $separation ?? [];
    }


    public function getEmployeeDocument()
    {
        $employee_documents = EmpDocument::where('active_user_id', $this->id)->get();

        $documents = [];

        foreach ($employee_documents as $document) {
            $media = $document->getMedia("employee-family-document");

            $mediaUrls = [];
            foreach ($media as $item) {
                $mediaUrls[] = $item->getUrl() ?? null;
            }

            $documentDetails = [
                'document' => $document,
                'files' => $mediaUrls,
            ];

            $documents[] = $documentDetails;
        }

        return $documents;
    }


    public function getFamilyDetails()
    {

        $families = EmpFamily::where('user_id', $this->id)->get();

        $i = 0;
        foreach($families as $family) {

            $media = $family->getMedia("family-details")->first();

            if ($media) {
                $families[$i]['file'] = $media->original_url;
            } else {
                $families[$i]['file'] = null;
            }

            $i++;
        }

        return $families ?? [];

        // if (isset($family) && $family != null) {
        //     // $family['file'] =  $family->getMedia("family-details")->first()->original_url;



        //     $media = $family->getMedia("family-details")->first();

        //     if ($media) {
        //         $family['file'] = $media->original_url;
        //     } else {
        //         // Handle the case where no media is found
        //         // For example:
        //         $family['file'] = null;
        //         // or throw an exception
        //         // throw new \Exception("No media found with tag 'identity-pan'");
        //     }
        
        // }

        // return $family ?? [];
    }

    public function getFamilyDocuments($id)
    {
        $documents = [];

        $families = EmpFamily::where('user_id', $id)->get();

        if ($families->isNotEmpty()) {
            foreach ($families as $family) {
                $document = EmpDocument::where('employee_family_id', $family->id)->first();

                if ($document !== null) {


                    $media = $document->getMedia("family-details")->first();

                    if ($media) {
                        $document_url = $media->original_url;
                    } else {
                        // Handle the case where no media is found
                        // For example:
                        $document_url = null;
                        // or throw an exception
                        // throw new \Exception("No media found with tag 'identity-pan'");
                    }




                    $documents[] = [
                        'family_name' => $family->name,
                        'document' => $document,
                        'file' => $document_url
                        // 'file' => $document->getMedia("family-details")->first()->original_url ?? ""
                    ];
                }
            }
        }

        return $documents;
    }

    public function getLoginStatus($id)
    {

        $timeLog = TimeLog::where('user_id', $id)
            ->whereIn('activity', ['shift start', 'shift_start'])
            ->where('date', date('Y-m-d'))
            ->first();

        if (isset($timeLog) && $timeLog != null) {

            return true;
        }

        return false;
    }

    public function getJoiningDetails($id)
    {
        $joining = EmpJoining::where('user_id', $id)->get();
        return $joining ?? [];
    }

    public function getReportingManager()
    {

        $employee_professional_details = EmpProfessionalDetail::where('user_id', $this->id)->first();
        if ($employee_professional_details == null) {
            return [];
        }
        $user = User::where('id', $employee_professional_details['reporting_manager_id'])->first();

        return $user ?? [];
    }

    public function getDepartment()
    {

        $organization   = EmpOrganization::where('user_id', $this->id)->orderBy('id', 'desc')->first();
        if (!isset($organization) || $organization == null) {
            return null;
        }

        $department     = Department::find($organization['department_id']);
        if (!isset($department) || $department == null) {
            return null;
        }

        return $department;
    }

    public function getDesignation()
    {

        $organization   = EmpOrganization::where('user_id', $this->id)->orderBy('id', 'desc')->first();
        if (!isset($organization) || $organization == null) {
            return null;
        }

        $designation    = Designation::find($organization['designation_id']);
        if (!isset($designation) || $designation == null) {
            return null;
        }

        return $designation;
    }
}
