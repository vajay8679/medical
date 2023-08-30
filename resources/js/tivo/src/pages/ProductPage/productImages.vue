<template>
  <div class="col-md-12">
      <!-- {{ single_gallery }} -->
      <div v-for="item in single_gallery" class="card-body">
          <div>
              <!-- {{ console.log() }} -->
              <img
                  v-if="item.image_path && item.image_path.length > 0"
                  :src="getFullUrl(item.image_path)"
                  :alt="item.image_name ? item.image_name : 'Product Image'"
              />
          </div>
      </div>
  </div>
</template>

<script>
import config from "../../config";
export default {
  name: "Benefits",
  props: {
      single_gallery: {
          type: Array,
          required: true,
          default: () => [],
      },
  },
  methods: {
        getFullUrl(urlnew){
            return config.imageUrl+urlnew;
        }
    },
};
</script>

<style scoped>
.Inner-main {
    margin-top: 50px;
}
img {
    max-width: 100%;
    height: auto;
}
.div-img-a {
    border-radius: 32px;
}
img {
    margin-left: 35px;
    width: 90% !important;
   
    margin-top: 30px;
    border-radius: 12px;
    
}
</style>