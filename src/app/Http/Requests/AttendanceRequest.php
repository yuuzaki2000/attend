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
            'remarks' => 'required|string|max:255',
        ];
    }

    public function withValidator($validator){
        $validator->after(function($validator){
            $workStart = $this->input('workStartTime');
            $workEnd = $this->input('workEndTime');
            if($workStart > $workEnd){
                $validator->errors()->add('workStartWorkEndTemporalOrder', '出勤時間もしくは退勤時間が不適切な値です');
            }

            $breakStarts = $this->input('breakStartTime', []);
            $breakEnds = $this->input('breakEndTime', []);

            foreach ($breakStarts as $index => $start) {
                if ($start < $workStart || $start > $workEnd) {
                    $validator->errors()->add("breakStartTime.$index", "休憩時間が不適切な値です");
                }
            }

            foreach ($breakEnds as $index => $end) {
                if ($end > $workEnd) {
                    $validator->errors()->add("breakEndTime.$index", "休憩時間もしくは退勤時間が不適切な値です");
                }
            }
        });
    }
}
