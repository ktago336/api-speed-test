Body data is object. 
data is array of days with events of birthdays, aware of "Bad days"!<br><br>

serverData.json is object. Melory_birthdays is enum ("odd"|"even"), representing Melory birthdays, each even or odd day of month.
days_group is number of days to group birthdays (if days_group==7, means grouping birthdays for weeks,
starting from most early day). number_of_gifts_per_days_group represents number of gifts available for each day group
(if there are 7 bithdays at one week and number_of_gifts_per_days_group == 5 means there are not enough gifts for this week)

response structure must be<br><br>
<pre>
{
  "day_groups":{
    "2024-01-01 2024-01-07":{
      "birthdays":{"Melory":1, "Alice":2, "Bob":0}
      "enough_gifts":true,
      "was_bad_day": false
    }
  }
}

  
</pre>

Remember, data[n].events can contain any string, need to check if name Alice|Bob is in the string.<pre>
If there a bad day in the days_group, the full group fails its burthdays and "birtdays" propery mus be {"Melory":0, "Alice":0, "Bob":0}

Melory birthdays are only in those days, which Bob or Alice have birthdays
