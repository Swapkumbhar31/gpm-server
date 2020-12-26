<template>
  <div class="card">
    <video id="myVideo" class="card-img video-js vjs-big-play-centered" controls preload="none" width="100%" height="264"
          data-setup='{"fluid": true , "center-big-play-button": true}'>
        <source v-bind:src="chapter.video_url" type="video/mp4">
    </video>
    <div class="card-body">
        <h1>{{chapter.name}}</h1>
       <span v-html="chapter.description"></span>
    </div>
    <a class="card-footer btn btn-success bg-success" v-bind:href="chapter.testUrl">Check your knowledge</a>
</div>
</template>
<script>
export default {
  data(){ 
      return{
          chapter : {
              name : '',
              description : '',
              video_id : '',
              video_url : '',
              chap_index : '',
              module_index : '',
              testUrl : '',
              pic : '',
          }

      }
  },
  created(){
      this.fetchCurrentChapter();
  },
  methods : {
      fetchCurrentChapter(){
            fetch('/api/chapter/current/'+User.user.id)
            .then(res =>  res.json())
            .then(res => {
                var video = Video("myVideo");
                this.chapter = res.data;
                this.chapter.video_url = "/video/"+this.chapter.video_id;
                this.chapter.testUrl = "/test/"+this.chapter.id;;
                video.src(this.chapter.video_url);
                // video.poster('chapters/'+this.chapter.pic);
                setTimeout(function(){
                    video.reset();
                    video.on('contextmenu', function(e) {
                        e.preventDefault();
                    });
                }, 1000);
            }).catch(err => console.log(err.msg))
        },
  }
}
</script>

