<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
                $events = Event::withTrashed()->orderBy('deleted_at')->orderBy('start_date', 'desc')->orderBy('id');
                return DataTables::eloquent($events)->make(true);
            }
            return view('pages.admin.events', ['menu' => $menu, 'data' => $data]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateEvent(Request $request)
    {
        // $data['end_date'] = \Carbon\Carbon::createFromFormat('Y-m-d', $data['end_date']);
        // $event = Event::create($dataToStore);
        // \Carbon\Carbon::createFromFormat('Y-m-d', $data['start_date'])
        try {
            $event_id = $request->get('event_id');
            $event = null;
            if ($event_id) {
                $event = Event::withTrashed()->find($event_id);
            }
            if ($request->has('delete')) {
                $event->delete();
                return ['message' => 'Deleted Successfully', 'event' => $event];
            }
            if ($request->has('restore')) {
                $event->restore();
                return ['message' => 'Restored Successfully', 'event' => $event];
            }
            if ($request->has('duplicate')) {
                $event = $event->replicate();
                $event->save();
                return ['message' => 'Replicated Successfully', 'event' => $event];
            }
            $data = $request->all();
            $extended_props = extractExtendedProps($request->get('extended_props'));
            $dataToUpdate = [
                'start_time' =>  isset($data['start_time']) ? $data['start_time'] : $event?->start_time,
                'end_time' => isset($data['end_time']) ? $data['end_time'] : $event?->end_time,
                'start_date' => isset($data['start_date']) ? \Carbon\Carbon::createFromFormat('Y-m-d', $data['start_date']) : $event?->start_date,
                'end_date' => isset($data['end_date']) ? \Carbon\Carbon::createFromFormat('Y-m-d', $data['end_date']) : $event?->end_date,
                'days_of_week' => isset($data['days_of_week']) ? $data['days_of_week'] : $event?->days_of_week,
                'background_color' => isset($data['background_color']) ? $data['background_color'] : $event?->background_color,
                'text_color' => isset($data['text_color']) ? $data['text_color'] : $event?->text_color,
                'url' => isset($data['url']) ? $data['url'] : $event?->url,
                'title' => isset($data['title']) ? $data['title'] : $event?->title ,
                'is_private' => isset($data['is_private']) ? ($data['is_private'] == 'on' ? true : false) : ($event ? $event->is_private : false),
                'extended_props' => $extended_props ? json_decode(json_encode($extended_props)) : $event?->extended_props,
            ];
            if (key_exists('days_of_week', $data) && $data['days_of_week'] != null) {
                $dataToUpdate['days_of_week'] = array_map(function ($i) {
                    return (int)$i;
                }, explode(',', $data['days_of_week']));
            }
            if ($event) {
                $event->update($dataToUpdate);
                $data = ['message' => 'Event Updated', 'event' => $event];
            } else {
                $event = Event::create($dataToUpdate);
                $data = ['message' => 'Event Created', 'event' => $event];
            }
            return $data;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
