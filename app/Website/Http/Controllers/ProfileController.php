<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Member;
use App\Website\Services\ProfilePhotoUrl;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
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

    public function recent_profiles(Request $request)
    {
        $id = Auth::guard('member')->user()->id;
        $member = Member::find($id);
        $today = Carbon::today()->format('Y-m-d');
        $profileFor = $request->input('profile');
        $loggedInUser = Member::find($id);
        if (! $loggedInUser) {
            return [];
        }
        $gender = $loggedInUser->gender;
        if ($profileFor == 'recent') {
            $recents = Member::where('gender', '!=', $gender)
                ->where('id', '!=', $id)
                ->where('profile_hide', '!=', 'yes')
                ->where('active', 'Yes')
                ->orderBy('activation_number', 'desc')
                ->limit(30)
                ->get();
        } elseif ($profileFor == 'verified') {
            $recents = Member::where('gender', '!=', $gender)
                ->where('id', '!=', $id)
                ->where('profile_hide', '!=', 'yes')
                ->where('member_type', 'Verified')
                ->where('active', 'Yes')
                ->orderBy('activation_number', 'desc')
                ->limit(30)
                ->get();
        } elseif ($profileFor == 'viewed') {

            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.viewed_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->limit(30)
                ->get();
            // dd($recents);
        } elseif ($profileFor == 'shortlist') {
            $recents = Member::whereIn('id', function ($query) use ($id) {
                $query->select('profile_id')
                    ->from('short_listed')
                    ->where('member_id', $id);
            })
                ->where('active', 'Yes')
                ->where('profile_hide', '!=', 'yes')
                ->orderBy('activation_number', 'desc')
                ->get();
        } else {
            $partner_country = explode(',', $member->partner_country ?? '');
            $partner_religion = explode(',', $member->partner_religion ?? '');
            $partner_education = explode(',', $member->partner_education ?? '');
            $partner_mothertongue = explode(',', $member->partner_mothertongue ?? '');

            if ($member->partner_cast === 'Any') {
                $partner_cast = Member::where('cast', '!=', '')->distinct()->pluck('cast')->toArray();
            } else {
                $partner_cast = explode(',', $member->partner_cast ?? '');
            }

            $today = Carbon::today();

            $recents = Member::where('gender', '!=', $member->gender)
                ->where('id', '!=', $id)
                ->get()
                ->filter(function ($profile) use (
                    $member,
                    $today,
                    $partner_country,
                    $partner_religion,
                    $partner_cast,
                    $partner_education,
                    $partner_mothertongue
                ) {
                    $age = Carbon::parse($profile->birth_date_time)->diffInYears($today);

                    return $age >= $member->partner_age_from &&
                        $age <= $member->partner_age_to &&
                        $profile->height >= $member->partner_height_from &&
                        $profile->height <= $member->partner_height_to &&
                        in_array($profile->religion, $partner_religion) &&
                        in_array($profile->country_living_in, $partner_country) &&
                        in_array($profile->cast, $partner_cast) &&
                        in_array($profile->education, $partner_education) &&
                        in_array($profile->mother_tongue, $partner_mothertongue);
                });
        }

        $users = [];

        foreach ($recents as $key => $recent) {
            $birthDate = Carbon::parse($recent->birth_date_time);
            $diff = $birthDate->diff(Carbon::parse($today));
            $users[$key]['age_years'] = $diff->y;
            $users[$key]['age_months'] = $diff->m;
            if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                $users[$key]['photo'] = ProfilePhotoUrl::get($recent->photo);
            } elseif ($recent->gender === 'Male') {
                $users[$key]['photo'] = '/img/boy.jpg';
            } elseif ($recent->gender === 'Female') {
                $users[$key]['photo'] = '/img/girl.jpg';
            }
            if ($recent->member_type === 'Verified') {
                $users[$key]['member_type'] = '/img/verified.png';
                $users[$key]['mem_type'] = 'Yes';
            } else {
                $users[$key]['member_type'] = 'normal';
            }
            if ($recent->is_trusted === 'Trusted') {
                $users[$key]['is_trusted'] = '/img/trusted.png';
            } else {
                $users[$key]['is_trusted'] = 'No';
            }
            $users[$key] = array_merge($recent->toArray(), $users[$key]);
        }
        $stats = '';

        // dd($users);
        return view('dashboard.profile.view-all-profiles', compact(['users', 'profileFor', 'stats']));
    }

    public function all_recent_profiles(Request $request)
    {
        $id = Auth::guard('member')->user()->id;
        $today = Carbon::today()->format('Y-m-d');
        $profileFor = $request->input('profile');
        $baseview = $request->input('view');
        $loggedInUser = Member::find($id);
        if (! $loggedInUser) {
            return response()->json(['users' => []]);
        }
        $gender = $loggedInUser->gender;
        $offset = $request->input('offset', 0);
        $limit = 30;

        if ($profileFor == 'recent') {
            $recents = Member::where('gender', '!=', $gender)
                ->where('id', '!=', $id)
                ->where('profile_hide', '!=', 'yes')
                ->where('active', 'Yes')
                ->orderBy('activation_number', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        } elseif ($profileFor == 'verified') {
            $recents = Member::where('gender', '!=', $gender)
                ->where('id', '!=', $id)
                ->where('profile_hide', '!=', 'yes')
                ->where('member_type', 'Verified')
                ->where('active', 'Yes')
                ->orderBy('activation_number', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        } elseif ($profileFor == 'viewed') {
            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.viewed_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        } elseif ($profileFor == 'shortlist') {
            $recents = Member::whereIn('id', function ($query) use ($id) {
                $query->select('profile_id')
                    ->from('short_listed')
                    ->where('member_id', $id);
            })
                ->where('active', 'Yes')
                ->where('profile_hide', '!=', 'yes')
                ->orderBy('activation_number', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        } else {
            $member = $loggedInUser;
            $partner_country = explode(',', $member->partner_country ?? '');
            $partner_religion = explode(',', $member->partner_religion ?? '');
            $partner_education = explode(',', $member->partner_education ?? '');
            $partner_mothertongue = explode(',', $member->partner_mothertongue ?? '');

            if ($member->partner_cast === 'Any') {
                $partner_cast = Member::where('cast', '!=', '')->distinct()->pluck('cast')->toArray();
            } else {
                $partner_cast = explode(',', $member->partner_cast ?? '');
            }

            $today = Carbon::today();

            $recents = Member::where('gender', '!=', $member->gender)
                ->where('id', '!=', $id)
                ->skip($offset)
                ->take($limit)
                ->get()
                ->filter(function ($profile) use (
                    $member,
                    $today,
                    $partner_country,
                    $partner_religion,
                    $partner_cast,
                    $partner_education,
                    $partner_mothertongue
                ) {
                    $age = Carbon::parse($profile->birth_date_time)->diffInYears($today);

                    return $age >= $member->partner_age_from &&
                        $age <= $member->partner_age_to &&
                        $profile->height >= $member->partner_height_from &&
                        $profile->height <= $member->partner_height_to &&
                        in_array($profile->religion, $partner_religion) &&
                        in_array($profile->country_living_in, $partner_country) &&
                        in_array($profile->cast, $partner_cast) &&
                        in_array($profile->education, $partner_education) &&
                        in_array($profile->mother_tongue, $partner_mothertongue);
                });
        }

        $users = [];
        foreach ($recents as $key => $recent) {
            $birthDate = Carbon::parse($recent->birth_date_time);
            $diff = $birthDate->diff(Carbon::parse($today));

            $profile = $recent->toArray();
            $profile['age_years'] = $diff->y;
            $profile['age_months'] = $diff->m;

            if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                $profile['photo'] = ProfilePhotoUrl::get($recent->photo);
            } elseif ($recent->gender === 'Male') {
                $profile['photo'] = '/images/profile_photos/boy.jpg';
            } elseif ($recent->gender === 'Female') {
                $profile['photo'] = '/images/profile_photos/girl.jpg';
            }

            if ($recent->member_type === 'Verified') {
                $profile['member_type'] = 'https://himrishtey.com/img/verified.png';
                $profile['mem_type'] = 'Yes';
            } else {
                $profile['member_type'] = 'normal';
            }

            if ($recent->is_trusted === 'Trusted') {
                $profile['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
            } else {
                $profile['is_trusted'] = 'No';
            }

            $users[] = $profile;
        }

        // 👉 If request is AJAX, return JSON
        if ($request->ajax() && $request->has('view')) {
            return view('dashboard.profile.view-all-profiles', compact(['users', 'profileFor']));
        } else {
            return response()->json(['users' => $users]);
        }
    }

    public function stats_profiles(Request $request)
    {
        $id = Auth::guard('member')->user()->id;
        $today = Carbon::today()->format('Y-m-d');
        $profileFor = $request->input('profile');
        $loggedInUser = Member::find($id);
        if (! $loggedInUser) {
            return [];
        }
        $gender = $loggedInUser->gender;
        if ($profileFor == 'profile_viewed') {
            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.viewed_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->limit(30)
                ->get();
        } elseif ($profileFor == 'likes') {
            $recents = Member::select('members.*', 'profile_like.like_profile_id')
                ->join('profile_like', 'members.id', '=', 'profile_like.user_id')
                ->where('profile_like.like_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_like.id', 'desc')
                ->limit(30)
                ->get();
        } elseif ($profileFor == 'contacts') {
            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.member_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->limit(30)
                ->get();
        } else {
            $recents = [];
        }

        $users = [];

        foreach ($recents as $key => $recent) {
            $birthDate = Carbon::parse($recent->birth_date_time);
            $diff = $birthDate->diff(Carbon::parse($today));
            $users[$key]['age_years'] = $diff->y;
            $users[$key]['age_months'] = $diff->m;
            if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                $users[$key]['photo'] = ProfilePhotoUrl::get($recent->photo);
            } elseif ($recent->gender === 'Male') {
                $users[$key]['photo'] = '/images/profile_photos/boy.jpg';
            } elseif ($recent->gender === 'Female') {
                $users[$key]['photo'] = '/images/profile_photos/girl.jpg';
            }
            if ($recent->member_type === 'Verified') {
                $users[$key]['member_type'] = 'https://himrishtey.com/img/verified.png';
                $users[$key]['mem_type'] = 'Yes';
            } else {
                $users[$key]['member_type'] = 'normal';
            }
            if ($recent->is_trusted === 'Trusted') {
                $users[$key]['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
            } else {
                $users[$key]['is_trusted'] = 'No';
            }
            $users[$key] = array_merge($recent->toArray(), $users[$key]);
        }
        $stats = 'stats';
        if ($profileFor == 'interest') {
            return response()->json(['redirect' => url('interest-box')]);
        } else {
            return view('dashboard.profile.view-all-profiles', compact(['users', 'profileFor', 'stats']));
        }
    }

    public function all_stats_profiles(Request $request)
    {
        $id = Auth::guard('member')->user()->id;
        $today = Carbon::today()->format('Y-m-d');
        $profileFor = $request->input('profile');
        $baseview = $request->input('view');
        $loggedInUser = Member::find($id);
        if (! $loggedInUser) {
            return response()->json(['users' => []]);
        }
        $gender = $loggedInUser->gender;
        $offset = $request->input('offset', 0);
        $limit = 30;

        if ($profileFor == 'profile_viewed') {
            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.viewed_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->skip($offset)
                ->limit($limit)
                ->get();
        } elseif ($profileFor == 'likes') {
            $recents = Member::select('members.*', 'profile_like.like_profile_id')
                ->join('profile_like', 'members.id', '=', 'profile_like.user_id')
                ->where('profile_like.like_profile_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_like.id', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        } else {
            $recents = Member::select('members.*', 'profile_viewed.viewed_profile_id')
                ->join('profile_viewed', 'members.id', '=', 'profile_viewed.member_id')
                ->where('profile_viewed.member_id', $id)
                ->where('members.active', 'Yes')
                ->where('members.profile_hide', '!=', 'yes')
                ->orderBy('profile_viewed.id', 'desc')
                ->skip($offset)
                ->take($limit)
                ->get();
        }

        $users = [];
        foreach ($recents as $key => $recent) {
            $birthDate = Carbon::parse($recent->birth_date_time);
            $diff = $birthDate->diff(Carbon::parse($today));

            $profile = $recent->toArray();
            $profile['age_years'] = $diff->y;
            $profile['age_months'] = $diff->m;

            if (! empty($recent->photo) && $recent->photo_approved === 'Yes') {
                $profile['photo'] = ProfilePhotoUrl::get($recent->photo);
            } elseif ($recent->gender === 'Male') {
                $profile['photo'] = '/images/profile_photos/boy.jpg';
            } elseif ($recent->gender === 'Female') {
                $profile['photo'] = '/images/profile_photos/girl.jpg';
            }

            if ($recent->member_type === 'Verified') {
                $profile['member_type'] = 'https://himrishtey.com/img/verified.png';
                $profile['mem_type'] = 'Yes';
            } else {
                $profile['member_type'] = 'normal';
            }

            if ($recent->is_trusted === 'Trusted') {
                $profile['is_trusted'] = 'https://himrishtey.com/img/trusted.png';
            } else {
                $profile['is_trusted'] = 'No';
            }

            $users[] = $profile;
        }

        // 👉 If request is AJAX, return JSON
        if ($request->ajax() && $request->has('view')) {
            return view('dashboard.profile.view-all-profiles', compact(['users', 'profileFor']));
        } else {
            return response()->json(['users' => $users]);
        }
    }
}
