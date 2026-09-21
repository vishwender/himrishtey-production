<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\AnnualIncome;
use App\Website\Models\Cast;
use App\Website\Models\City;
use App\Website\Models\Countrie;
use App\Website\Models\DeleteProfile;
use App\Website\Models\Education;
use App\Website\Models\Employer;
use App\Website\Models\FamilyStatus;
use App\Website\Models\Height;
use App\Website\Models\MaritalStatus;
use App\Website\Models\Member;
use App\Website\Models\MembershipPlan;
use App\Website\Models\MotherTongue;
use App\Website\Models\Occupation;
use App\Website\Models\ProfileCreatedFor;
use App\Website\Models\ProfileLike;
use App\Website\Models\ProfileViewed;
use App\Website\Models\Religion;
use App\Website\Models\SentInterest;
use App\Website\Models\Shortlist;
use App\Website\Models\State;
use App\Website\Models\User;
use App\Website\Services\ProfilePhotoUrl;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MyMemberController extends Controller
{
    public function search_home_member(Request $request)
    {
        $data['member'] = Auth::guard('member')->user();
        $id = $data['member']->id;
        $data['plan'] = MembershipPlan::where('id', $data['member']->plan_id)->first();
        $data['shortlisted'] = Shortlist::where('member_id', $id)->count();
        $data['viewed'] = ProfileViewed::where('viewed_profile_id', $id)->count();
        $data['iviewed'] = ProfileViewed::where('member_id', $id)->count();
        $data['interestSent'] = SentInterest::where('member_id', $id)->count();
        $data['interestReceived'] = SentInterest::where('profile_id', $id)->count();

        $lookingFor = $request->input('marital_status');
        $partnerAgeFrom = $request->input('partner_age_from');
        $partnerAgeTo = $request->input('partner_age_to');
        $partnerGender = $request->input('partner_gender');
        $today = Carbon::now();
        $members = Member::where('gender', $partnerGender)
            ->where('marital_status', $lookingFor)
            ->where('id', '!=', $id)
            ->where('profile_completed', '>', 70)
            ->where('active', 'yes')
            ->get()
            ->filter(function ($member) use ($partnerAgeFrom, $partnerAgeTo) {

                // Check if birth_date_time is valid
                if (empty($member->birth_date_time) || $member->birth_date_time === false) {
                    return false; // skip invalid dates
                }

                try {
                    $age = Carbon::parse($member->birth_date_time)->age;
                } catch (\Exception $e) {
                    return false; // skip if parsing fails
                }

                return $age >= $partnerAgeFrom && $age <= $partnerAgeTo;
            });
        $data['members'] = $members->map(function ($member) {
            return [
                'id' => $member->id,
                'birthdatetime' => Carbon::parse($member->birth_date_time)->format('d-m-Y h:i A'),
                'gender' => $member->gender,
                'age' => Carbon::parse($member->birth_date_time)->age,
                'height' => $member->height,
                'religion' => $member->religion,
                'mother_tongue' => $member->mother_tongue,
                'cast' => $member->cast,
                'education' => $member->education,
                'occupation' => $member->occupation,
                'location' => $member->city_living_in.'<br>'.$member->state_living_in.' '.$member->country_living_in,
                'photo' => $member->photo,
                'profile_completed' => $member->profile_completed,
                'profile_id' => $member->profile_id,
                'mother_tongue' => $member->mother_tongue,
            ];
        });

        return response()->json($data);
        // return view('dashboard.search_member', compact('data'));
    }

    public function search_home_profile(Request $request)
    {
        $member = Auth::guard('member')->user();

        $keyword = trim($request->keyword);

        if (strlen($keyword) < 2) {
            return response()->json([]);
        }

        $profiles = Member::where(function ($query) use ($keyword) {

            $query->where('full_name', 'LIKE', "%{$keyword}%")
                ->orWhere('profile_id', 'LIKE', "%{$keyword}%");
        })
            ->where('id', '!=', $member->id)
            ->where('gender', '!=', $member->gender)
            ->where('active', 'Yes')
            ->limit(20)
            ->get();

        $result = [];

        // dd($profiles);

        foreach ($profiles as $profile) {

            if (! empty($profile->photo) && $profile->photo_approved == 'Yes') {

                $photo = ProfilePhotoUrl::get($profile->photo);
            } else {

                $photo = $profile->gender == 'Male'
                    ? asset('images/profile_photos/boy.jpg')
                    : asset('images/profile_photos/girl.jpg');
            }

            $result[] = [
                'id' => $profile->id,
                'profile_id' => $profile->profile_id,
                'full_name' => $profile->full_name,
                'gender' => $profile->gender,
                'age' => Carbon::parse($profile->birth_date_time)->age,
                'city' => $profile->city_living_in,
                'state' => $profile->state_living_in,
                'photo' => $photo,
                'verified' => $profile->member_type == 'Verified',
            ];
        }

        return response()->json($result);
    }

    public function myProfile()
    {
        $today = Carbon::now();
        $user = Auth::guard('member')->user();
        $id = $user->plan_id;
        $rm = $user->relationship_manager;
        $plan = MembershipPlan::where('id', $id)->first();
        $rm = User::where('username', $rm)->first();
        $data['profile_created_for'] = ProfileCreatedFor::all();
        $data['heights'] = Height::all();
        $data['religions'] = Religion::all();
        $data['casts'] = Cast::all();
        $data['marital_statuses'] = MaritalStatus::all();
        $data['countries'] = Countrie::all();
        $country_id = Countrie::where('name', $user->country_living_in)->first();
        $state_id = State::where('name', $user->state_living_in)->first();
        // $data['states'] = State::where('country_id',$country_id->id)->get();
        //  $data['cities'] = City::where('state_id',$state_id->id)->get();
        $data['educations'] = Education::all();
        $data['familyStatuses'] = FamilyStatus::all();
        $data['employers'] = Employer::all();
        $data['annualIncomes'] = AnnualIncome::all();
        $data['motherTongues'] = MotherTongue::all();

        $profile_created_for = $user->profile_created_for;
        if ($profile_created_for == 'Self') {
            $data['profile_created_by'] = 'Self';
        } elseif ($profile_created_for == 'Friend') {
            $data['profile_created_by'] = 'Friend';
        } elseif ($profile_created_for == 'Relative') {
            $data['profile_created_by'] = 'Relative';
        } elseif ($profile_created_for == 'Son' || $profile_created_for == 'Daughter') {
            $data['profile_created_by'] = 'Parents';
        } elseif ($profile_created_for == 'Brother' || $profile_created_for == 'Sister') {
            $data['profile_created_by'] = 'Siblings';
        } else {
            $data['profile_created_by'] = 'Client';
        }

        $birthDate = Carbon::parse($user->birth_date_time);
        $diff = $birthDate->diff($today);
        $user['age_years'] = $diff->y;
        $user['age_months'] = $diff->m;

        $profileStats = [
            'profile_views' => ProfileViewed::where('viewed_profile_id', $user->id)->count(),
            'likes' => ProfileLike::where('like_profile_id', $user->id)->count(),
            'interests' => SentInterest::where('profile_id', $user->id)->count(),
            'contacts_viewed' => ProfileViewed::where('member_id', $user->id)->count(),
        ];

        $recentProfiles = Member::where('gender', '!=', $user->gender)
            ->where('id', '!=', $user->id)
            ->where('profile_hide', '!=', 'yes')
            ->where('active', 'Yes')
            ->orderBy('activation_number', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($member) use ($today) {
                $birthDate = Carbon::parse($member->birth_date_time);
                $diff = $birthDate->diff($today);
                $member->age_years = $diff->y;
                $member->age_months = $diff->m;
                $member->photo = ! empty($member->photo) && $member->photo_approved === 'Yes'
                    ? ProfilePhotoUrl::get($member->photo)
                    : ($member->gender === 'Male'
                        ? '/images/profile_photos/boy.jpg'
                        : '/images/profile_photos/girl.jpg');
                $member->mem_type = $member->member_type === 'Verified' ? 'Yes' : 'No';

                return $member;
            });

        $verifiedProfiles = Member::where('gender', '!=', $user->gender)
            ->where('id', '!=', $user->id)
            ->where('profile_hide', '!=', 'yes')
            ->where('member_type', 'Verified')
            ->where('active', 'Yes')
            ->orderBy('activation_number', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($member) use ($today) {
                $birthDate = Carbon::parse($member->birth_date_time);
                $diff = $birthDate->diff($today);
                $member->age_years = $diff->y;
                $member->age_months = $diff->m;
                $member->photo = ! empty($member->photo) && $member->photo_approved === 'Yes'
                    ? ProfilePhotoUrl::get($member->photo)
                    : ($member->gender === 'Male'
                        ? '/images/profile_photos/boy.jpg'
                        : '/images/profile_photos/girl.jpg');
                $member->mem_type = $member->member_type === 'Verified' ? 'Yes' : 'No';

                return $member;
            });

        $viewedMyProfile = Member::select('members.*', 'profile_viewed.created_at as viewed_at')
            ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
            ->where('profile_viewed.viewed_profile_id', $user->id)
            ->where('members.active', 'Yes')
            ->where('members.profile_hide', '!=', 'yes')
            ->orderBy('profile_viewed.id', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($member) use ($today) {
                $birthDate = Carbon::parse($member->birth_date_time);
                $diff = $birthDate->diff($today);
                $member->age_years = $diff->y;
                $member->age_months = $diff->m;
                $member->photo = ! empty($member->photo) && $member->photo_approved === 'Yes'
                    ? ProfilePhotoUrl::get($member->photo)
                    : ($member->gender === 'Male'
                        ? '/images/profile_photos/boy.jpg'
                        : '/images/profile_photos/girl.jpg');
                $member->mem_type = $member->member_type === 'Verified' ? 'Yes' : 'No';

                return $member;
            });

        return view('dashboard.profile', compact(['user', 'plan', 'rm', 'data', 'recentProfiles', 'verifiedProfiles', 'viewedMyProfile', 'profileStats']));
    }

    public function advance_search(Request $request)
    {
        $member = Auth::guard('member')->user();
        $id = $member->id;
        $data['religions'] = Religion::all();
        $data['casts'] = Cast::all();
        $data['mstatus'] = MaritalStatus::all();
        $data['states'] = State::where('country_id', 1)->get();
        $data['incomes'] = AnnualIncome::all();
        $data['mother_tongues'] = MotherTongue::all();
        $data['educations'] = Education::all();
        $data['employers'] = Employer::all();
        $data['heights'] = Height::all();

        $memberGender = $member->gender;
        $today = Carbon::now();
        $partnerAgeFrom = $request->input('partner_age_from');
        $partnerAgeTo = $request->input('partner_age_to');
        $partnerCasts = $request->input('partner_cast', []);
        $partnerReligions = $request->input('partner_religion', []);
        $maritalStatus = $request->input('marital_status');
        if (! is_array($partnerReligions)) {
            $partnerReligions = [$partnerReligions];
        }
        if (! is_array($partnerCasts)) {
            $partnerCasts = [$partnerCasts];
        }
        $data['searchedMembers'] = [];
        $partnerAgeFrom = $partnerAgeTo = $partnerCasts = $partnerReligions = $maritalStatus = $partnerStates = $partnerEmployers = $partnerIncomeFrom = $partnerIncomeTo = null;
        $profile_id = $partnerManglik = $partnerMotherTongue = $partnerHeightFrom = $partnerHeightTo = null;
        if ($request->query()) {
            $partnerAgeFrom = $request->input('partner_age_from');
            $partnerAgeTo = $request->input('partner_age_to');
            $partnerCasts = (array) $request->input('partner_cast', []);
            $partnerReligions = (array) $request->input('partner_religion', []);
            $maritalStatus = $request->input('marital_status');
            $profile_id = $request->input('profile_id');
            $partnerManglik = $request->input('partner_manglik');
            $partnerMotherTongue = (array) $request->input('partner_mother_tongue', []);
            $partnerStates = (array) $request->input('partner_state', []);
            $partnerEmployers = (array) $request->input('partner_employers', []);
            $partnerIncomeFrom = $request->input('partner_annual_income_from');
            $partnerIncomeTo = $request->input('partner_annual_income_to');
            $partnerHeightFrom = $request->input('partner_height_from');
            $partnerHeightTo = $request->input('partner_height_to');
            $partnerEducation = (array) $request->input('partner_education', []);

            $data['members'] = Member::where('gender', '!=', $memberGender)
                ->where('id', '!=', $id)
                ->where('active', 'yes')
                ->when(! empty($maritalStatus), function ($q) use ($maritalStatus) {
                    $q->where('marital_status', $maritalStatus);
                })
                ->when(! empty($partnerReligions), function ($q) use ($partnerReligions) {
                    $q->whereIn('religion', $partnerReligions);
                })
                ->when(! empty($partnerCasts), function ($q) use ($partnerCasts) {
                    $q->whereIn('cast', $partnerCasts);
                })
                ->when(! empty($partnerStates), function ($q) use ($partnerStates) {
                    $q->whereIn('state_living_in', $partnerStates);
                })
                ->when(! empty($partnerEmployers), function ($q) use ($partnerEmployers) {
                    $q->whereIn('employer', $partnerEmployers);
                })
                ->when(! empty($partnerIncomeFrom), function ($q) use ($partnerIncomeFrom) {
                    $q->where('annual_income', '>=', $partnerIncomeFrom);
                })
                ->when(! empty($partnerIncomeTo), function ($q) use ($partnerIncomeTo) {
                    $q->where('annual_income', '<=', $partnerIncomeTo);
                })
                ->when(! empty($partnerHeightFrom), function ($q) use ($partnerHeightFrom) {
                    $q->where('height', '>=', $partnerHeightFrom);
                })
                ->when(! empty($partnerHeightTo), function ($q) use ($partnerHeightTo) {
                    $q->where('height', '<=', $partnerHeightTo);
                })
                ->when(! empty($partnerEducation), function ($q) use ($partnerEducation) {
                    $q->whereIn('education', $partnerEducation);
                })
                ->when(! empty($partnerManglik), function ($q) use ($partnerManglik) {
                    $q->where('manglik', $partnerManglik);
                })
                ->when(! empty($partnerMotherTongue), function ($q) use ($partnerMotherTongue) {
                    $q->whereIn('mother_tongue', $partnerMotherTongue);
                })
                ->when(! empty($profile_id), function ($q) use ($profile_id) {
                    $q->where('profile_id', $profile_id);
                })
                ->get()
                ->filter(function ($m) use ($partnerAgeFrom, $partnerAgeTo) {
                    if ($partnerAgeFrom && $partnerAgeTo) {
                        return $m->age >= $partnerAgeFrom && $m->age <= $partnerAgeTo;
                    } elseif ($partnerAgeFrom) {
                        return $m->age >= $partnerAgeFrom;
                    } elseif ($partnerAgeTo) {
                        return $m->age <= $partnerAgeTo;
                    }

                    return true; // no age filter applied
                });

            foreach ($data['members'] as $key => $recent) {
                $birthDate = Carbon::parse($recent->birth_date_time);
                $diff = $birthDate->diff($today);

                $data['searchedMembers'][$key]['age_years'] = $diff->y;
                $data['searchedMembers'][$key]['age_months'] = $diff->m;

                if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                    $data['searchedMembers'][$key]['photo'] = ProfilePhotoUrl::get($recent->photo);
                } elseif ($recent->gender === 'Male') {
                    $data['searchedMembers'][$key]['photo'] = '/images/profile_photos/boy.jpg';
                } elseif ($recent->gender === 'Female') {
                    $data['searchedMembers'][$key]['photo'] = '/images/profile_photos/girl.jpg';
                }

                if ($recent->member_type === 'Verified') {
                    $data['searchedMembers'][$key]['member_type'] = 'https://himrishtey.com/img/verified.png';
                    $data['searchedMembers'][$key]['mem_type'] = 'Yes';
                } else {
                    $data['searchedMembers'][$key]['member_type'] = 'normal';
                }

                if ($recent->is_trusted === 'Trusted') {
                    $data['searchedMembers'][$key]['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
                } else {
                    $data['searchedMembers'][$key]['is_trusted'] = 'No';
                }

                $data['searchedMembers'][$key] = array_merge($recent->toArray(), $data['searchedMembers'][$key]);
            }
        }
        if ($request->ajax()) {
            return view('dashboard.search.search-results', [
                'searchedMembers' => $data['searchedMembers'] ?? [],
            ])->render();
        }

        return view('dashboard.search.advanced-search', compact('data', 'partnerAgeFrom', 'partnerAgeTo', 'partnerReligions', 'partnerCasts', 'maritalStatus'));
    }

    public function edit_profile()
    {

        $member = Auth::guard('member')->user();
        $educations = Education::orderBy('education')->get();
        $occupations = Occupation::where('status', '1')->orderBy('occupation')->get();
        $employedIn = Employer::orderBy('employer')->get();
        $annualIncome = AnnualIncome::orderBy('annual_income')->get();
        $familyStatus = FamilyStatus::orderBy('value')->get();
        $maritalStatus = MaritalStatus::orderBy('marital_status')->get();
        $religions = Religion::orderBy('religion')->get();
        $motherTongues = MotherTongue::orderBy('mother_tongue')->get();
        $casts = Cast::orderBy('cast')->get();
        // dd($employedIn);
        $sections = $member->profileCompletionSections();
        $completion = $member->profileCompletionPercentage();
        $sectionCompletion = collect($sections)
            ->mapWithKeys(fn (array $section, string $key) => [$key => $section['completed']])
            ->all();

        // dd($completion);
        return view('dashboard.profile.edit', compact('member', 'completion', 'sectionCompletion', 'familyStatus', 'annualIncome', 'employedIn', 'educations', 'occupations', 'maritalStatus', 'religions', 'motherTongues', 'casts'));
    }

    public function update_profile(Request $request)
    {
        $member = Auth::guard('member')->user();
        $user = Member::findOrFail($member->id);

        /*
    |--------------------------------------------------------------------------
    | Simple fields (string/int)
    |--------------------------------------------------------------------------
    */

        $fields = [
            'about_me',
            'profile_created_for',
            'height',
            'cast',
            'religion',
            'marital_status',
            'no_of_child',
            'city_living_in',
            'state_living_in',
            'country_living_in',
            'birth_place',
            'manglik',
            'alternate_number',
            'whatsapp_number',
            'sub_cast',
            'gotra',
            'diet',
            'is_smoking',
            'is_drinking',
            'any_disability',
            'about_family' => 'about_my_family',
            'family_status',
            'native_place',
            'no_of_brothers',
            'no_of_sisters',
            'married_brothers',
            'married_sisters',
            'father_name',
            'father_occupation',
            'mother_name',
            'mother_occupation',
            'about_my_education',
            'education',
            'any_other_qualifications',
            'employed_in',
            'organization_name',
            'job_location',
            'occupation',
            'annual_income',
            'is_partner_smoking',
            'is_partner_drinking',
            'partner_diet',
            'is_partner_manglik',
            'about_my_partner',
            'partner_height_from',
            'partner_height_to',
            'partner_age_from',
            'partner_age_to',
            'partner_annual_income_from',
            'partner_annual_income_to',
        ];

        foreach ($fields as $dbField => $requestField) {

            if (is_numeric($dbField)) {
                $dbField = $requestField;
            }

            if ($request->filled($requestField)) {
                $user->$dbField = $request->$requestField;
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Birth Date + Time
    |--------------------------------------------------------------------------
    */

        if ($request->filled('date_of_birth') || $request->filled('time_of_birth')) {

            $user->birth_date_time = trim(
                $request->date_of_birth.' '.$request->time_of_birth
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Multi Select Fields
    |--------------------------------------------------------------------------
    */

        $multiFields = [
            'partner_occupation',
            'partner_education',
            'partner_cast',
            'partner_religion',
            'looking_for',
            'partner_mothertongue',
        ];

        foreach ($multiFields as $requestField => $dbField) {

            if (is_numeric($requestField)) {
                $requestField = $dbField;
            }

            if ($request->filled($requestField)) {

                $value = $request->$requestField;

                $user->$dbField = is_array($value)
                    ? implode(',', $value)
                    : $value;
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully',
        ]);
    }

    public function delete_profile()
    {

        return view('dashboard.profile.delete');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        DeleteProfile::create([
            'user_id' => Auth::guard('member')->id(),
            'reason' => $request->reason,
            'date' => Carbon::now()->format('Y-m-d'),
            'status' => 0, // 0 = Pending
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Your profile deletion request has been sent to the administrator.',
        ]);
    }

    public function verify_account()
    {
        $member = Auth::guard('member')->user();
        $user = Member::findOrFail($member->id);
        $member_mobile = $user->mobile_number;

        if ($user->member_type === 'Verified') {
            return redirect('/home');
        }

        Session::put('verify_phone', $member_mobile);

        return view('dashboard.verify_account.verify-account', compact('member_mobile'));
    }
}
