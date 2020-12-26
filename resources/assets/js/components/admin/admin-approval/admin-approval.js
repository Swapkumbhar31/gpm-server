export default {
  name: 'admin-approval',
  components: {},
  props: [],
  data() {
    return {
      user: {
        name: ""
      },
      users: []
    }
  },
  computed: {

  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      fetch("/api/account/approval/list")
        .then(res => res.json())
        .then(res => {
          this.users = res.data;
          // console.log(User.user.api_key);
        })
        .catch(err => console.log(err));
    },
    approve(id) {
      this.$http.post('/api/profile/approve', {
        api_key: User.user.api_key,
        id: id
      }).then(res => {
        this.users = res.body.data;
      });
    }
  }
}