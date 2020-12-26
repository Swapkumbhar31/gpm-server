export default {
    name: 'admin-board',
    components: {},
    props: [],
    data() {
        return {
            board: {
                total_users: 0,
                new_users: 0,
                earing: 0,
                total_master: 0
            }
        }
    },
    computed: {

    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            fetch("/api/adminboard")
                .then(res => res.json())
                .then(res => {
                    this.board = res;
                })
                .catch(err => console.log(err));
        },
    }
}