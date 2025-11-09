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

class StartWorkTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    use DatabaseMigrations;

    public function test_start_work_button_works_well()
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

        Worktime::create([
            'id' => 1,
            'date' => $current_date,
            'user_id' => Auth::user()->id,
            'start_time' => Carbon::now()->copy()->setTime(9,0,0)->format('H:i'),
            'end_time' => Carbon::now()->copy()->setTime(17,0,0)->format('H:i'),
        ]);

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => 1,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);

        $response->assertSee('出勤</button>', false);
        
        $response = $this->post('/work/start');
        $this->followRedirects($response)->assertSeeText('出勤中');
    }

    public function test_we_start_work_once_per_a_day(){
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
        $worktime->start_time = Carbon::now()->copy()->setTime(9,0,0)->format('H:i');
        $worktime->end_time = Carbon::now()->copy()->setTime(17,0,0)->format('H:i');
        $worktime->save();
        $worktimeId = $worktime->id;

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);

        $response->assertDontSee('出勤</button>', false);
    }

    public function test_we_can_find_start_work_time_at_attendance_list(){
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
        $worktime->start_time = Carbon::now()->copy()->setTime(9,0,0)->format('H:i');
        $worktime->end_time = Carbon::now()->copy()->setTime(17,0,0)->format('H:i');
        $worktime->save();
        $worktimeId = $worktime->id;

        $data = [
            'current_time' => $current_time,
            'current_date' => $current_date,
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $data);
        $response->assertStatus(200);

        $response = $this->post('/work/start');
        $response = $this->get('/attendance/list');
        $response->assertStatus(200);
        $response->assertSeeText(Carbon::now()->copy()->setTime(9,0,0)->format('H:i'));
    }
}
