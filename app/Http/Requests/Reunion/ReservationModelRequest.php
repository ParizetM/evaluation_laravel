<?php

namespace App\Http\Requests\Reunion;

use Auth;
use Illuminate\Foundation\Http\FormRequest;

class ReservationModelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return env('APP_ENV') == 'testing'
    //         ? true
    //         : Auth::user()->can('reservation-create');
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @example https://laravel.com/docs/validation#available-validation-rules
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [];
        $rules['xxx'] = '';

        return $rules;
    }

    /**
     * @return array<string>
     */
    public function messages()
    {
        return [];
    }
}
