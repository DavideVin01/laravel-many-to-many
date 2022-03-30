<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <Header :title="title" />

          <div class="card-body">Work In Progress Website</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Header from "./Header.vue";

export default {
  name: "App",
  components: {
    Header,
  },
  data(){
      return{
          title: 'Benvenuti',
      }
  }
  mounted() {
    console.log("Component mounted.");
  },
};
</script>
