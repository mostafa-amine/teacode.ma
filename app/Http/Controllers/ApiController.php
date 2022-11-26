<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getEvents(Request $request)
    {
        $dates = array_values($request->all());
        return Event::getEvents($dates);
    }

    public function getNextEvent(Request $request)
    {
        $event = Event::getNextEvent();
        return ['event' => $event];
    }
}
