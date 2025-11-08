<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Status;
use App\Models\Worktime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class StatusConfirmationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    use DatabaseMigrations;

    public function test_attendance_off()
    {

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
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);
        $response->assertSeeText("勤務外");
    }

    public function test_attendance_atd(){

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
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);
        $response->assertSeeText("出勤中");
    }

    public function test_attendance_brk(){
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
                'content' => '休憩中',
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
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);
        $response->assertSeeText("休憩中");
    }

    public function test_attendance_end(){
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
                'content' => '退勤済',
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
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);
        $response->assertSeeText("退勤済");
    }
}