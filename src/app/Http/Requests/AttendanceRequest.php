<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'workStartTime' =>'required|date_format:H:i',
            'workEndTime' => 'required|date_format:H:i',
            'breakStartTime' => 'array',
            'breakStartTime.*' => 'required|date_format:H:i',
            'breakEndTime' => 'array',
            'breakEndTime.*' => 'required|date_format:H:i',
            'remarks' => 'required',
        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            if($this->input('workStartTime') > $this->input('workEndTime')){
                $validator->errors()->add('workStartWorkEndTemporalOrder', '出勤時間もしくは退勤時間が不適切な値です');
            }
            
            if($this->input('breakStartTime.*') > $this->input('workStartTime') || $this->input('breakStartTime.*') > $this->input('workEndTime')){
                $validator->errors()->add('workStartBreakStartTemporalOrder', '休憩時間が不適切な値です');
            }

            if($this->input('breakEndTime.*')> $this->input('workEndTime')){
                $validator->errors()->add('workEndBreakEndTemporalOrder', '休憩時間もしくは退勤時間が不適切な値です');
            }
        });
    }
}
