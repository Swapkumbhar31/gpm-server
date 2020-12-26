@extends('layouts.app')


@section('content')

<div class="pane" id="pane_0">
	<img src="{{ asset('images/live.jpg') }}" alt="introduction" class="img-fluid" />
</div>
<div class="pane" id="pane_1" style="display: none">

	<div class="row">
	@if(count($streams) === 0)
	<h2 class="text-center">No live stream available</h2>
	@endif()
	@foreach($streams as $stream)
	<div class="col-sm-6">
		<div class="card">
			<div class="card-body">
				<h2>{{$stream->topic_name}}</h2>
				<p>{{$stream->description}}</p>
			</div>
			<button class="btn btn-info btn-block viewbtn" data-id="{{$stream->stream_uuid}}">View</button>
		</div>
	</div>
	@endforeach()
	</div>
	
</div>

<div class="pane" id="pane_2" style="display: none">
	<div id="video_container"></div>
	<input type="button" value="Quit Conference Room" id="quit" class="btn btn-danger btn-default btn-block">
</div>
@endsection


@section('script')
<style>
video{
	width:100%;
}
</style>
<script>
    var room;
	$('.viewbtn').click(function(){
		joinConference($(this).data('id'));
	});
// when Bistri API client is ready, function
// "onBistriConferenceReady" is invoked
onBistriConferenceReady = function () {

	// test if the browser is WebRTC compatible
	if ( !bc.isCompatible() ) {
		// if the browser is not compatible, display an alert
		alert( "your browser is not WebRTC compatible !" );
		// then stop the script execution
		return;
	}

	// initialize API client with application keys
	// if you don't have your own, you can get them at:
	// https://api.developers.bistri.com/login
	bc.init( {
		appId: "6252c9aa",
		appKey: "9370e7a1fb7782511f04a54d978ae4e9"
	} );

	/* Set events handler */

	// when local user is connected to the server
	bc.signaling.bind( "onConnected", function () {
		// show pane with id "pane_1"
		showPanel( "pane_1" );
	});

	// when an error occured on the server side
	bc.signaling.bind( "onError", function ( error ) {
		// display an alert message
		alert( error.text + " (" + error.code + ")" );
	} );

	// when the user has joined a room
	bc.signaling.bind( "onJoinedRoom", function ( data , localStream) {
		// set the current room name
		room = data.room;
        showPanel( "pane_2" );
        bc.call( data.members[ 0 ].id, data.room, { stream: localStream } );
	} );

	// when an error occurred while trying to join a room
	bc.signaling.bind( "onJoinRoomError", function ( error ) {
		// display an alert message
	   alert( error.text + " (" + error.code + ")" );
	});

	// when the local user has quitted the room
	bc.signaling.bind( "onQuittedRoom", function( room ) {
		// show pane with id "pane_1"
		showPanel( "pane_1" );
		// stop the local stream
		bc.stopStream( bc.getLocalStreams()[ 0 ], function( stream ){
			// remove the local stream from the page
			bc.detachStream( stream );
		} );
	} );

	// when a new remote stream is received
	bc.streams.bind( "onStreamAdded", function ( remoteStream ) {
		// insert the new remote stream into div#video_container node
		bc.attachStream( remoteStream, q( "#video_container" ) );
	} );

	// when a local or a remote stream has been stopped
	bc.streams.bind( "onStreamClosed", function ( stream ) {
		// remove the stream from the page
		bc.detachStream( stream );
	} );

	// bind function "quitConference" to button "Quit Conference Room"
	q( "#quit" ).addEventListener( "click", quitConference );

	// open a new session on the server
	bc.connect();
}

// when button "Join Conference Room" has been clicked
function joinConference(roomToJoin){
	// if "Conference Name" field is not empty ...
	if( roomToJoin ){
		// ... join the room
		bc.joinRoom( roomToJoin );
	}
	else{
		// otherwise, display an alert
		alert( "you must enter a room name !" )
	}
}

// when button "Quit Conference Room" has been clicked
function quitConference(){
	// quit the current conference room
	bc.quitRoom( room );
}

function showPanel( id ){
	var panes = document.querySelectorAll( ".pane" );
	// for all nodes matching the query ".pane"
	for( var i=0, max=panes.length; i<max; i++ ){
		// hide all nodes except the one to show
		panes[ i ].style.display = panes[ i ].id == id ? "block" : "none";
	};
}

function q( query ){
	// return the DOM node matching the query
	return document.querySelector( query );
}
</script>
@endsection
