<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class CommentPost extends FormRequest
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
            //
            'moment_id'=>"required",
            'content'=>'required|max:255'
        ];
    }
    public function messages()
    {
        return [
            'moment_id.required'=>"参数错误！",
            'content.required'=>'内容不允许为空！',
            'content.max'=>'内容不得超过255个字符！'
        ];
    }
    protected function formatErrors(Validator $validator)
    {
        $message = $validator->errors()->first();
        return ['message'=>$message];
    }
}
