import { Calendar } from '@fullcalendar/core';
import interactionPlugin from '@fullcalendar/interaction';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';

let table = undefined;
function getEvents() {
    let eventslistElement = $('#events-list');
    if (!eventslistElement.length) {
        return;
    }
    table = eventslistElement.DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'GET',
            url: '/admin/events',
            data: {api: true},
        },
        columns: [
            {data: "id", name: "id", title: "id"},
            {
                data: "id", name: "id", title: "Actions",
                render: function(data, type, row) {
                    return `<div class="actions d-flex justify-content-around">
                        <div class="prepare-update-event cursor-pointer fs-2"><i class="fa-solid fa-pen-to-square"></i></div>
                        <div class="delete-event cursor-pointer fs-2" data-id="${data}"><i class="fa-solid fa-trash"></i></div>
                        <div class="restore-event cursor-pointer fs-2" data-id="${data}"><i class="fa-solid fa-rotate-left"></i></div>
                    </div>`;
                }
            },
            {data: "title", name: "title", title: "Title"},
            {data: "url", name: "url", title: "URL"},
            {
                data: "start_date", name: "start_date", title: "Start Date",
                render: function(data, type, row) {
                    return (new Date(data)).toDateString();
                }
            },
            {data: "start_time", name: "start_time", title: "Start Time"},
            {
                data: "end_date", name: "end_date", title: "End Date",
                render: function(data, type, row) {
                    return (new Date(data)).toDateString();
                }
            },
            {data: "end_time", name: "end_time", title: "End Time"},
            {data: "days_of_week", name: "days_of_week", title: "Days Of Week"},
            {data: "is_private", name: "is_private", title: "Is Private"},
            {
                data: "extended_props", name: "extended_props", title: "Extended Props",
                render: function (data, type, row) {
                    let dom = '';
                    let index = 0;
                    for (let property in data) {
                        dom += `<p class="extended_props_row">${property} : ${data[property]}</p>`;
                    }
                    return dom;
                }
            },
            {
                data: "deleted_at", name: "deleted_at", title: "Active",
                render: function(data, type, row) {
                    let color = data ? 'red' : 'green';
                    return `<div><i class="fa-solid fa-circle" style="color: ${color}"></i></div>`;
                }
            },
        ]
    });
}

