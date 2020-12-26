export default {
  name: 'recent-activities',
  components: {},
  props: [],
  data() {
    return {
      activities: []
    }
  },
  computed: {

  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      this.$http.post('/api/get/all/activities', {
        api_key: User.user.api_key,
      }).then(res => {
        this.activities = res.body.data;
      }).catch(err => console.log(err));
    },
  }
}