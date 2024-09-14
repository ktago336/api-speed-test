<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {

        return '';
    }

    public function testPost(Request $request){
        $input = $request->input();
        $serverData = json_decode(file_get_contents(app_path('serverData.json')));
        $melory_birthdays = $serverData->Melory_birthdays;
        $days_group = $serverData->days_group;
        $number_of_gifts_per_days_group = $serverData->number_of_gifts_per_days_group;




        return $input;
    }
}
