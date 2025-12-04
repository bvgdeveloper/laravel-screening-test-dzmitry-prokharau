<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Workshop;
use Carbon\Carbon;

class EventController extends Controller
{
    public function getWarmupEvents()
    {
        return Event::with('workshops')->get();
    }

    public function getEventsWithWorkshops()
    {
        return Event::with('workshops')->get();
    }

    public function getFutureEventsWithWorkshops()
    {
        $now = Carbon::now();

        $eventIds = Workshop::select('event_id')
            ->groupBy('event_id')
            ->havingRaw('MIN(start) > ?', [$now])
            ->pluck('event_id');

        return Event::whereIn('id', $eventIds)
            ->with('workshops')
            ->get();
    }
}
