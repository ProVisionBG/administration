<?php

namespace ProVision\Administration\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AjaxQuickSwichRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'table' => 'required',
            'id' => 'required|integer',
            'field' => 'required',
            'state' => 'required|in:true,false'
        ];
    }
}
