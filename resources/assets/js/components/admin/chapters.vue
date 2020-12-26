<template>
  <div class="mt-2">
		<button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#myModal">
			Add Chapter
		</button>
	  <div class="text-left ">
		  <nav aria-label="Page navigation example">
			<ul class="pagination">
				<li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
					<a class="page-link" href="javascript:void(0)" @click="fetchChapter(module_id,pagination.prev_page_url)">Previous</a>
				</li>
				<li class="page-item disabled">
					<a class="page-link text-dark"> Page {{ pagination.current_page}} of {{ pagination.last_page}}</a>
				</li>
				<li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
					<a @click="fetchChapter(module_id,pagination.next_page_url)" class="page-link" href="javascript:void(0)">Next</a>
				</li>
			</ul>
		</nav>
	  </div>
		<div class="modal" ref="myModalRef" id="myModal">
			<div class="modal-dialog modal-lg modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Chapter</h4>
						<button type="button" class="close" @click="clearInput()"  data-dismiss="modal">&times;</button>
					</div>
				<div class="modal-body">
					<form class="mb-3" id="uploadForm" @submit.prevent="addChapter()">
						<p class="text-success">{{uploadmsg}}</p>
						<div class="form-group">
              <label>Name</label>
							<input type="text" name="name" v-model="chapter.name" class="form-control" placeholder="Enter chapter name">
						</div>
						<div class="form-group">
              <label>Chapter image</label><br>
							<input id="chap_img" type="file" ref="chap_img">
						</div>
						<div class="form-group">
              <label>Chapter index</label>
							<input type="number" name="chap_index" v-model="chapter.chap_index" class="form-control" placeholder="Enter chapter index">
						</div>
						<div class="form-group">
              <label>Video file</label><br>
							<input id="my_file" type="file" ref="fileInput">
						</div>
						<div class="form-group">
              <label>Description</label>
							<vue-editor v-model="chapter.description"></vue-editor>
						</div>
						<input type="submit" class="btn btn-success">
						<input type="hidden" v-model="chapter.video_id" name="video_id">
						<input type="hidden" v-model="chapter.module_id" name="module_id">
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" @click="clearInput()" class="btn btn-danger" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div>
		<h2>{{module_name}}</h2>
		<div class="card">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Index</th>
							<th>Chapter name</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="chapter in chapters" v-bind:key="chapter.id">
							<th>{{chapter.chap_index}}</th>
							<td>{{chapter.name | subStr(100)}}</td>
							<td>
								<a class="btn btn-primary" v-bind:href="getQuestionLink(chapter)">View</a>
								<button class="btn btn-warning" @click="editChapter(chapter)"  data-toggle="modal" data-target="#myModal">Edit</button>
								<button @click="deleteChapter(chapter.id)" class="btn btn-danger">Delete</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		
  </div>
</template>
<script>
import { uuid } from "vue-uuid";
import axios from "axios";
import { VueEditor } from "vue2-quill-editor";

export default {
  components: {
    VueEditor
  },
  data() {
    return {
      module_id: "",
      module_name: "",
      chapters: [],
      chapter: {
        id: "",
        name: "",
        chap_index: "",
        video_file: "",
        video_id: "",
        chap_name: "",
        module_id: "",
        pic: ""
      },
      uploadmsg: "",
      chap_id: "",
      pagination: {},
      edit: false
    };
  },
  created() {
    this.fetchUrl();
    this.fetchChapter(this.module_id);
  },
  filters: {
    subStr: function(string, count) {
      if (string.length > count) return string.substring(0, count) + "...";
      else return string;
    }
  },
  methods: {
    fetchUrl() {
      this.module_id = window.location.pathname.split("/")[2];
    },
    fetchChapter(module_id, page_url) {
      page_url = page_url || "/api/chapters/" + module_id;
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.chapters = res.data;
          this.makePagenation(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },
    makePagenation(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },
    clearInput() {
      this.uploadmsg = "";
      this.chapter.name = "";
      this.chapter.description = "";
      this.chapter.chap_index = "";
      this.chapter.module_id = "";
      this.chapter.video_id = "";
      this.chapter.video_file = "";
      this.chapter.pic = "";
    },
    createImage(file, chapter) {
      let reader = new FileReader();
      let vm = this;
      reader.onload = e => {
        chapter.pic = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    addChapter() {
      const fd = new FormData();
      fd.append("name", this.chapter.name);
      fd.append("description", this.chapter.description);
      fd.append("chap_index", this.chapter.chap_index);
      fd.append("video_url", this.$refs.fileInput.files[0]);
      fd.append("module_id", this.module_id);
      fd.append("pic", this.$refs.chap_img.files[0]);
      console.log(this.$refs.chap_img.files[0]);
      if (this.edit === false) {
        axios
          .post("/api/chapter", fd, {
            onUploadProgress: uploadEvent => {
              this.uploadmsg =
                "Uploading..." +
                Math.round(uploadEvent.loaded / uploadEvent.total * 100) +
                "%";
            }
          })
          .then(res => {
            this.fetchChapter(this.module_id);
          });
      } else {
        fd.append("video_id", this.chapter.video_id);
        fd.append("module_id", this.module_id);
        fd.append("id", this.chapter.id);
        axios
          .post("/api/chapter/update", fd, {
            onUploadProgress: uploadEvent => {
              this.uploadmsg =
                "Uploading..." +
                Math.round(uploadEvent.loaded / uploadEvent.total * 100) +
                "%";
            }
          })
          .then(res => {
            this.edit = false;
            this.fetchChapter(this.module_id);
          });
      }
    },
    editChapter(chapter) {
      this.edit = true;
      this.chapter.id = chapter.id;
      this.chapter.module_id = chapter.module_id;
      this.chapter.name = chapter.name;
      this.chapter.video_id = chapter.video_id;
      this.chapter.description = chapter.description;
      this.chapter.chap_index = chapter.chap_index;
    },
    deleteChapter(id) {
      if (confirm("Are you sure?")) {
        fetch(`/api/chapter/${id}`, {
          method: "delete"
        })
          .then(res => res.json())
          .then(data => {
            this.fetchChapter(this.module_id);
          })
          .catch(err => console.log(err));
      }
    },
    getQuestionLink(chapter) {
      return "/admin/question/" + chapter.id;
    }
  }
};
</script>
