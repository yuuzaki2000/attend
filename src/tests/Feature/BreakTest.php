<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Status;
use App\Models\Worktime;
use App\Models\Breaktime;

class BreakTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */


    use DatabaseMigrations;

    public function test_break_in_button_works_well()
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
        $response->assertSee('休憩入</button>', false);

        $response = $this->post('/break/in', $data);
        $this->assertDatabaseHas('statuses', [
            'user_id' => Auth::user()->id,
            'date' => $current_date,
            'content' => '休憩中',
        ]);
        $this->followRedirects($response)->assertSeeText('休憩中');
    }

    public function test_we_take_break_many_times() {
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

        $work_data = [
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $work_data);
        $response->assertStatus(200);
        $response->assertSee('休憩入</button>', false);

        $response = $this->post('/break/in', $work_data);

        $breaktime = new Breaktime();
        $breaktime->start_time = $current_time;
        $breaktime->end_time = null;
        $breaktime->worktime_id = $worktimeId;
        $breaktime->save();
        $breaktimeId = $breaktime->id;

        $break_data = [
            'breaktimeId' => $breaktimeId,
        ];

        $response = $this->post('/break/out', $break_data);
        $this->followRedirects($response)->assertSee('休憩入</button>', false);
    }

    public function test_break_out_button_works_well(){
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
        //同じ日の9:00にしたい
        $worktime->start_time = Carbon::create(2025,10,30,9,0,0)->format('H:i');
        $worktime->end_time = Carbon::create(2025,10,30,17,0,0)->format('H:i');
        $worktime->save();
        $worktimeId = $worktime->id;

        $work_data = [
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $work_data);
        $response->assertStatus(200);
        $response->assertSee('休憩入</button>', false);

        $response = $this->post('/break/in', $work_data);

        $breaktime = new Breaktime();
        $breaktime->start_time = $current_time;
        $breaktime->end_time = null;
        $breaktime->worktime_id = $worktimeId;
        $breaktime->save();
        $breaktimeId = $breaktime->id;

        $break_data = [
            'breaktimeId' => $breaktimeId,
        ];

        $response = $this->post('/break/out', $break_data);
        $this->assertDatabaseHas('statuses', [
            'user_id' => Auth::user()->id,
            'date' => $current_date,
            'content' => '出勤中',
        ]);
        $this->followRedirects($response)->assertSeeText('出勤中');
    }

    public function test_we_leave_break_many_times(){
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

        $work_data = [
            'worktimeId' => $worktimeId,
        ];

        $response = $this->get('/attendance', $work_data);
        $response->assertStatus(200);
        $response->assertSee('休憩入</button>', false);

        $response = $this->post('/break/in', $work_data);

        $breaktime = new Breaktime();
        $breaktime->start_time = $current_time;
        $breaktime->end_time = null;
        $breaktime->worktime_id = $worktimeId;
        $breaktime->save();
        $breaktimeId = $breaktime->id;

        $break_data = [
            'breaktimeId' => $breaktimeId,
        ];

        $response = $this->post('/break/out', $break_data);
        $response = $this->post('/break/in', $work_data);
        $this->followRedirects($response)->assertSee('休憩戻</button>', false);
    }

    public function we_can_confirm_break_time_at_attendance_list(){

    }
}
