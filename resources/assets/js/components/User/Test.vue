<template>
	<div class="container mt-4">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<form id="myForm" @submit.prevent="checkAnswer()">
					<div class="card mb-4" v-for="question in questions" v-bind:key="question.in">
						<div class="card-body">
							<p>{{question.answer}}</p>
							<h3>{{question.question}}</h3>

							<div class="custom-control custom-radio">
								<input type="radio" value="1" class="custom-control-input" v-bind:id="'optradio1-'+question.id" v-bind:name="'optradio-'+question.id">
								<label class="custom-control-label" v-bind:for="'optradio1-'+question.id">{{question.option1}}</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" value="2" class="custom-control-input" v-bind:id="'optradio2-'+question.id" v-bind:name="'optradio-'+question.id">
								<label class="custom-control-label" v-bind:for="'optradio2-'+question.id">{{question.option2}}</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" value="3" class="custom-control-input" v-bind:id="'optradio3-'+question.id" v-bind:name="'optradio-'+question.id">
								<label class="custom-control-label" v-bind:for="'optradio3-'+question.id">{{question.option3}}</label>
							</div>
							<div class="custom-control custom-radio">
								<input type="radio" value="4" class="custom-control-input" v-bind:id="'optradio4-'+question.id" v-bind:name="'optradio-'+question.id">
								<label class="custom-control-label" v-bind:for="'optradio4-'+question.id">{{question.option4}}</label>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-center">
						<button class="btn btn-primary" type="submit">Submit</button>
					</div>
				</form>
			</div>
		</div>
		<div class="modal  modal-mask" v-if="showModal">
			<div class="modal-dialog  modal-dialog-centered">
				<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Result</h4>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<p v-if="isPass">Congratulations, You pass this chapter.</p>
					<p v-if="!isPass">Bad luck, You failed this chapter.</p>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<a href="/view" v-if="isPass" class="btn btn-danger">Next Chapter</a>
					<a v-bind:href="'/test/'+chapter_id" v-if="!isPass" class="btn btn-danger">Retry</a>
					<a href="/view" v-if="!isPass" class="btn btn-warning">Watch video again</a>
				</div>

				</div>
			</div>
		</div>
	</div>
</template>
<script>
import axios from 'axios'
export default {
	data(){
		return{
			isPass : false,
			showModal: false ,
			chapter_id : '',
			questions :{
				id : '',
				question : '',
				option1 :'',
				option2 :'',
				option3 :'',
				option4 :'',
				answer : '',
				chapter_id : '',
			},
		}
	},
	created(){
		this.fetchUrl();
		this.fetchQuestion();
	},
	methods:{
		openModal() {
      	this.showModal = true;
		},
		closeModal() {
			this.showModal = false;
		},
        fetchUrl(){
            this.chapter_id = window.location.pathname.split('/')[2];
        },
		fetchQuestion(page_url){
			page_url = page_url || '/api/test/questions/'+this.chapter_id;
			fetch(page_url)
				.then(res =>  res.json())
				.then(res => {
					this.questions = res.data;
				})
				.catch(err => console.log(err))
		},
		checkAnswer(){
			let myForm = document.getElementById('myForm');
			let formData = new FormData(myForm);
			formData.append('user_id', User.user.id);
			axios.post('/api/test/check/'+this.chapter_id, formData)
				.then(res => res.data)
				.then(res => {
					if(res == 1)
						this.isPass = true;
					else
						this.isPass = false;
					this.openModal();
					console.log(res); 
				})
		}
	}
}
</script>
<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}
</style>