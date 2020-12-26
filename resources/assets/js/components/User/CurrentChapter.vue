<template>
      <div class="card">
            <div class="card-body">
                <h4>Current Chapter</h4>
            </div>
            <img v-bind:src="'chapters/'+chapter.pic" class="card-img">
            <div class="card-body">
                <h2 class="mt-2">{{chapter.name}}</h2>
                <span v-html="chapter.description"></span>
                <br>
                <a class="btn btn-success" v-bind:href="viewUrl">Learn</a>
                <a class="btn btn-warning" v-bind:href="testUrl">Check your knowledge</a>
            </div>
      </div>
</template>
<script>
export default {
  data() {
      return{
          chapter:{
              name : '',
              description : '',
              id:'',
              pic:'',
          },
          viewUrl:'',
          testUrl:'',
      }
  },
  created(){
      this.fetchCurrentChapter();
  },
  methods:{
      fetchCurrentChapter(){
            fetch('/api/chapter/current/'+User.user.id)
            .then(res =>  res.json())
            .then(res => {
                this.chapter = res.data;
                this.viewUrl = "/view";
                this.testUrl = "/test/"+this.chapter.id;
            }).catch(err => console.log(err.msg))
        },
  }
}
</script>
