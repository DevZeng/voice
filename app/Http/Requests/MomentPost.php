<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class MomentPost extends FormRequest
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
            'content'=>'bail|required|max:255',
            'images'=>'nullable',
            'type'=>'required'
        ];
    }
    public function messages()
    {
         return [
             'content.required'=>"内容不允许为空！",
             'content.max'=>'内容不得超过255个字符！',
             'type.required'=>'参数错误！'
         ];
    }
    protected function formatErrors(Validator $validator)
    {
        $message = $validator->errors()->first();
        return ['message'=>$message];
    }
}
