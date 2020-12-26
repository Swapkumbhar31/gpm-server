<template>
  <div>
    <div class="card">
          <div class="card-body">
              <h4>Current Progress</h4>
              <p>It will help you for tracking your progress</p>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-3 p-0">
                    <img src="http://via.placeholder.com/350x350" class="card-img mt-2">
                  </div>
                  <div class="col-9 my-auto">
                    <p>Chapter progress ({{completed}}/{{total}})</p>
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped"  v-bind:style="{'width': chapters}"></div>
                    </div>
                  </div>
                  <div class="col-3 p-0">
                    <img src="http://via.placeholder.com/350x350" class="card-img mt-2">
                  </div>
                  <div class="col-9 my-auto">
                    <p>Test result average ({{test_avg}})</p>
                    <div class="progress">
                      <div class="progress-bar progress-bar-striped" v-bind:style="{'width': test_avg}"></div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
      </div>
  </div>
</template>
<script>
export default {
  data(){ 
    return{
      test_avg : 0,
      chapters : 0,
      completed : 0,
      total : 0,
    }    
  },
  created() {
    this.fetchChapterProgress();
    this.fetchTestProgress();
  },
  methods: {
    fetchChapterProgress(url) {
      url = url || "/chapter/progress/" + User.user.id;
      fetch(url)
        .then(res => res.json())
        .then(res => {
          this.chapters = res.chapters+ "%";
          this.completed = res.completed;
          this.total = res.total;
        })
        .catch(err => console.log(err.msg));
    },
    fetchTestProgress(url) {
      url = url || "/api/test/progress/" + User.user.id;
      fetch(url)
        .then(res => res.json())
        .then(res => {
          this.test_avg = res.test_avg + "%";
        })
        .catch(err => console.log(err.msg));
    },
  }
}
</script>
 