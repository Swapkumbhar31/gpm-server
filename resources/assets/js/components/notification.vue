<template>
  	<li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24"></i>
        </a>
         <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Something else here</a>
        </div>
    </li>
</template>	
<script>
export default {
  data() {
    return {
    	notifications: [],
        notification: {
            message:'',
            isRead:'',
            id: '',
            user_id:''
        },
    };
  },
  created() {
  	console.log("Notification Created");
    this.fetchNotifications();
  },
  methods: {
    fetchNotifications(module_id, page_url) {
      page_url = "/api/notifications";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.chapters = res.data;
          this.makePagenation(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },
  }
};
</script>
