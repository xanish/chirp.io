<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (Auth::user()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:30',
            'email' => 'required|string|email|max:40|exists:users',
            'profile_image' => 'dimensions:min_width=500,min_height=500|image|max:5000',
            'profile_banner' => 'dimensions:min_width=900,min_height=400|image|max:5000',
        ];
    }

    /**
     * Get the validation messages for the specified rules.
     *
     * @return array
     */
    public function messages()
    {
         return [
              'profile_image.dimensions' => 'The avatar must be atleast 500x500 pixels',
              'profile_banner.dimensions' => 'The banner must be atleast 900x400 pixels',
         ];
    }
}
