<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'url',
        'start_date',
        'start_time',
        'end_time',
        'end_date',
        'background_color',
        'text_color',
        'days_of_week',
        'extended_props',
        'is_private'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'days_of_week' => 'array',
        'extended_props' => 'array',
    ];
    
    public static function getNextEvent($only_public = false)
    {
        $events = Event::where(function ($q){
            $q->whereNull('days_of_week')
                ->whereDate('start_date', '>=', today())
                // ->whereTime('start_time', '>=', now())
                ;
            })
            ->orWhere(function ($q) {
                $q->whereNotNull('days_of_week')
                    ->where('end_date', '>=', today())
                    // ->whereTime('end_time', '>=', now())
                    ;
            });
        if ($only_public) {
            $events = $events->where('is_private', 0);
        }
        $events = $events->select(['id', 'title', 'start_date', 'start_time', 'days_of_week', 'url'])->get();
        $events = collect($events)->sortBy(function ($e){
            if ($e->days_of_week) {
                $diff = now()->diffInDays($e->start_date, false);
                if ($diff > 0) {
                    $e->_start_date = $e->start_date->toDateString() . ' ' . $e->start_time;
                } else {
                    $diff = abs($diff);
                    $days = $diff % 7 == 0 ? 0 : 7 - $diff % 7;
                    $e->_start_date = now()->addDays($days)->toDateString() . ' ' . $e->start_time;
                }
            } else {
                $e->_start_date = $e->start_date->toDateString() . ' ' . $e->start_time;
            }
            unset($e->days_of_week);
            return $e->_start_date;
        })->values();
        return count($events) > 0 ? $events[0] : null;
    }
    
    public static function getEvents($dates)
    {
        $_events = Event::where(function ($q) use ($dates) {
            $q->where('start_date', '>=', $dates[0])
            ->where('start_date', '<=', $dates[1]);
        })->orWhere(function ($q) use ($dates) {
            $q->where('end_date', '>=', $dates[0])
            ->where('end_date', '<=', $dates[1]);
        })->orWhere(function ($q) use ($dates) {
            $q->where('start_date', '<=', $dates[0])->where('start_date', '<=', $dates[1])
            ->where('end_date', '>=', $dates[0])->where('end_date', '>=', $dates[1]);
        })
        ->get();

        $events = $_events->map(function ($_e) {
            $eventProps = [
                'backgroundColor' => $_e->background_color,
                'textColor' => $_e->text_color,
                'url' => $_e->url,
                'title' => $_e->title,
                'classNames' => 'cursor-pointer',
                'extendedProps' => (array)$_e->extended_props
            ];
            if ($_e->days_of_week) {
                $e = (object) array_merge($eventProps, [
                    'startTime' => $_e->start_time,
                    'endTime' => $_e->end_time,
                    'startRecur' => $_e->start_date,
                    'endRecur' => $_e->end_date,
                    'daysOfWeek' => $_e->days_of_week,
                ]);
            } else {
                $e = (object) array_merge($eventProps, [
                    'start' => $_e->start_date->toDateString() . ' ' . $_e->start_time,
                    'end' => $_e->end_date->toDateString() . ' ' . $_e->end_time,
                ]);
            }
            return $e;
        });

        return $events;
    }
}
