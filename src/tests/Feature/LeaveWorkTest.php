<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Worktime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LeaveWorkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_leave_wprk_button_works_well()
    {
        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);


        $baseDate = Carbon::now();
        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');

        $status_data = [
                'user_id' => Auth::user()->id,
                'date' => $current_date,
                'content' => '出勤中',
        ];
        Status::create($status_data);

        $worktime = new Worktime();
        $worktime->date = $current_date;
        $worktime->user_id = Auth::user()->id;
        $worktime->start_time = Carbon::create(2025,10,30,9,0,0)->format('H:i');
        $worktime->end_time = Carbon::create(2025,10,30,17,0,0)->format('H:i');
        $worktime->save();
        $worktimeId = $worktime->id;

        $data = [
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);
        $response->assertSee('退勤</button>', false);

        $response = $this->post('/work/end', $data);
        $this->followRedirects($response)->assertSeeText('退勤済');
    }

    public function test_we_can_confirm_work_end_time_at_attendance_list(){
        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($user);


        $current_time = Carbon::now()->format('H:i');
        $current_date = Carbon::now()->format('Y-m-d');

        $status_data = [
                'user_id' => Auth::user()->id,
                'date' => $current_date,
                'content' => '勤務外',
        ];
        Status::create($status_data);

        $worktime = new Worktime();
        $worktime->date = $current_date;
        $worktime->user_id = Auth::user()->id;
        $worktime->start_time = Carbon::create(2025,10,30,9,0,0)->format('H:i');
        $worktime->end_time = Carbon::create(2025,10,30,17,0,0)->format('H:i');
        $worktime->save();
        $worktimeId = $worktime->id;

        $data = [
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);

        $response = $this->post('/work/start');
        $response = $this->post('/work/end', $data);

        $worktime->update([
            'start_time' => Carbon::now()->copy()->setTime(9,0,0),
            'end_time' => Carbon::now()->copy()->setTime(17,0,0),
        ]);
        $response = $this->get('/attendance/list');
        $response->assertSeeText('17:00');
    }
}
