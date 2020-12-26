<template>
  <div class="mt-2">
        <h1>{{module_name}}</h1>
        
        <button type="button" class="btn btn-primary mr-auto mb-4" data-toggle="modal" data-target="#myModal">
            Add question
        </button>
        <div class="modal" ref="myModalRef" id="myModal">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add Question</h4>
                        <button type="button" class="close" @click="clearInput()"  data-dismiss="modal">&times;</button>
                    </div>
                <div class="modal-body">
                    <form class="mb-3" @submit.prevent="addquestion()">
                        <div class="form-group">
                            <textarea type="text" name="option1" v-model="question.question" class="form-control" placeholder="Enter Question">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" name="option1" v-model="question.option1" class="form-control" placeholder="Enter Option1">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" name="option2" v-model="question.option2" class="form-control" placeholder="Enter Option2">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" name="option3" v-model="question.option3" class="form-control" placeholder="Enter Option3">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <textarea type="text" name="option4" v-model="question.option4" class="form-control" placeholder="Enter Option4">
                            </textarea>
                        </div>
                        <div class="form-group">
                            <select name="answer" class="form-control"  v-model="question.answer">
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                                <option value="4">Option 4</option>
                            </select>
                        </div>
                        <input type="hidden" v-model="question.chapter_id" name="chapter_id">
                        <input type="submit" class="btn btn-success">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="clearInput()" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
        <div class="card table-responsive">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Question</th>
                    <th>Options</th>
                </tr>
                <tr v-for="question in questions" v-bind:key="question.id">
                    <td>{{question.id}}</td>
                    <td>{{question.question}}</td>
                    <td>
                        <button class="btn btn-warning text-light" @click="editquestion(question)"  data-toggle="modal" data-target="#myModal">
                            <span class="fa fa-edit"></span>
                        </button>
                        <button @click="deletequestion(question.id)" class="btn btn-danger">
                            <span class="fa fa-trash"></span>
                        </button>
                    </td>
                </tr>
            </table>
        </div>
        <div class="text-left mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
                        <a class="page-link" href="javascript:void(0)" @click="fetchQuestion(pagination.prev_page_url)">Previous</a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link text-dark"> Page {{ pagination.current_page}} of {{ pagination.last_page}}</a>
                    </li>
                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
                        <a @click="fetchQuestion(pagination.next_page_url)" class="page-link" href="javascript:void(0)">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
  </div>
</template>
<script>
import { uuid } from 'vue-uuid'
import axios from 'axios'

export default {
    data(){
        return {
            module_id : '',
            chapter_id : '',
            module_name : '',
            questions : [],
            question :{
                id : '',
                question : '',
                option1 :'',
                option2 :'',
                option3 :'',
                option4 :'',
                answer : '',
                chapter_id : '',
            },
            chap_id : '',
            pagination: {},
            edit: false
        }
    },
    created(){
        this.fetchUrl();
        this.fetchQuestion();
    },
    methods:{
        fetchUrl(){
            this.chapter_id = window.location.pathname.split('/')[3];
        },
        fetchQuestion(page_url){
            page_url = page_url || '/api/questions/'+this.chapter_id;
            fetch(page_url)
                .then(res =>  res.json())
                .then(res => {
                    this.questions = res.data;
                    this.makePagenation(res.meta, res.links);
                })
                .catch(err => console.log(err))
        },
        makePagenation(meta, links){
          let pagination = {
              current_page: meta.current_page,
              last_page: meta.last_page,
              next_page_url: links.next,
              prev_page_url: links.prev
          }
          this.pagination = pagination;
        },
        clearInput(){
            this.question.question = ''
            this.question.option1 = ''
            this.question.option2 = ''
            this.question.option3 = ''
            this.question.option4 = ''
        },
        addquestion(){
            if(this.edit === false){
              //Add Module
              this.question.module_id = this.module_id
              this.question.chapter_id = this.chapter_id
              fetch('/api/question',{
                  method: 'post',
                  body: JSON.stringify(this.question),
                  headers: {
                      'content-type': 'application/json',
                  },
              })
              .then( res => res.json())
              .then(data => {
                  this.clearInput()
                  this.fetchQuestion();
              })
          }else{
              //Update Module
              fetch('/api/question',{
                  method: 'put',
                  body: JSON.stringify(this.question),
                  headers: {
                      'content-type': 'application/json'
                  }
              })
              .then( res => res.json())
              .then(data => {
                  this.edit = false;
                  this.clearInput()
                  this.fetchQuestion();
              })
          }
        },
        editquestion(question){
            this.edit = true;
            this.question.id = question.id;
            this.question.module_id = question.module_id;
            this.question.chapter_id = question.chapter_id;
            this.question.question = question.question;
            this.question.option1 = question.option1;
            this.question.option2 = question.option2;
            this.question.option3 = question.option3;
            this.question.option4 = question.option4;
            this.question.answer = question.answer;
        },
        deletequestion(id){
            if(confirm("Are you sure?")){
              fetch(`/api/question/${id}`, {
                  method: 'delete'
              })
              .then(res => res.json())
              .then(data => {
                  this.fetchquestion(this.module_id);
              })
              .catch(err => console.log(err));
          }
        },
    }
}
</script>
