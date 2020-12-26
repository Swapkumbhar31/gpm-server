<template>
  <div>
      <div class="card mt-2">
            <form @submit.prevent="updatePassword()">
                <div class="card-body">
                    <div class="alert alert-success" v-if="success">
                        Password updated.
                    </div>
                    <div class="alert alert-danger" v-if="error">
                        {{msg}}
                    </div>
                    <div class="form-group">
                        <label>Old Password</label>
                        <input type="password" name="name" v-model="data.old" class="form-control" placeholder="Enter old password" required>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="name" v-model="data.new" class="form-control" placeholder="Enter New password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="name" v-model="data.confirm" class="form-control" placeholder="Re-enter password" required>
                    </div>
                    <input type="submit" value="Save" class="btn btn-success">
                </div>
            </form>
      </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
        data: {
            old: "",
            new: "",
            confirm: "",
        },
      success: false,
      error: false,
      msg: '',
    };
  },
  methods: {
    updatePassword() {
      fetch("/api/update/password/" + User.user.id, {
        method: "put",
        body: JSON.stringify(this.data),
        headers: {
          "content-type": "application/json"
        }
      })
      .then(res => res.json())
      .then(res => {
        if(res.status === 200){
            this.success = true;
            this.error = false;
            this.data.old = '';
            this.data.new = '';
            this.data.confirm = '';
        }else{
            this.error = true;
            this.success = false;
            this.msg = res.msg;
        }
      });
    }
  }
};
</script>
