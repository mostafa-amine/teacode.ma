import { getEvents, initCalendarActions, initCalendar } from "./calendar";
import { getContributor, initContributorActions } from "./contributor";
window.$ = require('jquery');

$(function () {
    try {
        $.ajaxSetup({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
        });
        getEvents();
        var calendarEl = document.getElementById('calendar-wrapper');
        if (calendarEl) initCalendar(calendarEl);
        initCalendarActions();

        getContributor();
        initContributorActions();
    } catch (error) {
        console.log(error);
    }
});
