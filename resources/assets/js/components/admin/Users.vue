<template>
	<div>
			<div class="card table-responsive">
						<table class="table">
								<tr>
										<th>ID</th>
										<th>Full name</th>
										<th>Email</th>
                    <th>Contact</th>
										<th>Type</th>
										<th>Details</th>
								</tr>
								<tr v-for="user in users" v-bind:key="user.id">
										<td>{{user.id}}</td>
										<td>{{user.name}}</td>
										<td>{{user.email}}</td>
                    <td>{{user.contact}}</td>
                    <td v-if="user.membership === 'core' && user.isAdmin != 2">Core</td>
                    <td v-if="user.membership === 'core' && user.isAdmin === 2">Master</td>
										<td v-if="user.membership === 'basic'">Basic</td>
										<td>
											<a v-bind:href="'/admin/members/details/'+user.id" class="btn btn-cyan btn-sm">Details</a>
										</td>
								</tr>
						</table>
				</div>
				<div class="text-left mt-4">
						<nav aria-label="Page navigation">
								<ul class="pagination">
										<li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
												<a class="page-link" href="javascript:void(0)" @click="fetchUsers(pagination.prev_page_url)">Previous</a>
										</li>
										<li class="page-item disabled">
												<a class="page-link text-dark"> Page {{ pagination.current_page}} of {{ pagination.last_page}}</a>
										</li>
										<li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
												<a @click="fetchUsers(pagination.next_page_url)" class="page-link" href="javascript:void(0)">Next</a>
										</li>
								</ul>
						</nav>
				</div>
	</div>
</template>
<script>
export default {
  data() {
    return {
      users: [],
      user: {
        id: "",
        name: "",
        email: "",
        contact: ""
      },
      pagination: {}
    };
  },
  created() {
	this.fetchUsers();
  },
  methods: {
		approve(id) {
      this.$http.post('/api/profile/approve', {
        api_key: User.user.api_key,
        id: id
      }).then(res => {
        this.fetchUsers(this.pagination.current_page);
      });
    },
    fetchUsers(page_url) {
      page_url = page_url || "/api/students";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.users = res.data;
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
    }
  }
};
</script>
