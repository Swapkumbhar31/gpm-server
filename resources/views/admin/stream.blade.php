@extends('layouts.admin')

@section('content')
<div class="pane" id="pane_0">
      <img src="http://static.tumblr.com/uzwqx7a/8VIm8jofz/logo.png">
</div>
<div class="pane" id="pane_1" style="display: none">
	<div class="row">
		<div class="col-12 mb-1">
			<input type="text" placeholder="Topic Name" id="topic" class="form-control">
		</div>
		<div class="col-12 mb-1">
			<textarea type="text" placeholder="Description" id="description" class="form-control"></textarea>
		</div>
		<div class="col-12 mb-1">
			<input type="button" value="Start Stream" id="join" class="btn btn-info btn-default btn-block">
		</div>
	</div>
      
      
      
</div>

<div class="pane" id="pane_2" style="display: none">
      <div id="video_container" class="img-fluid"></div>
      <input type="button" value="Stop Stream" id="quit" class="btn btn-danger btn-default btn-block">
</div>
@endsection


@section('script')
<style>
video{
	width:100%;
}
</style>
<script type="text/javascript">
var room = "false";

// when Bistri API client is ready, function
// "onBistriConferenceReady" is invoked
$(window).on("unload", function() { 
    $.post("/stream/stop",
	{
		id: room
	},
	function(postdata, status){ });
});
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
	});

	/* Set events handler */

	// when local user is connected to the server
	bc.signaling.bind( "onConnected", function () {
		// show pane with id "pane_1"
		showPanel( "pane_1" );
	} );

	// when an error occured on the server side
	bc.signaling.bind( "onError", function ( error ) {
		// display an alert message
		alert( error.text + " (" + error.code + ")" );
	} );

	// when the user has joined a room
	bc.signaling.bind( "onJoinedRoom", function ( data ) {
		// set the current room name
		room = data.room;
		// ask the user to access to his webcam and set the resolution to 640x480
		bc.startStream( "640x480", function( localStream ){
			// when webcam access has been granted
			// show pane with id "pane_2"
			console.log("room : "+room);
			showPanel( "pane_2" );
			// insert the local webcam stream into the page body, mirror option invert the display
			$.post("/stream/start",
			{
				id: room
			},
			function(postdata, status){
				bc.attachStream( localStream, q( "#video_container" ), { mirror: true } );
			});
		} );
	} );

	// when an error occurred while trying to join a room
	bc.signaling.bind( "onJoinRoomError", function ( error ) {
		// display an alert message
	   alert( error.text + " (" + error.code + ")" );
	} );

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
	bc.streams.bind( "onStreamAdded", function ( remoteStream, id ) {
		// insert the new remote stream into div#video_container node
        console.log(id);
		//bc.attachStream( remoteStream, q( "#video_container" ) );
	} );

	// when a local or a remote stream has been stopped
	bc.streams.bind( "onStreamClosed", function ( stream ) {
		// remove the stream from the page
		bc.detachStream( stream );
	} );

	// bind function "joinConference" to button "Join Conference Room"
	q( "#join" ).addEventListener( "click", joinConference );

	// bind function "quitConference" to button "Quit Conference Room"
	q( "#quit" ).addEventListener( "click", quitConference );

	// open a new session on the server
	bc.connect();
}

// when button "Join Conference Room" has been clicked
function joinConference(){
	$.post("/stream/init",
	{
		topic_name: $('#topic').val(),
		description: $('#description').val()
	},
	function(data, status){
		bc.joinRoom( JSON.parse(data)[0].data.stream_uuid );
	});
}

// when button "Quit Conference Room" has been clicked
function quitConference(){
	// quit the current conference room
	$.post("/stream/stop",
	{
		id: room
	},
	function(postdata, status){
		bc.quitRoom( room );
	});
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
