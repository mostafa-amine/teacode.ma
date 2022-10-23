<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\Datatables;

class EventController extends Controller
{

    public function calendar(Request $request)
    {
        $data = new \stdClass;
        $data->title = 'TeaCode | Calendar';
        $menu = json_decode(\File::get(base_path() . '/database/data/admin/menu.json'));
        return view('pages.admin.calendar', ['data' => $data, 'menu' => $menu]);
    }

    public function getEvents(Request $request)
    {
        try {
            $data = new \stdClass;
            $data->title = 'TeaCode | Events list';
            $menu = json_decode(\File::get(base_path() . '/database/data/admin/menu.json'));
            if ($request->has('api')) {
                return Datatables::eloquent(Event::orderBy('start_date', 'desc'))->make(true);
            }
            return view('pages.admin.events', ['menu' => $menu, 'data' => $data]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateEvent(Request $request)
    {
        dd($request->all());
        // $data['end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $data['end_date']);
        // $event = Event::create($dataToStore);
        // \Carbon\Carbon::createFromFormat('Y-m-d', $data['start_date'])
        try {
            $event_id = $request->get('event_id');
            $event = null;
            if ($event_id) {
                $event = Event::find($event_id);
            }
            $data = $request->all();
            $extended_props = extractExtendedProps($request->get('extended_props'));
            $dataToUpdate = [
                'start_time' =>  !$event ? $data['start_time'] : $event->start_time,
                'end_time' => !$event ? $data['end_time'] : $event->end_time,
                'start_date' => $data['start_date'] ? \Carbon\Carbon::createFromFormat('Y-m-d', $data['start_date']) : $event->start_date,
                'end_date' => $data['end_date'] ? \Carbon\Carbon::createFromFormat('Y-m-d', $data['end_date']) : $event->end_date,
                'days_of_week' => !$event ? $data['days_of_week'] : $event->days_of_week,
                'background_color' => !$event ? $data['background_color'] : $event->background_color,
                'text_color' => !$event ? $data['text_color'] : $event->text_color,
                'url' => !$event ? $data['url'] : $event->url,
                'title' => !$event ? $data['title'] : $event->title ,
                'is_private' => !$event ? (isset($data['is_private']) ? true : false) : $event->is_private,
                'extended_props' => $extended_props ? json_decode(json_encode($extended_props)) : $event->extended_props,
            ];
            if (key_exists('days_of_week', $data) && $data['days_of_week'] != null) {
                $dataToUpdate['days_of_week'] = array_map(function ($i) {
                    return (int)$i;
                }, explode(',', $data['days_of_week']));
            }
            if ($event) {
                $event->update($dataToUpdate);
            } else {
                $event = Event::create($dataToUpdate);
            }
            return back();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroyEvent(Request $request, Event $event)
    {
        try {
            $event->delete();
            return ['event' => $event];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
