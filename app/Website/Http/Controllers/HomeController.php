<?php

namespace App\Website\Http\Controllers;

use App\Services\SuccessStoryPhoto;
use App\Website\Models\Cast;
use App\Website\Models\Height;
use App\Website\Models\MaritalStatus;
use App\Website\Models\Member;
use App\Website\Models\MembershipPlan;
use App\Website\Models\MemberWallet;
use App\Website\Models\ProfileLike;
use App\Website\Models\ProfileViewed;
use App\Website\Models\Religion;
use App\Website\Models\SentInterest;
use App\Website\Models\Shortlist;
use App\Website\Models\SuccessStory;
use App\Website\Services\EmailService;
use App\Website\Services\NimbusSmsService;
use App\Website\Services\ProfilePhotoStorage;
use App\Website\Services\ProfilePhotoUrl;
use App\Website\Services\ProfileUnlockPricing;
use App\Website\Services\PushNotificationService;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    // public function index()
    // {
    //     $member = Auth::guard('member')->user();
    //     $id = $member->id;

    //     //complete your profile section
    //     $steps = [
    //         [
    //             'title' => 'Basic Info',
    //             'completed' => !empty($member->about_me)
    //                 && !empty($member->profile_created_for)
    //                 && !empty($member->height)
    //                 && !empty($member->birth_date_time)
    //                 && !empty($member->religion)
    //                 && !empty($member->cast)
    //                 && !empty($member->marital_status)
    //                 && !empty($member->country_living_in)
    //                 && !empty($member->state_living_in)
    //                 && !empty($member->city_living_in)
    //         ],
    //         [
    //             'title' => 'Astro & Kundali',
    //             'completed' => !empty($member->manglik)
    //                 && !empty($member->birth_place)
    //         ],
    //         [
    //             'title' => 'Horoscope',
    //             'completed' => !empty($member->horoscope_needed)
    //         ],
    //         [
    //             'title' => 'Religion & Community',
    //             'completed' => !empty($member->gotra)
    //                 && !empty($member->sub_cast)
    //         ],

    //         [
    //             'title' => 'Education & Career',
    //             'completed' => !empty($member->education)
    //                 && !empty($member->any_other_qualifications)
    //                 && !empty($member->about_my_career)
    //                 && !empty($member->employed_in)
    //                 && !empty($member->organization_name)
    //                 && !empty($member->job_location)
    //                 && !empty($member->occupation)
    //                 && !empty($member->annual_income)
    //         ],
    //         [
    //             'title' => 'Family',
    //             'completed' => !empty($member->family_status)
    //                 && !empty($member->native_place)
    //                 && !empty($member->family_type)
    //                 && !empty($member->father_occupation)
    //                 && !empty($member->father_name)
    //                 && !empty($member->mother_name)
    //                 && !empty($member->mother_occupation)
    //                 && !empty($member->no_of_brothers)
    //                 && !empty($member->no_of_sisters)
    //                 && !empty($member->married_brothers)
    //                 && !empty($member->married_sisters)
    //                 && !empty($member->about_family)
    //         ],
    //         [
    //             'title' => 'Lifestyle',
    //             'completed' => !empty($member->diet)
    //                 && !empty($member->is_smoking)
    //                 && !empty($member->is_drinking)
    //                 && !empty($member->any_disability)
    //         ],
    //         [
    //             'title' => 'Partner Preference',
    //             'completed' => !empty($member->looking_for)
    //                 && !empty($member->partner_age_from)
    //                 && !empty($member->partner_age_to)
    //                 && !empty($member->partner_height_from)
    //                 && !empty($member->partner_height_to)
    //                 && !empty($member->partner_religion)
    //                 && !empty($member->partner_cast)
    //                 && !empty($member->partner_mothertongue)
    //                 && !empty($member->partner_education)
    //                 && !empty($member->partner_occupation)
    //                 && !empty($member->partner_annual_income_from)
    //                 && !empty($member->partner_annual_income_to)
    //                 && !empty($member->is_partner_smoking)
    //                 && !empty($member->is_partner_drinking)
    //                 && !empty($member->is_partner_manglik)
    //         ]
    //     ];

    //     //dd($steps);

    //     $completedSteps = 0;

    //     foreach ($steps as $step) {
    //         if ($step['completed']) {
    //             $completedSteps++;
    //         }
    //     }

    //     $completion = round(($completedSteps / count($steps)) * 100);
    //     $circumference = 226.2;
    //     $strokeOffset = $circumference - ($completion / 100) * $circumference;

    //     $data['plan'] = MembershipPlan::where('id', $member->plan_id)->first();
    //     $data['shortlisted'] = Shortlist::where('member_id', $id)->count();
    //     $data['iLikes'] = ProfileLike::where('user_id', $id)->count();
    //     $data['iviewed'] = ProfileViewed::where('viewed_profile_id', $id)->count();
    //     $data['interestSent'] = SentInterest::where('member_id', $id)->count();
    //     $data['contact'] = ProfileViewed::where('member_id', $id)->count();

    //     /* recent profiles */
    //     $today   = Carbon::today()->format('Y-m-d');
    //     $loggedInUser = Member::find($id);
    //     if (!$loggedInUser) {
    //         return [];
    //     }
    //     $gender = $loggedInUser->gender;
    //     $recents = Member::where('gender', '!=', $gender)
    //         ->where('id', '!=', $id)
    //         ->where('profile_hide', '!=', 'yes')
    //         ->where('active', 'Yes')
    //         ->orderBy('activation_number', 'desc')
    //         ->limit(30)
    //         ->get();

    //     $users = [];

    //     foreach ($recents as $key => $recent) {
    //         $birthDate = Carbon::parse($recent->birth_date_time);
    //         $diff = $birthDate->diff(Carbon::parse($today));
    //         $users[$key]['age_years']  = $diff->y;
    //         $users[$key]['age_months'] = $diff->m;
    //         if (!empty($recent->photo) && $recent->photo_approved === "Yes") {
    //             $users[$key]['photo'] = \App\Website\Services\ProfilePhotoUrl::get($recent->photo);
    //         } elseif ($recent->gender === "Male") {
    //             $users[$key]['photo'] = "/images/profile_photos/boy.jpg";
    //         } elseif ($recent->gender === "Female") {
    //             $users[$key]['photo'] = "/images/profile_photos/girl.jpg";
    //         }
    //         if ($recent->member_type === 'Verified') {
    //             $users[$key]['member_type'] = "https://himrishtey.com/img/verified.png";
    //             $users[$key]['mem_type']    = "Yes";
    //         } else {
    //             $users[$key]['member_type'] = "normal";
    //         }
    //         if ($recent->is_trusted === 'Trusted') {
    //             $users[$key]['is_trusted'] = "https://himrishtey.com/img/trusted.png";
    //         } else {
    //             $users[$key]['is_trusted'] = "No";
    //         }
    //         $users[$key] = array_merge($recent->toArray(), $users[$key]);
    //     }
    //     $data['recents'] = $users;

    //     /********** Verified Users **************/
    //     $verified = Member::where('gender', '!=', $gender)
    //         ->where('id', '!=', $id)
    //         ->where('profile_hide', '!=', 'yes')
    //         ->where('member_type', 'Verified')
    //         ->where('active', 'Yes')
    //         ->orderBy('activation_number', 'desc')
    //         ->limit(30)
    //         ->get();

    //     $data['verifiedUsers'] = [];
    //     foreach ($verified as $key => $recent) {
    //         $birthDate = Carbon::parse($recent->birth_date_time);
    //         $diff = $birthDate->diff(Carbon::parse($today));
    //         $data['verifiedUsers'][$key]['age_years']  = $diff->y;
    //         $data['verifiedUsers'][$key]['age_months'] = $diff->m;
    //         if (!empty($recent->photo) && $recent->photo_approved === "Yes") {
    //             $data['verifiedUsers'][$key]['photo'] = \App\Website\Services\ProfilePhotoUrl::get($recent->photo);
    //         } elseif ($recent->gender === "Male") {
    //             $data['verifiedUsers'][$key]['photo'] = "/images/profile_photos/boy.jpg";
    //         } elseif ($recent->gender === "Female") {
    //             $data['verifiedUsers'][$key]['photo'] = "/images/profile_photos/girl.jpg";
    //         }
    //         if ($recent->member_type === 'Verified') {
    //             $data['verifiedUsers'][$key]['member_type'] = "https://himrishtey.com/img/verified.png";
    //             $data['verifiedUsers'][$key]['mem_type']    = "Yes";
    //         } else {
    //             $data['verifiedUsers'][$key]['member_type'] = "normal";
    //         }
    //         if ($recent->is_trusted === 'Trusted') {
    //             $data['verifiedUsers'][$key]['is_trusted'] = "https://himrishtey.com/img/trusted.png";
    //         } else {
    //             $data['verifiedUsers'][$key]['is_trusted'] = "No";
    //         }
    //         $data['verifiedUsers'][$key] = array_merge($recent->toArray(), $data['verifiedUsers'][$key]);
    //     }

    //     /********** Who viewewd *********/
    //     $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
    //         ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
    //         ->where('profile_viewed.viewed_profile_id', $id)
    //         ->where('members.active', 'Yes')
    //         ->where('members.profile_hide', '!=', 'yes')
    //         ->orderBy('profile_viewed.id', 'desc')
    //         ->limit(30)
    //         ->get();

    //     $data['viewed'] = [];

    //     foreach ($recents as $key => $recent) {
    //         $birthDate = Carbon::parse($recent->birth_date_time);
    //         $diff = $birthDate->diff($today);
    //         $data['viewed'][$key]['id']                = $recent->id;
    //         $data['viewed'][$key]['profile_id']        = $recent->profile_id;
    //         $data['viewed'][$key]['full_name']         = $recent->full_name;
    //         $data['viewed'][$key]['age_years']         = $diff->y;
    //         $data['viewed'][$key]['age_months']        = $diff->m;
    //         $data['viewed'][$key]['birth_date_time']   = $birthDate->format('d-m-Y h:i:s A');
    //         $data['viewed'][$key]['religion']          = $recent->religion;
    //         $data['viewed'][$key]['cast']              = $recent->cast;
    //         $data['viewed'][$key]['height']            = $recent->height;
    //         $data['viewed'][$key]['mother_tongue']     = $recent->mother_tongue;
    //         $data['viewed'][$key]['annual_income']     = $recent->annual_income;
    //         $data['viewed'][$key]['gender']            = $recent->gender;
    //         $data['viewed'][$key]['activation_number'] = $recent->activation_number;
    //         if (!empty($recent->photo) && $recent->photo_approved === "Yes") {
    //             $data['viewed'][$key]['photo'] = \App\Website\Services\ProfilePhotoUrl::get($recent->photo);
    //         } elseif ($recent->gender === "Male") {
    //             $data['viewed'][$key]['photo'] = "/images/profile_photos/boy.jpg";
    //         } elseif ($recent->gender === "Female") {
    //             $data['viewed'][$key]['photo'] = "/images/profile_photos/girl.jpg";
    //         }
    //         if ($recent->member_type === 'Verified') {
    //             $data['viewed'][$key]['member_type'] = "https://himrishtey.com/img/verified.png";
    //             $data['viewed'][$key]['mem_type']    = "Yes";
    //         } else {
    //             $data['viewed'][$key]['member_type'] = "normal";
    //         }
    //         if ($recent->is_trusted === 'Trusted') {
    //             $data['viewed'][$key]['is_trusted'] = "https://himrishtey.com/img/trusted.png";
    //         } else {
    //             $data['viewed'][$key]['is_trusted'] = "No";
    //         }
    //         $data['viewed'][$key] = array_merge($recent->toArray(), $data['viewed'][$key]);
    //     }

    //     /****** shortlisted profiles **************/
    //     $results = Member::whereIn('id', function ($query) use ($id) {
    //         $query->select('profile_id')
    //             ->from('short_listed')
    //             ->where('member_id', $id);
    //     })
    //         ->where('active', 'Yes')
    //         ->where('profile_hide', '!=', 'yes')
    //         ->orderBy('activation_number', 'desc')
    //         ->get();

    //     $data['shortlist'] = [];

    //     foreach ($results as $key => $recent) {
    //         $birthDate = Carbon::parse($recent->birth_date_time);
    //         $diff = $birthDate->diff($today);

    //         $data['shortlist'][$key]['age_years']  = $diff->y;
    //         $data['shortlist'][$key]['age_months'] = $diff->m;
    //         if (!empty($recent->photo) && $recent->photo_approved === "Yes") {
    //             $data['shortlist'][$key]['photo'] = \App\Website\Services\ProfilePhotoUrl::get($recent->photo);
    //         } elseif ($recent->gender === "Male") {
    //             $data['shortlist'][$key]['photo'] = "/images/profile_photos/boy.jpg";
    //         } elseif ($recent->gender === "Female") {
    //             $data['shortlist'][$key]['photo'] = "/images/profile_photos/girl.jpg";
    //         }
    //         if ($recent->member_type === 'Verified') {
    //             $data['shortlist'][$key]['member_type'] = "https://himrishtey.com/img/verified.png";
    //             $data['shortlist'][$key]['mem_type']    = "Yes";
    //         } else {
    //             $data['shortlist'][$key]['member_type'] = "normal";
    //         }
    //         if ($recent->is_trusted === 'Trusted') {
    //             $data['shortlist'][$key]['is_trusted'] = "https://himrishtey.com/img/trusted.png";
    //         } else {
    //             $data['shortlist'][$key]['is_trusted'] = "No";
    //         }
    //         $data['shortlist'][$key] = array_merge($recent->toArray(), $data['shortlist'][$key]);
    //     }

    //     /* Matching profiles */

    //     $partner_country    = explode(',', $member->partner_country ?? '');
    //     $partner_religion   = explode(',', $member->partner_religion ?? '');
    //     $partner_education  = explode(',', $member->partner_education ?? '');
    //     $partner_mothertongue = explode(',', $member->partner_mothertongue ?? '');

    //     if ($member->partner_cast === 'Any') {
    //         $partner_cast = Member::where('cast', '!=', '')->distinct()->pluck('cast')->toArray();
    //     } else {
    //         $partner_cast = explode(',', $member->partner_cast ?? '');
    //     }
    //     $today = Carbon::today();

    //     $data['matching_profiles'] = Member::where('gender', '!=', $member->gender)
    //         ->where('id', '!=', $id)
    //         ->where('birth_date_time', 'not like', '%00:30:00%')
    //         ->get()
    //         ->filter(function ($profile) use (
    //             $member,
    //             $today,
    //             $partner_country,
    //             $partner_religion,
    //             $partner_cast,
    //             $partner_education,
    //             $partner_mothertongue
    //         ) {

    //             $age = Carbon::parse($profile->birth_date_time)->diffInYears($today);
    //             return $age >= $member->partner_age_from &&
    //                 $age <= $member->partner_age_to &&
    //                 $profile->height >= $member->partner_height_from &&
    //                 $profile->height <= $member->partner_height_to &&
    //                 in_array($profile->religion, $partner_religion) &&
    //                 in_array($profile->country_living_in, $partner_country) &&
    //                 in_array($profile->cast, $partner_cast) &&
    //                 in_array($profile->education, $partner_education) &&
    //                 in_array($profile->mother_tongue, $partner_mothertongue);
    //         });
    //     //dd($data['matching_profiles']);
    //     return view('dashboard/home', compact(['member', 'data', 'completion', 'steps', 'strokeOffset']));
    // }

    public function index()
    {
        $member = Auth::guard('member')->user();
        $id = $member->id;

        /*
    |--------------------------------------------------------------------------
    | Profile Completion
    |--------------------------------------------------------------------------
    */

        $steps = array_values($member->profileCompletionSections());
        $completion = $member->profileCompletionPercentage();

        $circumference = 226.2;

        $strokeOffset = $circumference -
            ($completion / 100) * $circumference;

        /*
    |--------------------------------------------------------------------------
    | Dashboard Counts
    |--------------------------------------------------------------------------
    */

        $data['plan'] = MembershipPlan::find($member->plan_id);

        $data['shortlisted'] = Shortlist::where(
            'member_id',
            $id
        )->count();

        $data['iLikes'] = ProfileLike::where(
            'user_id',
            $id
        )->count();

        $data['iviewed'] = ProfileViewed::where(
            'viewed_profile_id',
            $id
        )->count();

        $data['interestSent'] = SentInterest::where(
            'member_id',
            $id
        )->count();

        $data['contact'] = ProfileViewed::where(
            'member_id',
            $id
        )->count();

        /*
    |--------------------------------------------------------------------------
    | Common Profile Query
    |--------------------------------------------------------------------------
    */

        $profileColumns = [
            'members.id',
            'members.profile_id',
            'members.full_name',
            'members.gender',
            'members.birth_date_time',
            'members.religion',
            'members.cast',
            'members.height',
            'members.mother_tongue',
            'members.annual_income',
            'members.photo',
            'members.photo_approved',
            'members.member_type',
            'members.is_trusted',
            'members.activation_number',
        ];

        /*
    |--------------------------------------------------------------------------
    | Recent Profiles
    |--------------------------------------------------------------------------
    */

        $recentProfiles = Member::query()
            ->select($profileColumns)
            ->where('gender', '!=', $member->gender)
            ->where('id', '!=', $id)
            ->where('profile_hide', '!=', 'yes')
            ->where('active', 'Yes')
            ->orderByDesc('activation_number')
            ->limit(30)
            ->get();

        // dd($recentProfiles);

        $data['recents'] = $this->formatDashboardProfiles(
            $recentProfiles
        );

        /*
    |--------------------------------------------------------------------------
    | Verified Profiles
    |--------------------------------------------------------------------------
    */

        $verifiedProfiles = Member::query()
            ->select($profileColumns)
            ->where('gender', '!=', $member->gender)
            ->where('id', '!=', $id)
            ->where('profile_hide', '!=', 'yes')
            ->where('member_type', 'Verified')
            ->where('active', 'Yes')
            ->orderByDesc('activation_number')
            ->limit(30)
            ->get();

        $data['verifiedUsers'] = $this->formatDashboardProfiles(
            $verifiedProfiles
        );

        /*
    |--------------------------------------------------------------------------
    | Shortlisted Profiles
    |--------------------------------------------------------------------------
    */

        $shortlistedProfiles = Member::query()
            ->select($profileColumns)
            ->whereIn('id', function ($query) use ($id) {

                $query->select('profile_id')
                    ->from('short_listed')
                    ->where('member_id', $id);
            })
            ->where('active', 'Yes')
            ->where('profile_hide', '!=', 'yes')
            ->orderByDesc('activation_number')
            ->limit(30)
            ->get();

        $data['shortlist'] = $this->formatDashboardProfiles(
            $shortlistedProfiles
        );

        /*
        |--------------------------------------------------------------------------
        | Who Viewed My Profile
        |--------------------------------------------------------------------------
        |
        | profile_viewed:
        |   member_id          = person who viewed the profile
        |   viewed_profile_id  = profile that was viewed
        |
        */

        $viewedProfiles = Member::query()
            ->select($profileColumns)
            ->join(
                'profile_viewed',
                'members.id',
                '=',
                'profile_viewed.member_id'
            )
            ->where(
                'profile_viewed.viewed_profile_id',
                $id
            )
            ->where('members.id', '!=', $id)
            ->where('members.active', 'Yes')
            ->where('members.profile_hide', '!=', 'yes')
            ->orderByDesc('profile_viewed.id')
            ->limit(30)
            ->get();

        $data['viewed'] = $this->formatDashboardProfiles(
            $viewedProfiles
        );

        /*
    |--------------------------------------------------------------------------
    | Matching Profiles
    |--------------------------------------------------------------------------
    */

        $partnerCountry = array_filter(
            explode(',', $member->partner_country ?? '')
        );

        $partnerReligion = array_filter(
            explode(',', $member->partner_religion ?? '')
        );

        $partnerEducation = array_filter(
            explode(',', $member->partner_education ?? '')
        );

        $partnerMotherTongue = array_filter(
            explode(',', $member->partner_mothertongue ?? '')
        );

        if ($member->partner_cast === 'Any') {

            $partnerCast = Member::whereNotNull('cast')
                ->where('cast', '!=', '')
                ->distinct()
                ->pluck('cast')
                ->toArray();
        } else {

            $partnerCast = array_filter(
                explode(',', $member->partner_cast ?? '')
            );
        }

        /*
        | Age calculation in SQL
        */

        $minBirthDate = Carbon::today()
            ->subYears((int) $member->partner_age_to + 1)
            ->addDay()
            ->startOfDay();

        $maxBirthDate = Carbon::today()
            ->subYears((int) $member->partner_age_from)
            ->endOfDay();

        $matchingQuery = Member::query()
            ->select($profileColumns)

            ->where('gender', '!=', $member->gender)
            ->where('id', '!=', $id)

            ->where('active', 'Yes')
            ->where('profile_hide', '!=', 'yes')

            ->whereBetween('birth_date_time', [
                $minBirthDate,
                $maxBirthDate,
            ])

            ->whereBetween('height', [
                $member->partner_height_from,
                $member->partner_height_to,
            ]);

        if (! empty($partnerCountry)) {
            $matchingQuery->whereIn(
                'country_living_in',
                $partnerCountry
            );
        }

        if (! empty($partnerReligion)) {
            $matchingQuery->whereIn(
                'religion',
                $partnerReligion
            );
        }

        if (! empty($partnerCast)) {
            $matchingQuery->whereIn(
                'cast',
                $partnerCast
            );
        }

        if (! empty($partnerEducation)) {
            $matchingQuery->whereIn(
                'education',
                $partnerEducation
            );
        }

        if (! empty($partnerMotherTongue)) {
            $matchingQuery->whereIn(
                'mother_tongue',
                $partnerMotherTongue
            );
        }

        $data['matching_profiles'] = $this
            ->formatDashboardProfiles(
                $matchingQuery
                    ->orderByDesc('activation_number')
                    ->limit(30)
                    ->get()
            );
        // dd($data);

        return view(
            'dashboard/home',
            compact(
                'member',
                'data',
                'completion',
                'steps',
                'strokeOffset'
            )
        );
    }

    private function formatDashboardProfiles($profiles)
    {
        $today = Carbon::today();

        return $profiles->map(function ($profile) use ($today) {

            $birthDate = Carbon::parse(
                $profile->birth_date_time
            );

            $diff = $birthDate->diff($today);

            $profile->age_years = $diff->y;
            $profile->age_months = $diff->m;

            if (
                ! empty($profile->photo) &&
                ($profile->photo_approved === 'Yes' || trim((string) $profile->photo_approved) === '')
            ) {

                $profile->photo =
                    ProfilePhotoUrl::get($profile->photo);
            } elseif ($profile->gender === 'Male') {

                $profile->photo =
                    '/images/profile_photos/boy.jpg';
            } else {

                $profile->photo =
                    '/images/profile_photos/girl.jpg';
            }

            if ($profile->member_type === 'Verified') {

                $profile->member_type =
                    '/img/verified.png';

                $profile->mem_type = 'Yes';
            } else {

                $profile->member_type = 'normal';
                $profile->mem_type = 'No';
            }

            if ($profile->is_trusted === 'Trusted') {

                $profile->is_trusted =
                    '/img/trusted.png';
            } else {

                $profile->is_trusted = 'No';
            }

            return $profile;
        });
    }

    public function quick_search(Request $request)
    {
        $member = Auth::guard('member')->user();
        $data = [];
        $data['religions'] = Religion::all();
        $data['casts'] = Cast::all();
        $data['mstatus'] = MaritalStatus::all();

        return view(
            'dashboard.search.quick-search',
            compact('member', 'data')
        );
    }

    /**
     * Process search and display results.
     */
    public function searchResults(Request $request)
    {
        $member = Auth::guard('member')->user();
        $advancedFilterKeys = [
            'profile_id',
            'age_from',
            'age_to',
            'height_from',
            'height_to',
            'annual_income',
            'annual_income_to',
            'manglik',
            'religion',
            'cast',
            'mother_tongue',
            'state_name',
            'city_name',
            'education',
            'employed_in',
        ];
        $isAdvancedSearch = $request->input('_source') === 'advanced'
            || $request->hasAny($advancedFilterKeys);
        $searchSource = $isAdvancedSearch ? 'advanced' : 'quick';
        $searchReturnUrl = $searchSource === 'advanced'
            ? route('advance-search')
            : route('quick-search');

        /*
    |--------------------------------------------------------------------------
    | Get search parameters
    |--------------------------------------------------------------------------
    */

        $partnerAgeFrom = $request->input('partner_age_from', $request->input('age_from'));
        $partnerAgeTo = $request->input('partner_age_to', $request->input('age_to'));
        $partnerReligion = $request->input('partner_religion', $request->input('religion'));
        $partnerCast = $request->input('partner_cast', $request->input('cast'));
        $maritalStatus = $request->input('marital_status');
        $lookingFor = $request->input('looking_for');
        $stateName = $request->input('state_name');
        $cityName = $request->input('city_name');

        /*
    |--------------------------------------------------------------------------
    | Convert comma separated values to arrays
    |--------------------------------------------------------------------------
    */

        $partnerReligions = [];
        if (! empty($partnerReligion)) {
            $partnerReligions = array_map('trim', explode(',', $partnerReligion));
        }
        $partnerCasts = [];
        if (! empty($partnerCast)) {
            $partnerCasts = array_map('trim', explode(',', $partnerCast));
        }

        /*
    |--------------------------------------------------------------------------
    | Build query
    |--------------------------------------------------------------------------
    */

        $query = Member::query();
        \App\Support\AnnualIncomeOptions::filter($query, $request->input('annual_income'), $request->input('annual_income_to'));
        foreach (['mother_tongue', 'education', 'employed_in'] as $field) {
            if ($request->filled($field)) {
                $query->whereIn($field, array_filter(array_map('trim', explode(',', $request->input($field)))));
            }
        }
        if ($request->filled('manglik')) {
            $query->where('manglik', $request->input('manglik'));
        }
        if ($request->filled('profile_id')) {
            $query->where('profile_id', trim($request->input('profile_id')));
        }
        if ($request->filled('height_from') || $request->filled('height_to')) {
            $toInches = static function (string $height): int {
                $parts = explode('.', $height, 2);
                return (int) $parts[0] * 12 + (int) ($parts[1] ?? 0);
            };
            $minHeight = $toInches((string) $request->input('height_from', '0'));
            $maxHeight = $toInches((string) $request->input('height_to', '9'));
            $heights = [];
            for ($inches = max(0, $minHeight); $inches <= min(119, $maxHeight); $inches++) {
                $feet = intdiv($inches, 12);
                $remainder = $inches % 12;
                $heights[] = $feet . '.' . $remainder;
                if ($remainder === 0) {
                    $heights[] = (string) $feet;
                }
            }
            $query->whereIn('height', $heights);
        }
        // Don't show current member
        $query->where('id', '!=', $member->id);
        if (in_array($lookingFor, ['Male', 'Female'], true)) {
            $query->where('gender', $lookingFor);
        } else {
            $query->where('gender', '!=', $member->gender);
        }
        // Active members
        $query->where('active', 'yes');
        // verified
        $query->where('member_type', 'Verified');

        /*
    |--------------------------------------------------------------------------
    | Marital Status
    |--------------------------------------------------------------------------
    */

        if (
            ! empty($maritalStatus) && $maritalStatus !== 'Any'
        ) {
            $query->where('marital_status', $maritalStatus);
        }

        /*
    |--------------------------------------------------------------------------
    | Religion
    |--------------------------------------------------------------------------
    */

        if (! empty($partnerReligions)) {
            $query->whereIn('religion', $partnerReligions);
        }
        /*
    |--------------------------------------------------------------------------
    | Cast
    |--------------------------------------------------------------------------
    */

        if (! empty($partnerCasts)) {
            $query->whereIn('cast', $partnerCasts);
        }

        if (filled($cityName)) {
            $query->where('city_living_in', $cityName);
        }

        if (! empty($stateName)) {
            $query->whereIn('state_living_in', array_filter(array_map('trim', explode(',', $stateName))));
        }

        /*
    |--------------------------------------------------------------------------
    | Age filter
    |--------------------------------------------------------------------------
    */

        if (
            ! empty($partnerAgeFrom) &&
            ! empty($partnerAgeTo)
        ) {

            $maxBirthDate = Carbon::today()
                ->subYears((int) $partnerAgeFrom);

            $minBirthDate = Carbon::today()
                ->subYears((int) $partnerAgeTo + 1)
                ->addDay();

            $query->whereBetween(
                'birth_date_time',
                [
                    $minBirthDate,
                    $maxBirthDate,
                ]
            );
        } elseif (! empty($partnerAgeFrom)) {

            $maxBirthDate = Carbon::today()
                ->subYears((int) $partnerAgeFrom);

            $query->whereDate(
                'birth_date_time',
                '<=',
                $maxBirthDate
            );
        } elseif (! empty($partnerAgeTo)) {

            $minBirthDate = Carbon::today()
                ->subYears((int) $partnerAgeTo + 1)
                ->addDay();

            $query->whereDate(
                'birth_date_time',
                '>=',
                $minBirthDate
            );
        }
        /*
    |--------------------------------------------------------------------------
    | Get members
    |--------------------------------------------------------------------------
    */

        $members = $query
            ->orderBy('activation_number', 'desc')
            ->get();
        /*
    |--------------------------------------------------------------------------
    | Prepare profiles for JavaScript
    |--------------------------------------------------------------------------
    */

        $searchedMembers = $members->map(function ($recent) {

            /*
        |--------------------------------------------------------------------------
        | Age
        |--------------------------------------------------------------------------
        */

            $age = null;
            if (! empty($recent->birth_date_time)) {
                try {
                    $birthDate = Carbon::parse(
                        $recent->birth_date_time
                    );

                    $age = $birthDate->age;
                } catch (\Exception $e) {
                    $age = null;
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Photo
        |--------------------------------------------------------------------------
        */

            if (
                ! empty($recent->photo) &&
                $recent->photo_approved === 'Yes'
            ) {

                $photo =
                    ProfilePhotoUrl::get($recent->photo);
            } elseif (
                strtolower($recent->gender) === 'male'
            ) {

                $photo =
                    '/images/profile_photos/boy.jpg';
            } else {

                $photo =
                    '/images/profile_photos/girl.jpg';
            }

            /*
        |--------------------------------------------------------------------------
        | Verified
        |--------------------------------------------------------------------------
        */

            $verified =
                $recent->member_type === 'Verified';

            /*
        |--------------------------------------------------------------------------
        | Trusted
        |--------------------------------------------------------------------------
        */

            $trusted =
                $recent->is_trusted === 'Trusted';

            /*
        |--------------------------------------------------------------------------
        | Return profile
        |--------------------------------------------------------------------------
        */

            return [
                'id' => $recent->id,

                'profile_id' => $recent->profile_id,

                'name' => $recent->full_name,

                'age' => $age,

                'height' => $recent->height,

                'location' => trim(
                    implode(', ', array_filter([
                        $recent->city_living_in,
                        $recent->state_living_in,
                    ]))
                ),

                'occupation' => $recent->occupation,

                'religion' => $recent->religion,

                'education' => $recent->education,

                'cast' => $recent->cast,

                'marital_status' => $recent->marital_status,

                'photo' => $photo,

                'verified' => $verified,

                'trusted' => $trusted,

                'member_type' => $recent->member_type,

                'online' => false,
            ];
        })->values();

        /*
    |--------------------------------------------------------------------------
    | Return view
    |--------------------------------------------------------------------------
    */

        return view(
            'dashboard.search.search-results',
            compact(
                'member',
                'searchedMembers',
                'partnerAgeFrom',
                'partnerAgeTo',
                'partnerReligions',
                'partnerCasts',
                'maritalStatus',
                'searchSource',
                'searchReturnUrl'
            )
        );
    }

    /**
     * Convert comma separated values into an array.
     *
     * Example:
     * "Hindu,Sikh" => ["Hindu", "Sikh"]
     */
    private function parseMultiValue(?string $value): array
    {
        if (empty($value)) {
            return [];
        }

        return array_values(
            array_filter(
                array_map(
                    'trim',
                    explode(',', $value)
                )
            )
        );
    }

    public function search_by_profile_id(Request $request)
    {
        $member = Auth::guard('member')->user();
        $id = $member->id;
        $gender = $member->gender;
        $today = Carbon::now();
        $profile = null;
        $profile_id = null;
        if ($request->filled('profile_id')) {
            $profile_id = $request->input('profile_id');

            $profile = Member::where('gender', '!=', $gender)
                ->where('id', '!=', $id)
                ->where('profile_id', $profile_id)
                ->where('profile_hide', '!=', 'yes')
                ->where('active', 'Yes')
                ->first();
            if ($profile) {
                // Calculate age
                $birthDate = Carbon::parse($profile->birth_date_time);
                $diff = $birthDate->diff($today);
                $profile['age_years'] = $diff->y;
                $profile['age_months'] = $diff->m;

                // Photo
                if (! empty($profile->photo) && $profile->photo_approved === 'Yes') {
                    $profile['photo'] = ProfilePhotoUrl::get($profile->photo);
                } elseif ($profile->gender === 'Male') {
                    $profile['photo'] = '/images/profile_photos/boy.jpg';
                } elseif ($profile->gender === 'Female') {
                    $profile['photo'] = '/images/profile_photos/girl.jpg';
                }

                // Member type
                if ($profile->member_type === 'Verified') {
                    $profile['member_type'] = 'https://himrishtey.com/img/verified.png';
                    $profile['mem_type'] = 'Yes';
                } else {
                    $profile['member_type'] = 'normal';
                }

                // Trusted
                if ($profile->is_trusted === 'Trusted') {
                    $profile['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
                } else {
                    $profile['is_trusted'] = 'No';
                }
            }
        }

        return view('dashboard.search.search-by-id', compact(['profile', 'profile_id']));
    }

    public function searchByProfileIdApi($profile_id)
    {
        $member = Auth::guard('member')->user();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        $id = $member->id;
        $gender = $member->gender;

        $profile_id = strtoupper(trim($profile_id));

        $profile = Member::where('profile_id', $profile_id)
            ->where('gender', '!=', $gender)
            ->where('id', '!=', $id)
            ->where(function ($query) {
                $query->whereNull('profile_hide')
                    ->orWhere('profile_hide', '!=', 'yes');
            })
            ->where('active', 'Yes')
            ->first();

        if (! $profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile not found.',
            ], 404);
        }

        /*
    |--------------------------------------------------------------------------
    | Age
    |--------------------------------------------------------------------------
    */

        $ageYears = null;
        $ageMonths = null;

        if (! empty($profile->birth_date_time)) {

            $birthDate = Carbon::parse($profile->birth_date_time);
            $diff = $birthDate->diff(Carbon::now());

            $ageYears = $diff->y;
            $ageMonths = $diff->m;
        }

        /*
    |--------------------------------------------------------------------------
    | Photo
    |--------------------------------------------------------------------------
    */

        if (
            ! empty($profile->photo) &&
            $profile->photo_approved === 'Yes'
        ) {

            $photoUrl =
                ProfilePhotoUrl::get($profile->photo);
        } elseif ($profile->gender === 'Male') {

            $photoUrl =
                '/images/profile_photos/boy.jpg';
        } else {

            $photoUrl =
                '/images/profile_photos/girl.jpg';
        }

        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([
            'success' => true,

            'user' => [
                'id' => $profile->id,
                'profile_id' => $profile->profile_id,

                'first_name' => $profile->first_name,
                'last_name' => $profile->last_name,

                'gender' => $profile->gender,

                'age_years' => $ageYears,
                'age_months' => $ageMonths,

                'photo_url' => $photoUrl,

                'verified' => $profile->member_type === 'Verified',

                'trusted' => $profile->is_trusted === 'Trusted',

                'height' => $profile->height ?? null,
                'religion' => $profile->religion ?? null,
                'community' => $profile->community ?? null,
                'education' => $profile->education ?? null,
                'occupation' => $profile->occupation ?? null,
                'city' => $profile->city ?? null,
                'mother_tongue' => $profile->mother_tongue ?? null,
                'marital_status' => $profile->marital_status ?? null,
                'income' => $profile->income ?? null,
            ],
        ]);
    }

    public function interest_box()
    {
        $data['received_pending_interests'] = $this->received_pending_interest();
        $data['received_accepted_interests'] = $this->received_accepted_interest();
        $data['received_rejected_interests'] = $this->received_rejected_interest();
        $data['sent_pending_interests'] = $this->sent_pending_interest();
        $data['sent_accepted_interests'] = $this->sent_accepted_interest();
        $data['sent_rejected_interests'] = $this->sent_rejected_interest();

        // dd($data['received_pending_interests']->first());
        return view('dashboard.interest.interest-box', compact('data'));
    }

    public function received_pending_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.member_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.profile_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 0)
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function received_accepted_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.member_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.profile_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 1)
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function received_rejected_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.member_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.profile_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 2) // ✅ rejected only
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function sent_pending_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.profile_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.member_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 0) // ✅ pending sent requests
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function sent_accepted_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.profile_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.member_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 1) // ✅ pending sent requests
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function sent_rejected_interest()
    {
        $user_id = Auth::guard('member')->user()->id;
        $results = DB::connection('site')->table('members')
            ->join('sent_interests', 'members.id', '=', 'sent_interests.profile_id')
            ->select(
                'members.*',
                'sent_interests.member_id',
                'sent_interests.created_at as interest_created_at',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('sent_interests.member_id', $user_id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->where('sent_interests.status', 2) // ✅ pending sent requests
            ->orderBy('sent_interests.id', 'desc')
            ->get();

        foreach ($results as $key => $result) {
            if (! empty($result->photo) && $result->photo_approved === 'Yes') {
                $results[$key]->photo = ProfilePhotoUrl::get($result->photo);
            } elseif ($result->gender === 'Male') {
                $results[$key]->photo = '/images/profile_photos/boy.jpg';
            } elseif ($result->gender === 'Female') {
                $results[$key]->photo = '/images/profile_photos/girl.jpg';
            }
            if (! empty($result->birth_date_time)) {
                $birthDate = Carbon::parse($result->birth_date_time);
                $ageDiff = $birthDate->diff(Carbon::today());

                $results[$key]->age_years = $ageDiff->y;
                $results[$key]->age_months = $ageDiff->m;
            } else {
                $results[$key]->age_years = null;
                $results[$key]->age_months = null;
            }
            if ($result->member_type === 'Verified') {
                $results[$key]->member_type = 'https://himrishtey.com/img/verified.png';
                $results[$key]->mem_type = 'Yes';
            } else {
                $results[$key]->member_type = 'normal';
            }
            if ($result->is_trusted === 'Trusted') {
                $results[$key]->is_trusted = 'https://himrishtey.com/img/trusted.png';
            }
            if (! empty($result->interest_created_at)) {
                $results[$key]->sentDate = Carbon::parse($result->interest_created_at)->diffForHumans();
            } else {
                $results[$key]->sentDate = '';
            }
        }

        return $results;
    }

    public function updateInterestStatus(Request $request)
    {
        // dd($request->status);
        $request->validate([
            'member_id' => 'required|integer',
            'status' => 'required|in:0,1,2', // 0=Pending, 1=Accepted, 2=Rejected
        ]);

        $loggedInUser = Auth::guard('member')->user()->id;

        $updated = DB::connection('site')->table('sent_interests')
            ->where('member_id', $request->member_id)
            ->where('profile_id', $loggedInUser)
            ->update([
                'status' => $request->status,
            ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to update status.',
        ], 400);
    }

    public function view_my_profile()
    {
        $profile = Auth::guard('member')->user();
        // dd($profile);

        $profilegallery = $profile->photos()->get();
        $profile->profile_photo_url = filled($profile->photo)
            ? ProfilePhotoUrl::get($profile->photo)
            : asset($profile->gender === 'Female'
                ? 'images/profile_photos/girl.jpg'
                : 'images/profile_photos/boy.jpg');
        $profile->formatted_height = \App\Support\HeightFormatter::format($profile->height, 'Not provided');
        $birthDate = null;
        if (filled($profile->birth_date_time) && !str_starts_with((string) $profile->birth_date_time, '0000-00-00')) {
            try {
                $birthDate = Carbon::parse($profile->birth_date_time);
            } catch (\Throwable $exception) {
                // Legacy records can contain an invalid or incomplete birth date.
            }
        }
        $profile->date = $birthDate?->format('d-m-Y') ?? 'Not provided';
        $profile->time = $birthDate?->format('h:i A') ?? 'Not provided';
        $profile->age_years = $birthDate ? (int) $birthDate->diffInYears(Carbon::today()) : null;
        $profile->age_months = $birthDate?->diff(Carbon::today())->m;

        return view('dashboard.profile.view-my-profile', compact('profile', 'profilegallery'));
    }

    public function viewed_contacts()
    {
        $id = Auth::guard('member')->user()->id;
        $today = Carbon::today()->format('Y-m-d');

        $results = DB::connection('site')->table('members')
            ->join('viewed_contacts', 'members.id', '=', 'viewed_contacts.profile_id')
            ->select(
                'members.*',
                'viewed_contacts.member_id',
                DB::connection('site')->raw("DATE_FORMAT(members.birth_date_time, '%d-%m-%Y %I:%i:%S %p') as birthdatetime")
            )
            ->where('viewed_contacts.member_id', $id)
            ->where('members.profile_hide', '!=', 'yes')
            ->where('members.active', 'Yes')
            ->orderBy('viewed_contacts.id', 'asc')
            ->get();

        $data['contacts'] = [];

        foreach ($results as $key => $recent) {
            // Age calculation
            $birthDate = Carbon::parse($recent->birth_date_time);
            $diff = $birthDate->diff(Carbon::today());

            $data['contacts'][$key]['age_years'] = $diff->y;
            $data['contacts'][$key]['age_months'] = $diff->m;

            // Profile photo
            if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                $data['contacts'][$key]['photo'] = ProfilePhotoUrl::get($recent->photo);
            } elseif ($recent->gender === 'Male') {
                $data['contacts'][$key]['photo'] = '/images/profile_photos/boy.jpg';
            } elseif ($recent->gender === 'Female') {
                $data['contacts'][$key]['photo'] = '/images/profile_photos/girl.jpg';
            }

            // Member type (verified or not)
            if ($recent->member_type === 'Verified') {
                $data['contacts'][$key]['member_type'] = 'https://himrishtey.com/img/verified.png';
                $data['contacts'][$key]['mem_type'] = 'Yes';
            } else {
                $data['contacts'][$key]['member_type'] = 'normal';
            }

            // Trusted status
            if ($recent->is_trusted === 'Trusted') {
                $data['contacts'][$key]['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
            } else {
                $data['contacts'][$key]['is_trusted'] = 'No';
            }

            // Merge original record into array (convert stdClass → array)

            $data['contacts'][$key] = array_merge((array) $recent, $data['contacts'][$key]);
        }

        // dd($data);
        return view('dashboard.viewed_contacts.index', compact('data'));
    }

    public function view_profile($profileId, EmailService $emailService)
    {
        $data['user_id'] = Auth::guard('member')->user()->id;

        $profilemain = Member::where('profile_id', $profileId)->firstOrFail();

        $data['photos'] = $profilemain->photos()->get();
        $data['profile_id'] = $profileId;

        $usr = DB::connection('site')->table('members')
            ->where('profile_id', $data['profile_id'])
            ->where('profile_hide', '!=', 'yes')
            ->where('active', 'Yes')
            ->first();

        if (! $usr) {
            return null;
        }

        /*
    |--------------------------------------------------------------------------
    | Check sent interest
    |--------------------------------------------------------------------------
    */
        $profile = DB::connection('site')->table('sent_interests')
            ->where('member_id', $data['user_id'])
            ->where('profile_id', $data['profile_id'])
            ->first();

        /*
    |--------------------------------------------------------------------------
    | Shortlisted
    |--------------------------------------------------------------------------
    */
        $short = DB::connection('site')->table('short_listed')
            ->where('member_id', $data['user_id'])
            ->where('profile_id', $data['profile_id'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Profile views
        |--------------------------------------------------------------------------
        */
        $viewed_contacts = DB::connection('site')->table('viewed_contacts')
            ->where('member_id', $data['user_id'])
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Current user
        |--------------------------------------------------------------------------
        */
        $pc_user = DB::connection('site')->table('members')
            ->where('id', $data['user_id'])
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Current Membership Plan
        |--------------------------------------------------------------------------
        */

        $currentPlan = DB::connection('site')->table('membership_plans')
            ->where('id', $pc_user->plan_id)
            ->first();

        $usr->current_plan_name = $currentPlan?->plan_name ?? 'Free';
        $usr->is_free_member = ! $currentPlan || strtolower($currentPlan->plan_name) === 'free';

        /*
        |--------------------------------------------------------------------------
        | Already viewed contact?
        |--------------------------------------------------------------------------
        */
        $viewed_contact = DB::connection('site')->table('viewed_contacts')
            ->where('member_id', $data['user_id'])
            ->where('profile_id', $usr->id)
            ->first();

        $usr->profile_viewed = $viewed_contact ? 'Yes' : 'No';

        /*
        |--------------------------------------------------------------------------
        | Birth date/time
        |--------------------------------------------------------------------------
        */
        $birthDate = null;

        if (! empty($usr->birth_date_time)) {

            $birthDate = Carbon::parse($usr->birth_date_time);

            // Keep original database value if needed internally
            $usr->birth_date_time_original = $usr->birth_date_time;

            // Actual values
            $usr->birth_date = $birthDate->format('j F Y');
            $usr->birth_time = $birthDate->format('g:i A');

            // Age
            $diff = $birthDate->diff(Carbon::today());

            $usr->age_years = $diff->y;
            $usr->age_months = $diff->m;
        } else {

            $usr->birth_date = '';
            $usr->birth_time = '';
            $usr->age_years = 0;
            $usr->age_months = 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Check like profile
        |--------------------------------------------------------------------------
        */
        $like_profile = DB::connection('site')->table('profile_like')
            ->where('user_id', $data['user_id'])
            ->where('like_profile_id', $data['profile_id'])
            ->where('status', '1')
            ->first();

        $usr->like = $like_profile ? 'Yes' : 'No';

        /*
        |--------------------------------------------------------------------------
        | Profile created for mapping
        |--------------------------------------------------------------------------
        */
        $map = [
            'Self' => 'Self',
            'Relative' => 'Relative',
            'Son' => 'Parents',
            'Daughter' => 'Parents',
            'Brother' => 'Sibilings',
            'Sister' => 'Sibilings',
            'Client (Marriage bureau)' => 'Marriage Bureau',
        ];

        $usr->profile_created_for =
            $map[$usr->profile_created_for] ?? 'Friend';

        $usr->interest = $profile ? '1' : '0';

        $usr->shortlisted = $short ? 'Yes' : 'No';

        /*
        |--------------------------------------------------------------------------
        | Profile photo
        |--------------------------------------------------------------------------
        */
        if (! empty($usr->photo) && ($usr->photo_approved === 'Yes' || trim((string) $usr->photo_approved) === '')) {

            $usr->photo =
                ProfilePhotoUrl::get($usr->photo);
        } elseif ($usr->gender === 'Male') {

            $usr->photo =
                '/images/profile_photos/boy.jpg';
        } elseif ($usr->gender === 'Female') {

            $usr->photo =
                '/images/profile_photos/girl.jpg';
        }

        /*
        |--------------------------------------------------------------------------
        | Verified
        |--------------------------------------------------------------------------
        */
        if ($usr->member_type === 'Verified') {

            $usr->member_type =
                'https://himrishtey.com/img/promoted.png';
        } else {

            $usr->member_type = 'normal';
        }

        /*
        |--------------------------------------------------------------------------
        | Trusted
        |--------------------------------------------------------------------------
        */
        if ($usr->is_trusted === 'Trusted') {

            $usr->is_trusted =
                'https://himrishtey.com/img/trusted.png';
        }

        /*
        |--------------------------------------------------------------------------
        | Record profile view
        |--------------------------------------------------------------------------
        */
        $profile_viewed_check = ProfileViewed::where(
            'member_id',
            $data['user_id']
        )
            ->where(
                'viewed_profile_id',
                $usr->id
            )
            ->first();

        if (empty($profile_viewed_check)) {

            $profile_viewed_by_me = new ProfileViewed;

            $profile_viewed_by_me->member_id =
                $data['user_id'];

            $profile_viewed_by_me->viewed_profile_id =
                $usr->id;

            $profile_viewed_by_me->save();

            $emailService->viewProfile(
                $profilemain,
                $usr
            );
        }

        $viewedProfileCount = $this->viewedProfileCount($data['user_id']);
        $usr->viewed_profile = (string) $viewedProfileCount;
        $usr->profile_view_price = ProfileUnlockPricing::priceForViewCount($viewedProfileCount);

        /*
        |--------------------------------------------------------------------------
        | Wallet
        |--------------------------------------------------------------------------
        */
        $wallet = MemberWallet::where(
            'member_id',
            $data['user_id']
        )
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Interest action
        |--------------------------------------------------------------------------
        */
        $interest_action = SentInterest::where(
            'member_id',
            $data['profile_id']
        )
            ->where(
                'profile_id',
                $data['user_id']
            )
            ->where('status', 0)
            ->first();

        if (! empty($interest_action)) {

            $usr->interest_action = '1';
        } else {

            $interest_action_2 = SentInterest::where(
                'member_id',
                $data['profile_id']
            )
                ->where(
                    'profile_id',
                    $data['user_id']
                )
                ->whereIn('status', [1, 2])
                ->first();

            if (! empty($interest_action_2)) {

                $usr->interest_action = '2';
            } else {

                $usr->interest_action = '0';
            }
        }

        /*
        |--------------------------------------------------------------------------
        |    MASK SENSITIVE INFORMATION
        |--------------------------------------------------------------------------
        |
        | Do this AFTER calculating the actual values.
        |
        */

        // Mobile
        if (! empty($usr->mobile_number)) {

            $mobile = preg_replace(
                '/\D/',
                '',
                $usr->mobile_number
            );

            $usr->mobile_number_masked =
                '****' . substr($mobile, -4);
        } else {

            $usr->mobile_number_masked = '****';
        }

        // WhatsApp
        if (! empty($usr->whatsapp_number)) {

            $whatsapp = preg_replace(
                '/\D/',
                '',
                $usr->whatsapp_number
            );

            $usr->whatsapp_number_masked =
                '****' . substr($whatsapp, -4);
        } else {

            $usr->whatsapp_number_masked = '****';
        }

        // Email
        if (! empty($usr->email)) {

            $emailParts = explode(
                '@',
                $usr->email,
                2
            );

            if (count($emailParts) === 2) {

                $usr->email_masked =
                    '****@' . $emailParts[1];
            } else {

                $usr->email_masked = '****';
            }
        } else {

            $usr->email_masked = '****';
        }

        /*
        |--------------------------------------------------------------------------
        | Membership / Unlock status
        |--------------------------------------------------------------------------
        |
        | For now locked by default.
        | Later we can replace this with your actual membership check.
        |
        */

        $usr->contact_unlocked = (bool) $viewed_contact;

        /*
        |--------------------------------------------------------------------------
        | Return view
        |--------------------------------------------------------------------------
        */

        // dd($usr);
        return view(
            'dashboard.profile.view-profile',
            compact(
                'usr',
                'data',
                'wallet'
            )
        );
    }

    public function send_interest(Request $request, EmailService $emailservice, NimbusSmsService $messageService, $id)
    {

        $member = Auth::guard('member')->user();
        $profile = Member::find($id);
        $status = $request->input('status');
        $email = [
            'from_name' => $member->full_name,
            'from_profile_id' => $member->profile_id,
            'to_full_name' => $profile->full_name,
            'to_profile_id' => $profile->profile_id,
            'to_phone' => $profile->mobile_number,
            'to_email' => $profile->email,
            'status' => $status,
            'token' => $profile->google_token,
        ];
        $plan_id = $member->plan_id;
        $user_id = $member->id;

        $profile_id = $id;

        if ($plan_id == 0 || empty($plan_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please upgrade your membership plan',
            ], 403);
        }

        $checkInterest = SentInterest::where('member_id', $user_id)
            ->where('profile_id', $profile_id)
            ->first();

        $checkedInterest = SentInterest::where('member_id', $profile_id)
            ->where('profile_id', $user_id)
            ->first();

        if (empty($checkInterest)) {
            if (! empty($checkedInterest)) {
                $checkedInterest->status = $status;
                $emailservice->interestEmail($email);
                if ($checkedInterest->save()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Interest status changed successfully',
                    ]);
                }
            } else {
                $newInterest = new SentInterest;
                $newInterest->member_id = $user_id;
                $newInterest->profile_id = $profile_id;
                $newInterest->status = $status;
                // $messageService->sendInterest($email);
                // $emailservice->interestEmail($email);
                // $this->sendFCM($email);
                if ($newInterest->save()) {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Interest sent successfully',
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send interest',
                    ], 500);
                }
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Interest already sent',
            ], 409);
        }
    }

    public function referral()
    {

        return view('dashboard.referral.referral');
    }

    public function success_stories()
    {
        return view('dashboard.success_stories.success-stories');
    }

    public function successStories()
    {
        $userId = auth()->id();

        $success_stories = SuccessStory::where('status', 1)
            ->get()
            ->map(function ($story) {

                $story->photo = $story->photo
                    ? SuccessStoryPhoto::url($story->photo)
                    : asset('uploads/success-stories/default-story.png');

                return $story;
            });

        return response()->json([
            'status' => true,
            'data' => $success_stories,
        ]);
    }

    public function stories_store(Request $request)
    {
        $data = $request->validate([
            'groom_name' => 'required|max:255',
            'bride_name' => 'required|max:255',
            'detail' => 'required|string',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);
        $data['photo'] = basename($request->file('photo')->store('success-stories', 'public'));
        $data['user_id'] = Auth::guard('member')->id();
        $story = new SuccessStory($data);
        $story->status = 0;
        $story->save();

        return response()->json(['status' => true, 'message' => 'Story submitted successfully. It will be reviewed before publishing.']);
    }

    public function update(Request $request, $id)
    {
        $story = SuccessStory::where('user_id', Auth::guard('member')->id())->findOrFail($id);
        $data = $request->validate([
            'groom_name' => 'required|max:255',
            'bride_name' => 'required|max:255',
            'detail' => 'required|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);
        unset($data['photo']);
        $oldPhoto = $story->photo;
        if ($request->hasFile('photo')) {
            $data['photo'] = basename($request->file('photo')->store('success-stories', 'public'));
        }
        $story->fill($data);
        $story->status = 0;
        $story->save();
        if ($request->hasFile('photo')) {
            SuccessStoryPhoto::delete($oldPhoto);
        }

        return response()->json(['status' => true, 'message' => 'Story updated and submitted for review.']);
    }

    public function destroy($id)
    {
        $story = SuccessStory::where('user_id', Auth::guard('member')->id())->findOrFail($id);
        $photo = $story->photo;
        $story->delete();
        SuccessStoryPhoto::delete($photo);

        return redirect()->back()->with('success', 'Story deleted successfully!');
    }

    public function rating()
    {

        return view('dashboard.rateus');
    }

    public function rating_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stars' => 'required|numeric|min:0.5|max:5',
            'feedback' => 'nullable|string|max:1000',
        ], [
            'stars.required' => 'Please select a rating.',
            'stars.numeric' => 'Invalid rating selected.',
            'stars.min' => 'Rating must be at least 0.5 stars.',
            'stars.max' => 'Rating cannot be greater than 5 stars.',
            'feedback.max' => 'Feedback cannot exceed 1000 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // If rating is greater than 3.5, frontend should redirect.
        // Just return the URL.
        if ($request->stars > 3.5) {
            return response()->json([
                'success' => true,
                'redirect' => true,
                'url' => 'https://g.page/r/Caot8HkRuvFYEAE/review',
            ]);
        }

        DB::connection('site')->table('user_rating')->insert([
            'profile_id' => Auth::guard('member')->user()->profile_id,
            'name' => Auth::guard('member')->user()->full_name,
            'email' => Auth::guard('member')->user()->email,
            'rating' => $request->stars,
            'description' => $request->feedback,
            'submitted_on' => now(),
        ]);

        return response()->json([
            'success' => true,
            'redirect' => false,
            'message' => 'Thank you for your valuable feedback.',
        ]);
    }

    public function unlock_contact(Request $request, $profileId)
    {
        $userId = (int) auth()->guard('member')->id();
        $profileId = (int) $profileId;

        if ($userId === $profileId) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot unlock your own contact details.',
            ], 422);
        }

        $usr = DB::connection('site')->table('members')
            ->where('id', $profileId)
            ->where('profile_hide', '!=', 'yes')
            ->where('active', 'Yes')
            ->first();

        if (! $usr) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profile not found.',
            ], 404);
        }

        $result = DB::connection('site')->transaction(function () use ($userId, $profileId) {
            // This row always exists and provides a stable per-member lock, even
            // when the member has no wallet row or the calculated price is zero.
            $member = DB::connection('site')->table('members')
                ->where('id', $userId)
                ->lockForUpdate()
                ->first();

            // Check the current database status before reading contacts or charging the wallet.
            if (! $member || strtolower(trim((string) $member->active)) !== 'yes') {
                return ['inactive_membership' => true];
            }

            $wallet = MemberWallet::where('member_id', $userId)
                ->latest('id')
                ->lockForUpdate()
                ->first();

            // The member lock serializes unlocks for this member. Check the
            // contact only after acquiring it so concurrent requests cannot both
            // observe a missing viewed_contacts row and charge twice.
            $alreadyUnlocked = DB::connection('site')->table('viewed_contacts')
                ->where('member_id', $userId)
                ->where('profile_id', $profileId)
                ->exists();

            if ($alreadyUnlocked) {
                return ['wallet' => $wallet, 'already_unlocked' => true];
            }

            // Calculate the price again on the server so the amount displayed in
            // the browser can never be changed to reduce the wallet deduction.
            $unlockPrice = ProfileUnlockPricing::priceForViewCount(
                $this->viewedProfileCount($userId)
            );

            if (! $wallet || (int) $wallet->wallet_balance < $unlockPrice) {
                return ['insufficient_balance' => true];
            }

            $wallet = MemberWallet::create([
                'member_id' => $userId,
                'amount_added' => 0,
                'amount_deducted' => $unlockPrice,
                'wallet_balance' => (int) $wallet->wallet_balance - $unlockPrice,
            ]);

            DB::connection('site')->table('viewed_contacts')->insert([
                'member_id' => $userId,
                'profile_id' => $profileId,
                'viewed_date' => now(),
            ]);

            return ['wallet' => $wallet, 'already_unlocked' => false];
        }, 3);

        if (! empty($result['inactive_membership'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Active membership required to unlock contact details.',
            ], 403);
        }

        if (! empty($result['insufficient_balance'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Insufficient balance.',
            ], 422);
        }

        return response()->json([
            'status' => 'success',
            'message' => ! empty($result['already_unlocked'])
                ? 'Contact was already unlocked.'
                : 'Contact unlocked successfully!',
            'mobile_number' => $usr->mobile_number,
            'whatsapp_number' => $usr->whatsapp_number,
            'email' => $usr->email,
            'wallet_balance' => (int) ($result['wallet']->wallet_balance ?? 0),
            'already_unlocked' => (bool) ($result['already_unlocked'] ?? false),
        ]);
    }

    private function viewedProfileCount(int $memberId): int
    {
        return DB::connection('site')->table('profile_viewed')
            ->where('member_id', $memberId)
            ->distinct()
            ->count('viewed_profile_id');
    }

    public function like_profile(Request $request)
    {
        $member = Auth::guard('member')->user();

        $profile_id = $request->input('id');
        // dd($profile_id);
        $member_id = $member->id;

        // Check if already liked
        $existingLike = ProfileLike::where('user_id', $member_id)
            ->where('like_profile_id', $profile_id)
            ->first();

        if ($existingLike) {

            // Already liked → unlike
            $existingLike->delete();

            return response()->json([
                'status' => 'unliked',
                'liked' => false,
                'message' => 'Profile unliked',
            ]);
        }

        // Not liked → create like
        $like = new ProfileLike;
        $like->user_id = $member_id;
        $like->like_profile_id = $profile_id;

        if ($like->save()) {
            return response()->json([
                'status' => 'liked',
                'liked' => true,
                'message' => 'Profile liked',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to like profile',
        ], 500);
    }

    public function check_profile_like($id)
    {
        $member = Auth::guard('member')->user();

        $liked = ProfileLike::where('user_id', $member->id)
            ->where('like_profile_id', $id)
            ->exists();

        return response()->json([
            'liked' => $liked,
        ]);
    }

    public function shortlist_profile(Request $request, EmailService $emailservice)
    {

        $member = Auth::guard('member')->user();
        $status = $request->input('status');
        $id = $request->input('id');
        $user_id = $member->id;
        $plan_id = $member->plan_id;
        $profile_id = $id;
        $profile = Member::find($id);
        if ($plan_id == 0 || empty($plan_id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please upgrade your membership plan',
            ], 403);
        }

        // Check if already shortlisted
        $alreadyShortlisted = Shortlist::where('member_id', $user_id)
            ->where('profile_id', $profile_id)
            ->exists();

        if ($alreadyShortlisted) {
            return response()->json([
                'status' => 'already_shortlisted',
                'message' => 'Profile is already shortlisted',
            ], 200);
        }
        $newInterest = new Shortlist;
        $newInterest->member_id = $user_id;
        $newInterest->profile_id = $profile_id;
        // $emailservice->shortlist($profile, $member);
        if ($newInterest->save()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile Shortlisted',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to Shortlist Profile',
            ], 500);
        }
    }

    public function check_shortlist(Request $request)
    {
        $member = Auth::guard('member')->user();

        $profile_id = $request->input('id');
        $shortlisted = Shortlist::where('member_id', $member->id)
            ->where('profile_id', $profile_id)
            ->exists();

        return response()->json([
            'status' => 'success',
            'shortlisted' => $shortlisted,
        ]);
    }

    public function sendFCM($data)
    {
        $fcm = new PushNotificationService;
        $deviceToken = $data['token'];

        $response = $fcm->sendNotification(
            $deviceToken,
            'Test Notification',
            'This is a test message',
            ['screen' => 'home']
        );

        return $response;
    }

    public function uploadPhotos(Request $request)
    {
        $request->validate([
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $user = Auth::guard('member')->user();

        if ($user->photos()->count() + count($request->file('photos')) > 5) {
            return response()->json([
                'message' => 'You can keep a maximum of five gallery photos.',
            ], 422);
        }

        $uploadedPhotos = [];

        foreach ($request->file('photos') as $photo) {
            $imageName = $photo->storeAs('', \App\Services\MemberPhotoFilename::make($user->id, $photo->extension()), 'profile_photos');

            $galleryPhoto = $user->photos()->create([
                'photo' => $imageName,
                'photo_approved' => 'No',
                'photo_privacy' => '1',
            ]);

            $uploadedPhotos[] = [
                'id' => $galleryPhoto->id,
                'url' => ProfilePhotoUrl::get($imageName),
            ];
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Photos uploaded successfully.',
            'photos' => $uploadedPhotos,
        ]);
    }

    public function deleteGalleryPhoto(int $photo)
    {
        $user = Auth::guard('member')->user();
        $galleryPhoto = $user->photos()->findOrFail($photo);
        if ($user->photo !== $galleryPhoto->photo) {
            foreach (['photos/photo/', 'uploads/gallery/'] as $directory) {
                $photoPath = public_path($directory . basename($galleryPhoto->photo));
                if (File::exists($photoPath)) {
                    File::delete($photoPath);
                    break;
                }
            }
        }

        $galleryPhoto->delete();

        return response()->json([
            'success' => true,
            'message' => 'Photo removed successfully.',
        ]);
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $user = Auth::guard('member')->user();
        abort_unless($user, 401);
        $filename = app(ProfilePhotoStorage::class)->save($user, $request->file('photo'));

        return response()->json([
            'success' => true,
            'photo_url' => ProfilePhotoUrl::get($filename),
        ]);
    }
}
