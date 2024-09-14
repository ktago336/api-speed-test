<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $melory_birthdays = $serverData->Melory_birthdays == 'even' ? 0 : 1;
        $days_group = $serverData->days_group;
        $availableNames = $serverData->names;
        $number_of_gifts_per_days_group = $serverData->number_of_gifts_per_days_group;

        $days = collect($input['data']);
        $daysWithKey = $days->keyBy('date')->all();

        $dates = collect($days->pluck('date')->toArray())->sortBy(function ($date){
            return Carbon::parse($date);
        });

        $firstDate = $dates->first()??null;
        $lastDate = $dates->first??null;
        $groups = [];
        foreach ($dates as $date) {
            $day = Carbon::parse($date);

            if (Carbon::parse($firstDate)->diffInDays($day)>$days_group) {
                $groups[] = ['from' => $firstDate, 'to' => $lastDate];
                $firstDate = $date;
            }

            $lastDate = $date;
        }
        $response = [];

        foreach ($daysWithKey as $date=>$day) {
            foreach ($groups as $group){
                if (!isset($response[$group['from'].' '.$group['to']]['birthdays'])){
                    $response[$group['from'].' '.$group['to']]['birthdays'] = [];
                }

                if (Carbon::parse($date)->between($group['from'], $group['to'])) {
                    foreach ($day['events'] as $event) {
                        if (str_contains($event, 'Bad day')) {
                            $response[$group['from'].' '.$group['to']]['was_bad_day'] = true;
                        }
                    }
                    $isMeloryBirthday = Carbon::parse($date)->day % 2 == $melory_birthdays;

                    foreach ($availableNames as $name) {
                        foreach ($day['events'] as $event) {
                            if (str_contains($event, $name)) {
                                isset($response[$group['from'].' '.$group['to']]['birthdays'][$name])
                                    ? $response[$group['from'].' '.$group['to']]['birthdays'][$name]++
                                    : $response[$group['from'].' '.$group['to']]['birthdays'][$name] = 1;
                            }
                        }

                    }
                    isset($response[$group['from'].' '.$group['to']]['birthdays']['Melory'])
                    ? $response[$group['from'].' '.$group['to']]['birthdays']['Melory']+=(int)$isMeloryBirthday
                    : $response[$group['from'].' '.$group['to']]['birthdays']['Melory'] = (int)$isMeloryBirthday;
                }
            }
        }

        foreach ($response as $dateRange=>$group){
            $count = 0;
            foreach ($group['birthdays'] as $name=>$birthdaysCount){
                $count += $birthdaysCount;
            }
            $response[$dateRange]['enough_gifts'] = $count <= $number_of_gifts_per_days_group;
            if (!isset($group['was_bad_day'])) {
                $response[$dateRange]['was_bad_day'] = false;
            }
            if ($response[$dateRange]['was_bad_day']) {
                $response[$dateRange]['birthdays'] = new \stdClass();
            }

        }

        return $response;

    }
}
