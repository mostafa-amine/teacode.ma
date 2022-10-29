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
                            data-bs-target="#event-form-wrapper" aria-expanded="true" aria-controls="event-form-wrapper">
                            Create Event
                        </button>
                    </h2>
                    <div id="event-form-wrapper" class="accordion-collapse collapse" aria-labelledby="headingOne"
                        data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <div class="event form-wrapper mt-2">
                                <form id="event-form">
                                    <input type="hidden" id="event-id" name="event_id">
                                    <input type="text" class="form-control" id="title" name="title" placeholder="Titre" required />
                                    <input type="text" class="form-control" id="url" name="url" placeholder="Url" required />
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" placeholder="Start Date" required />
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" placeholder="End Date">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" placeholder="Start Time" required />
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" placeholder="End Time"  />
                                    <label for="exampleColorInput" class="form-label">Background Color picker</label>
                                    <input type="color" class="form-control" id="background_color" name="background_color" />
                                    <label for="exampleColorInput" class="form-label">Color picker</label>
                                    <input type="color" class="form-control" id="text_color" name="text_color" />
                                    <input type="text" class="form-control" id="days_of_week" name="days_of_week" placeholder="Days of week" />
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_private" id="is_private">
                                        <label class="form-check-label" for="is_private">Private</label>
                                    </div>
                                    <div class="extended_props_wrapper">
                                        <label for="extended_props" class="form-label">
                                            Extended Props
                                            <button type="button" class="p-0 ms-2 btn btn-default add-extended_props fs-4">
                                                <i class="fa-solid fa-plus-circle"></i>
                                            </button>
                                        </label>
                                        <div class="extended_props_items"></div>
                                    </div>
                                    <div class="btn-actions">
                                        <button type="submit" class="form-control btn tc-blue-bg">Submit</button>
                                        <button type="button" class="form-control btn tc-grey-dark-bg btn-form-clear">Clear</button>
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
