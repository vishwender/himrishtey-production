<?php

namespace App\Website\Http\Requests;

use App\Website\Models\ContactMessage;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:190'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+()\-\s]+$/'],
            'profile_id' => ['nullable', 'string', 'max:50'],
            'subject' => ['required', 'string', Rule::in(ContactMessage::SUBJECTS)],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
            'website' => ['nullable', 'max:0'],
            'captcha' => ['bail', 'required', 'integer', function ($attribute, $value, $fail) {
                $challenge = $this->session()->get('contact_captcha');
                if (! $challenge || $challenge['expires_at'] < now()->timestamp || (int) $value !== $challenge['answer']) {
                    $fail('Please answer the security question correctly. Refresh the page if it has expired.');
                }
            }],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Please enter a valid phone number.',
            'website.max' => 'Your message could not be submitted.',
            'captcha.required' => 'Please answer the security question.',
            'captcha.integer' => 'Please enter a whole number.',
        ];
    }
}
