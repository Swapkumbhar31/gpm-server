<template>
	<div>
		<div id="accordion">
			<div class="card" v-for="module in modules" v-bind:key="module.id">
				<div class="card-header" id="headingOne">
				<h5 class="mb-0">
					<button class="btn btn-link" data-toggle="collapse" v-bind:data-target="'#collapseOne'+module.id" aria-expanded="true" aria-controls="collapseOne">
					{{module.name}}
					</button>
				</h5>
				</div>

				<div v-bind:id="'collapseOne'+module.id" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
					<div class="card-body">
						<ul class="list-group">
							<li class="list-group-item"  v-for="chapter in module.chapters" v-bind:key="chapter.id">{{chapter.name}}</li>
						</ul>
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
			modules:[],
			module:{
			  id: '',
			  name: '',
			  chapters : {
				  name: '',
				  id: '',
			  },
			  description: '',
			  mod_index: ''
		  },
		}
	},
	created(){
		this.fetchSyllabus();
	},
	methods:{
		fetchSyllabus(){
			fetch('/api/syllabus')
				.then(res =>  res.json())
				.then(res => {
					this.modules = res.data;
				})
				.catch(err => console.log(err))
		}
	}
}
</script>
