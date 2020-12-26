<template>
  <div>
        <button type="button" class="btn btn-primary mr-auto mb-2" data-toggle="modal" data-target="#myModal">
            Add Module
        </button>
        <div class="modal" ref="myModalRef" id="myModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Module</h4>
                        <button type="button" class="close" @click="clearInput()"  data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body"> 
                      <div class="alert alert-success" v-if="success">
                        Updated successfully.
                    </div>
                    <div class="alert alert-danger" v-if="error">
                        {{msg}}
                    </div>
                        <form class="mb-3" @submit.prevent="addModule()">
                            <div class="form-group">
                                <input type="text" name="name" v-model="module.name" class="form-control" placeholder="Enter module name">
                            </div>
                            <div class="form-group">
                                <vue-editor v-model="module.description"></vue-editor>
                            </div>
                            <div class="form-group">
                                <input type="number" name="name" v-model="module.mod_index" class="form-control" placeholder="Enter module index">
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                    </div>
                <div class="modal-footer">
                    <button type="button" @click="clearInput()" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                    <a class="page-link" href="javascript:void(0)" @click="fetchModules(pagination.prev_page_url)">Previous</a>
                </li>
                <li class="page-item disabled">
                    <a class="page-link text-dark"> Page {{ pagination.current_page}} of {{ pagination.last_page}}</a>
                </li>
                <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                    <a @click="fetchModules(pagination.next_page_url)" class="page-link" href="javascript:void(0)">Next</a>
                </li>
            </ul>
        </nav>
      <div class="card mb-2" v-for="module in modules" v-bind:key="module.id">
          <div class="card-body">
                <h3>{{module.name}}</h3>
                <span v-html="module.description"></span>
                <hr>
                <a class="btn btn-primary" v-bind:href="getChapterLink(module)">View</a>
                <button @click="deleteModule(module.id)" class="btn btn-danger">Delete</button>
                <button @click="editModule(module)" data-toggle="modal" data-target="#myModal" class="btn btn-warning">Edit</button>
          </div>
      </div>
  </div>
</template>
<script>
import { VueEditor } from "vue2-quill-editor";

export default {
  components: {
    VueEditor
  },

  data() {
    return {
      modules: [],
      module: {
        id: "",
        name: "",
        description: "",
        mod_index: ""
      },
      module_id: "",
      pagination: {},
      edit: false,
      success: true,
      error: true,
      msg: ""
    };
  },

  created() {
    this.fetchModules();
  },

  methods: {
    clearInput() {
      this.module.name = "";
      this.module.description = "";
      this.module.mod_index = "";
      this.error = false;
      this.success = false;
    },
    fetchModules(page_url) {
      let vm = this;
      page_url = page_url || "/api/modules";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.modules = res.data;
          vm.makePagenation(res.meta, res.links);
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
    deleteModule(id) {
      if (confirm("Are you sure?")) {
        fetch(`/api/module/${id}`, {
          method: "delete"
        })
          .then(res => res.json())
          .then(data => {
            this.fetchModules();
          })
          .catch(err => console.log(err));
      }
    },
    addModule() {
      if (this.edit === false) {
        //Add Module
        fetch("/api/module", {
          method: "post",
          body: JSON.stringify(this.module),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.success = true;
            this.module.name = "";
            this.module.description = "";
            this.module.mod_index = "";
            this.fetchModules();
          });
      } else {
        //Update Module
        fetch("/api/module", {
          method: "put",
          body: JSON.stringify(this.module),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.success = true;
            this.edit = false;
            this.module.name = "";
            this.module.description = "";
            this.module.mod_index = "";
            this.fetchModules();
          });
      }
    },
    editModule(module) {
      this.success = false;
      this.error = false;
      this.edit = true;
      this.module.id = module.id;
      this.module.module_id = module.id;
      this.module.name = module.name;
      this.module.description = module.description;
      this.module.mod_index = module.mod_index;
    },
    getChapterLink(module) {
      return "/admin/" + "chapter/" + module.id;
    }
  }
};
</script>
