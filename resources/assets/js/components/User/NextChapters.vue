<template>
  <div>
    <div class="card">
      <div class="card-body">
        <h4>Next Chapter</h4>
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
              <a class="page-link" href="javascript:void(0)" @click="fetchNextChapter(pagination.prev_page_url)">
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
              </a>
            </li>
            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
              <a @click="fetchNextChapter(pagination.next_page_url)" class="page-link" href="javascript:void(0)">
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </a>
            </li>
          </ul>
        </nav>
        <div class="container-fluid">
          <div v-if="chapters.length > 0">
            <div class="row" v-for="chapter in chapters" v-bind:key="chapter.id">
              <div class="col-3 p-0">
                <img v-bind:src="'chapters/thumbnail/'+chapter.pic" class="card-img mt-2">
              </div>
              <div class="col-9 my-auto">
                <span class="h5">{{chapter.name}}</span>
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
    data() {
      return {
        chapters: {
          name: ""
        },
        pagination: {}
      };
    },
    created() {
      this.fetchNextChapter();
    },
    methods: {
      fetchNextChapter(url) {
        url = url || "/chapter/next/" + User.user.id;
        fetch(url)
          .then(res => res.json())
          .then(res => {
            this.chapters = res.data;
            this.makePagenation(res);
          })
          .catch(err => console.log(err.msg));
      },
      makePagenation(links) {
        let pagination = {
          current_page: links.current_page,
          last_page: links.last_page,
          next_page_url: links.next_page_url,
          prev_page_url: links.prev_page_url
        };
        this.pagination = pagination;
      }
    }
  };
</script>