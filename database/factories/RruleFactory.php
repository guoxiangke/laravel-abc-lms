<?php

use Faker\Generator as Faker;
use App\Models\Rrule;
use App\Models\Order;

$factory->define(Rrule::class, function (Faker $faker) {
    $rrules =[
        // "DTSTART:20190323T180000Z\nRRULE:FREQ=WEEKLY;COUNT=3;INTERVAL=1;WKST=MO;BYDAY=MO",
        // "DTSTART:20190324T180000Z\nRRULE:FREQ=WEEKLY;COUNT=3;INTERVAL=1;WKST=MO;BYDAY=MO",
        // "DTSTART:20190325T180000Z\nRRULE:FREQ=WEEKLY;COUNT=3;INTERVAL=1;WKST=MO;BYDAY=TU",
        // "DTSTART:20190326T180000Z\nRRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=WE",
        "DTSTART:20190409T180000Z\nRRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=MO,TU,WE,TH,FR,SA,SU",
        // "DTSTART:20190323T180000Z\nRRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=1;WKST=MO;BYDAY=MO,WE,FR",
        // "DTSTART:20190323T180000Z\nRRULE:FREQ=WEEKLY;COUNT=10;INTERVAL=1;WKST=MO;BYDAY=SA,SU",
        // "DTSTART:20190323T180000Z\nRRULE:FREQ=WEEKLY;COUNT=20;INTERVAL=1;WKST=MO;BYDAY=FR",
    ];
    $rrule = $rrules[rand(0, count($rrules)-1)];
    $rruleArray = Rrule::buildRrule($rrule);
    return array_merge($rruleArray, [
        'type' => Rrule::TYPE_SCHEDULE, //rand(0,1),//'AOL','SCHEDULE',
        'order_id' => function () {
            return factory(Order::class)
                ->create()
                ->id;
        },
    ]);
});
