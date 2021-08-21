<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WovenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "customer_id" => "required",
            "label" => "required",
            "date" => "required",
            "salesrep_id" => "required",
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            "customer_id.required"  => "Select customer is required",
            "label.required"        => "Label is required",
            "date.required"         => "Date is required",
            "salesrep_id.required"  => "Select sales rep is required",
        ];
    }
}
