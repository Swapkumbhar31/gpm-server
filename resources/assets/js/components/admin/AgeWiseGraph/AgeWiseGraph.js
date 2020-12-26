import {
  Bar
} from 'vue-chartjs'
export default {
  extends: Bar,
  data() {
    return {
      labels: any,
      data: any,
    }
  },
  computed: {

  },
  mounted() {
    this.fetchTestRecord();
  },
  methods: {
    fetchTestRecord() {
      this.$http.post('/api/report/age-wise', {
          api_key: User.user.api_key,
        }).then(res => res.json())
        .then(res => {
          this.data = res.data;
          this.labels = res.lable;
          this.renderChart({

            labels: this.labels,
            datasets: [{
              label: 'Number of users',
              backgroundColor: '#28b779',
              data: this.data,
            }]
          }, {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              xAxes: [{
                gridLines: {
                  display: true
                }
              }],
              yAxes: [{
                gridLines: {
                  display: true
                },
                ticks: {
                 min: 0,  
                }
              }]
            }
          })
        }).catch(err => console.log(err.msg));
    },
  }
}