function initCalendarActions() {
    $('.event').on('click', '.add-extended_props', function (){
        let index = $('.extended_props_row').length;
        let dom = `<div class="row extended_props_row">
                        <div class="col-5"><input type="text" class="form-control" name="extended_props[${index}][]" placeholder="Field name"/></div>
                        <div class="col-6"><input type="text" class="form-control" name="extended_props[${index}][]" placeholder="Field value"/></div>
                        <div class="col-1 remove-extended_props"><i class="fa-solid fa-minus-circle"></i></div>
                    </div>`;
        $('.extended_props_items').append(dom);
    });
    $('.event').on('click', '.remove-extended_props', function (){
        $(this).parent('.extended_props_row').remove();
    });
    $('#events-list').on('click', '.prepare-update-event', function (e) {
        let row = table.row($(this).closest('tr')).data();
        $('#event-id').val(row.id);
        $('#title').val(row.title);
        $('#url').val(row.url);
        $('#start_date').val(row.start_date.split('T')[0]);
        $('#end_date').val(row.end_date?.split('T')[0]);
        $('#start_time').val(row.start_time);
        $('#end_time').val(row.end_time);
        $('#background_color').val(row.background_color);
        $('#text_color').val(row.text_color);
        $('#days_of_week').val(row.days_of_week);
        $('#is_private').prop('checked', row.is_private);
        let dom = '';
        let index = 0;
        let data = row.extended_props;
        for (let property in data) {
            dom += `<div class="row extended_props_row">
                        <div class="col-5"><input type="text" class="form-control" name="extended_props[${index}][]" placeholder="Field name" value="${property}"/></div>
                        <div class="col-6"><input type="text" class="form-control" name="extended_props[${index}][]" placeholder="Field value" value="${data[property]}"/></div>
                        <div class="col-1 remove-extended_props"><i class="fa-solid fa-minus-circle"></i></div>
                    </div>`;
            index++;
        }
        $('.extended_props_items').html(dom);
    });
    
    $('#events-list').on('click', '.restore-event', function (e) {
        if (!confirm('Are you sur ?')) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: '/admin/events',
            data: {restore: true, event_id: $(this).data('id')},
            success: function (response) {
                alert(response.message);
                table.ajax.reload(null, false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });
    $('#events-list').on('click', '.delete-event', function (e) {
        if (!confirm('Are you sur ?')) {
            return;
        }
        $.ajax({
            method: 'POST',
            url: '/admin/events',
            data: {delete: true, event_id: $(this).data('id')},
            success: function (response) {
                alert(response.message);
                table.ajax.reload(null, false);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });
    $('.btn-form-clear').on('click', function() {
        $('#contributor-id').val('');
        $('#role').val('role');
        
        $('#event-id').val('');
        $('#title').val('');
        $('#url').val('');
        $('#start_date').val('');
        $('#end_date').val('');
        $('#start_time').val('');
        $('#end_time').val('');
        $('#background_color').val('');
        $('#text_color').val('');
        $('#days_of_week').val('');
        $('#is_private').prop('checked', false);
        $('.extended_props_items').empty();
    });
    
    $('#event-form').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this)
        $.ajax({
            method: 'POST',
            url: '/admin/events',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                table.ajax.reload(null, false);
                alert(response.message);
            },
            error: function (jqXHR, textStatus, errorThrown){
                console.log(jqXHR, textStatus, errorThrown);
                alert('Error');
            }
        });
    });
}

function initCalendar(calendarEl) {
    var calendar = new Calendar(calendarEl, {
        plugins: [interactionPlugin, dayGridPlugin, timeGridPlugin, listPlugin],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        // slotMinTime: '12:00',
        // slotMaxTime: '23:59',
        firstDay: 1,
        titleFormat: { year: 'numeric', month: '2-digit'},
        themeSystem: 'standard',
        initialDate:  new Date().toISOString(),
        nowIndicator: true,
        navLinks: true,
        allDaySlot: false,
        weekNumbers: true,
        weekNumberFormat: { week: 'numeric' },
        initialView: 'dayGridMonth',
        selectable: false,
        dayMaxEvents: true,
        events: '/api/events',
        eventClick: function(info) {
            info.jsEvent.preventDefault();
            let event = info.event
            $('#event-detail .modal-body').empty();
            let date = event.start.toLocaleString([], {day: 'numeric', weekday: 'short', year: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'});
            let url = event.url.length <= 50 ?  event.url : event.url.substring(0, 50) + '...';
            let dom = `<div class="event-info event-title">
                            <div class="event-icon"><i class="fa-solid fa-dot-circle"></i></div>
                            <div class="event-text"><span>${event.title}</span></div>
                        </div>
                        <div class="event-info event-date">
                            <div class="event-icon"><i class="fa-solid fa-calendar-day"></i></div>
                            <div class="event-text"><span>${date}</span></div>
                        </div>
                        <div class="event-info event-url">
                            <div class="event-icon"><i class="fa-solid fa-link"></i></div>
                            <div class="event-text"><span><a href="${event.url}" target="_blank">${url}</a></span></div>
                        </div>`;
            $('#event-detail .modal-body').append(dom);
            $('#event-detail').addClass('d-block show in animate__fadeIn').removeClass('animate__fadeOut');
        },
    });
    calendar.render();
    calendar.scrollToTime('18:00:00');
    // calendar.setOption('height', '100%');

    $('#event-detail .close').on('click', function (e) {
        $('#event-detail').addClass('animate__fadeOut').removeClass('animate__fadeIn');
        setTimeout(() => {
            $('#event-detail').removeClass('d-block show in');
        }, 300);
    });

    $('#event-detail').on('click', function (e) {
        if (e.currentTarget == e.target || $(e.target)[0] == $('.close i')[0]) {
            $('#event-detail').addClass('animate__fadeOut').removeClass('animate__fadeIn');
            setTimeout(() => {
                $('#event-detail').removeClass('d-block show in');
            }, 300);
        }
    });
}

export { getEvents, initCalendarActions, initCalendar }
