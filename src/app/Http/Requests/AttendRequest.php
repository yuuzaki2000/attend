<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendRequest extends FormRequest
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
            'workStartTime' =>'required',
            'workEndTime' => 'required',
            'breakStartTime' => 'required',
            'breakEndTime' => 'required',
        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            if($this->input('workStartTime') > $this->input('workEndTime')){
                $validator->errors()->add('workStartWorkEndTemporalOrder', '出勤時間もしくは退勤時間が不適切な値です');
            }
            
            if($this->input('breakStartTime') > $this->input('workStartTime')){
                $validator->errors()->add('workStartBreakStartTemporalOrder', '休憩時間が不適切な値です');
            }
        });
    }
}
