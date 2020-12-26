@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    <div class="row">
                        <div class="col-lg-3 border-right p-r-0">
                            <div class="card-body border-bottom">
                                <h4 class="card-title m-t-10">All Event</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div id="calendar-events" class="">
                                            @foreach($events as $event)
                                            <div class="calendar-events mb-2" data-class="bg-info">
                                                <i class="fa fa-circle mr-1"></i>
                                                {{ $event->event_name}}
                                            </div>
                                            @endforeach
                                        </div>
                                        <!-- checkbox -->
                                        <!-- <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="drop-remove">
                                            <label class="custom-control-label" for="drop-remove">Remove after drop</label>
                                        </div> -->
                                        <!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#add-new-event"
                                            class="btn m-t-20 btn-info btn-block waves-effect waves-light">
                                            <i class="ti-plus"></i> Add New Event
                                        </a> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
                            <div class="card-body b-l calender-sidebar">
                                <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN MODAL -->
    <div class="modal none-border" id="my-event">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Event Information</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success save-event waves-effect waves-light">Create event</button>
                    <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Category -->
    <div class="modal fade none-border" id="add-new-event">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><strong>Add</strong> a category</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="control-label">Category Name</label>
                                <input class="form-control form-white" placeholder="Enter name" type="text" name="category-name" />
                            </div>
                            <div class="col-md-6">
                                <label class="control-label">Choose Category Color</label>
                                <select class="form-control form-white" data-placeholder="Choose a color..." name="category-color">
                                    <option value="success">Success</option>
                                    <option value="danger">Danger</option>
                                    <option value="info">Info</option>
                                    <option value="primary">Primary</option>
                                    <option value="warning">Warning</option>
                                    <option value="inverse">Inverse</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect waves-light save-category" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->
    <!-- ============================================================== -->
    <!-- End PAge Content -->
    <!-- ============================================================== -->
</div>

@endsection

@section('script')
<script>
    ! function ($) {
        "use strict";

        var CalendarApp = function () {
            this.$body = $("body")
            this.$calendar = $('#calendar'),
                this.$event = ('#calendar-events div.calendar-events'),
                this.$categoryForm = $('#add-new-event form'),
                this.$extEvents = $('#calendar-events'),
                this.$modal = $('#my-event'),
                this.$saveCategoryBtn = $('.save-category'),
                this.$calendarObj = null
        };


        /* on drop */
        CalendarApp.prototype.onDrop = function (eventObj, date) {
                var $this = this;
                // retrieve the dropped element's stored Event Object
                var originalEventObject = eventObj.data('eventObject');
                var $categoryClass = eventObj.attr('data-class');
                // we need to copy it, so that multiple events don't have a reference to the same object
                var copiedEventObject = $.extend({}, originalEventObject);
                // assign it the date that was reported
                copiedEventObject.start = date;
                if ($categoryClass)
                    copiedEventObject['className'] = [$categoryClass];
                // render the event on the calendar
                $this.$calendar.fullCalendar('renderEvent', copiedEventObject, true);
                // is the "remove after drop" checkbox checked?
                if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    eventObj.remove();
                }
            },
            /* on click on event */
            CalendarApp.prototype.onEventClick = function (calEvent, jsEvent, view) {
                var $this = this;
                console.log(calEvent);
                var form = $("<form></form>");
                var parser = new DOMParser;
                var dom = parser.parseFromString(
                    '<!doctype html><body>' + calEvent.description,
                    'text/html');
                var description = dom.body.textContent;
                form.append("<h3>" + calEvent.title + "</h3>");
                var speakers = JSON.parse(parser.parseFromString('<!doctype html><body>' + calEvent.speakers,'text/html').body.textContent);
                var topics = JSON.parse(parser.parseFromString('<!doctype html><body>' + calEvent.topics,'text/html').body.textContent);


                form.append("<h5>Topics</h5>");
                let temphtml = '';
                temphtml = '<ul>';
                topics.forEach(o => {
                    temphtml = temphtml + '<li>' + o + '</li>';
                });
                temphtml = temphtml + '</ul>';
                form.append(temphtml);

                form.append("<h5>Speckers</h5>");
                temphtml = '<ul>';
                speakers.forEach(o => {
                    temphtml = temphtml + '<li>' + o + '</li>';
                });
                temphtml = temphtml + '</ul>';
                form.append(temphtml);

                form.append("<br>");
                form.append("<h5>Description</h5>");
                form.append(description);
                $this.$modal.modal({
                    backdrop: 'static'
                });
                $this.$modal.find('.delete-event').show().end().find('.save-event').hide().end().find('.modal-body').empty()
                    .prepend(form).end().find('.delete-event').unbind('click').click(function () {
                        $this.$calendarObj.fullCalendar('removeEvents', function (ev) {
                            return (ev._id == calEvent._id);
                        });
                        $this.$modal.modal('hide');
                    });
                $this.$modal.find('form').on('submit', function () {
                    calEvent.title = form.find("input[type=text]").val();
                    $this.$calendarObj.fullCalendar('updateEvent', calEvent);
                    $this.$modal.modal('hide');
                    return false;
                });
            },
            CalendarApp.prototype.enableDrag = function () {
                //init events
                $(this.$event).each(function () {
                    // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    };
                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject);
                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 999,
                        revert: true, // will cause the event to go back to its
                        revertDuration: 0 //  original position after the drag
                    });
                });
            }
        /* Initializing */
        CalendarApp.prototype.init = function () {
                // this.enableDrag();
                /*  Initialize the calendar  */
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                var form = '';
                var today = new Date($.now());

                var defaultEvents = [
                    @foreach($events as $event)
                {
                    title: '{{$event->event_name}}',
                    start: '{{$event->held_on}}',
                    @if($event->type === 'webinar')
                    color: '#2255a4',
                    @else
                    color: '#2961ff',
                    @endif()
                    allDay : false,
                    type: '{{$event->type}}',
                    description: '{{$event->description}}',
                    speakers: '{{$event->speakers}}',
                    topics: '{{$event->topics}}',
                    held_on: '{{$event->held_on}}',
                },
                    @endforeach
                ];

                var $this = this;
                $this.$calendarObj = $this.$calendar.fullCalendar({
                    slotDuration: '00:15:00',
                    /* If we want to split day time each 15minutes */
                    minTime: '08:00:00',
                    maxTime: '19:00:00',
                    defaultView: 'month',
                    handleWindowResize: true,

                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek'
                    },
                    events: defaultEvents,
                    editable: false,
                    droppable: false, // this allows things to be dropped onto the calendar !!!
                    eventLimit: true, // allow "more" link when too many events
                    selectable: true,
                    drop: function (date) {
                        $this.onDrop($(this), date);
                    },
                    select: function (start, end, allDay) {
                        $this.onSelect(start, end, allDay);
                    },
                    eventClick: function (calEvent, jsEvent, view) {
                        $this.onEventClick(calEvent, jsEvent, view);
                    }

                });


            },

            //init CalendarApp
            $.CalendarApp = new CalendarApp, $.CalendarApp.Constructor = CalendarApp

    }(window.jQuery),

    //initializing CalendarApp
    $(window).on('load', function () {
        $.CalendarApp.init()
    });
</script>


@endsection()