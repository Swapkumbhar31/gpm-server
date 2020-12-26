import {Bar} from 'vue-chartjs'
export default {
  extends: Bar,
  data () {
    return {
      labels: any,
      data: any,
    }
  },
  computed: {

  },
  mounted () {
    this.fetchTestRecord();
  },
  methods: {
    fetchTestRecord(){
      fetch('/api/test/chart/records/'+User.user.id)
      .then(res =>  res.json())
      .then(res => {
        this.data = res.data;
        this.labels = res.lable;
        this.renderChart({
          labels: this.labels,
          datasets: [
            {
              label: 'Percent',
              backgroundColor: '#28b779',
              data: this.data
            }
          ]
        }, {responsive: true, maintainAspectRatio: false})
      }).catch(err => console.log(err.msg))
  },
  }
}
