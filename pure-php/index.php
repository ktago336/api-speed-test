<?php
$input = $_POST;
$data = json_decode(file_get_contents('input.json'));

$days_group = $data->days_group;
$number_of_gifts_per_days_group = $data->number_of_gifts_per_days_group;
$Melory_birthdays = $data->Melory_birthdays;


