<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class School2 extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//这里一定要改成true默认是false 不然会报403错误
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'original_name' => 'required',
            'original_howtime' => 'required',
            'original_class' => 'required',
            'original_time' => 'required',
            'original_author' => 'required',
            'original_info' => 'required',

        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw(new HttpResponseException(json_fail('参数错误', $validator->errors()->all(), 422)));
    }

}
