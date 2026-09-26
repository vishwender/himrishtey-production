<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Education;
use App\Website\Models\Member;
use App\Website\Models\Occupation;
use App\Website\Services\ProfilePhotoStorage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    public function checkMemberExist(Request $request)
    {
        if ($request->filled('email')) {
            if (Member::where('email', $request->email)->exists()) {
                return response()->json([
                    'exists' => true,
                    'field' => 'email',
                    'message' => 'Email already registered.',
                ]);
            }
        }

        if ($request->filled('mobile_number')) {
            if (Member::where('mobile_number', $request->mobile_number)->exists()) {
                return response()->json([
                    'exists' => true,
                    'field' => 'mobile_number',
                    'message' => 'Mobile number already registered.',
                ]);
            }
        }

        return response()->json([
            'exists' => false,
        ]);
    }

    public function completeProfile(Request $request)
    {
        // Show page
        if ($request->isMethod('get')) {
            $educations = Education::orderBy('education')->get();
            $occupations = Occupation::where('status', '1')->orderBy('occupation')->get();

            return view('dashboard.profile.complete-profile', compact('educations', 'occupations'));
        }
        // Update profile
        $member = Auth::guard('member')->user();
        $id = Auth::guard('member')->id();

        if (! $member) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
        // Allowed fields
        $allowedFields = [
            'birth_date_time',
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
            'cast',
            'horoscope_needed',
            'gotra',
            'education',
            'employed_in',
            'organization_name',
            'job_location',
            'occupation',
            'annual_income',
        ];
        // Accept the former registration field name from older form submissions.
        if (! $request->has('annual_income') && $request->has('income')) {
            $request->merge(['annual_income' => $request->input('income')]);
        }
        $data = $request->only($allowedFields);
        if ($request->filled('time_of_birth')) {
            // Get existing date
            $date = Carbon::parse($member->birth_date_time)->format('Y-m-d');
            // Combine with new time
            $member->birth_date_time = $date.' '.$request->time_of_birth;
        }

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
            app(ProfilePhotoStorage::class)->save($member, $request->file('photo'));
        }
        $member->fill($data);
        $member->profile_completed = $member->profileCompletionPercentage();
        $member->saveOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'profile_completed' => $member->profileCompletionPercentage(),
        ]);
    }

    public function changePassword()
    {
        return view('dashboard.profile.change-password');
    }

    public function updatePassword(Request $request)
    {
        $id = Auth::guard('member')->id();
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }
        $member = Member::findOrFail($id);
        if (! hash_equals((string) $member->password, $request->current_password)) {
            return response()->json([
                'errors' => ['current_password' => ['Current password is incorrect']],
            ], 422);
        }
        $member->password = $request->new_password;
        $member->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully!',
        ]);
    }
}
