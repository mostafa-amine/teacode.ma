@extends('pages.admin.app')
@section('js-after')
<script defer src="{{ asset('/js/admin.app.js') }}"></script>
<link href="{{ asset('/extensions/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script defer src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script defer src="{{ asset('/extensions/jquery.dataTables.min.js') }}"></script>
<script defer src="{{ asset('/extensions/dataTables.bootstrap5.min.js') }}"></script>
@endsection
@section('admin-content')
    <div class="row mt-5">
        <div class="col-12">
            <h1 class="text-center mb-5">Events</h1>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#event-form" aria-expanded="true" aria-controls="event-form">
                            Create Event
                        </button>
                    </h2>
                    <div id="event-form" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="event form-wrapper mt-2">
                                <form action="{{ route('events.update') }}" method="post">
                                    @csrf
                                    <input type="hidden" id="event-id" name="event_id">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Titre" required value="{{ $event->title ?? old('title') }}"/>
                                    <input type="text" class="form-control" id="url" name="url" placeholder="Url" required value="{{ $event->url ?? old('url') }}"/>
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required value="{{ isset($event) ? $event->start_date->toDateString() : old('start_date') }}"/>
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date" value="{{ isset($event) ? $event->end_date->toDateString() : old('end_date') }}">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" placeholder="Start Time" required value="{{ $event->start_time ?? old('start_time') }}"/>
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" placeholder="End Time" value="{{ $event->end_time ?? old('end_time') }}" />
                                    <label for="exampleColorInput" class="form-label">Background Color picker</label>
                                    <input type="color" class="form-control" id="background_color" name="background_color" value="{{ $event->background_color ?? old('background_color') ?? '#ffffff' }}"/>
                                    <label for="exampleColorInput" class="form-label">Color picker</label>
                                    <input type="color" class="form-control" id="text_color" name="text_color" value="{{ $event->text_color ?? old('text_color') ?? '#ffffff' }}"/>
                                    <input type="text" class="form-control" id="days_of_week" name="days_of_week" placeholder="Days of week" value="{{ isset($event) && $event->days_of_week ? implode(',', $event->days_of_week) : old('days_of_week') }}" />
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_private" id="is_private" {{ isset($event) && $event->is_private ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_private">Private</label>
                                    </div>
                                    <div class="extended_props_wrapper">
                                        <label for="extended_props" class="form-label">
                                            Extended Props
                                            <button type="button" class="p-0 ms-2 btn btn-default add-extended_props fs-4">
                                                <i class="fa-solid fa-plus-circle"></i>
                                            </button>
                                        </label>
                                        @if(isset($event) && isset($event->extended_props))
                                            @php $index = 0 @endphp
                                            @foreach ($event->extended_props as $key => $prop)
                                                <div class="row extended_props_row">
                                                    <div class="col-5"><input type="text" class="form-control" name="extended_props[{{ $index }}][]" placeholder="Field name" value="{{ $key }}"/></div>
                                                    <div class="col-6"><input type="text" class="form-control" name="extended_props[{{ $index++ }}][]" placeholder="Field value" value="{{ $prop }}"/></div>
                                                    <div class="col-1 remove-extended_props"><i class="fa-solid fa-minus-circle"></i></div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="btn-actions">
                                        <button type="submit" class="form-control btn tc-blue-bg">Submit</button>
                                        @isset($event)
                                            <button type="button" class="form-control btn tc-blue-dark-2-bg update-event" data-id="{{ $event->id }}">Update</button>
                                            <button type="button" class="form-control btn tc-red-light-bg delete-event" data-id="{{ $event->id }}">Delete</button>
                                        @endisset
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered" id="events-list"></table>
            </div>
        </div>
    </div>
@endsection
