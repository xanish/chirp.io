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
            'profile_image' => 'image|max:5000',
            'profile_banner' => 'image|max:5000',
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
