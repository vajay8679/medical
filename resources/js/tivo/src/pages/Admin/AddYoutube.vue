<template>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="product-info">
                    <form @submit.prevent="submitForm">
                        <div>
                            <input type="hidden" v-model="idd" />
                            <label for="youtubeLink">YouTube Link:</label>
                            <input
                                class="form-control"
                                type="text"
                                placeholder="Enter YouTube Link"
                                v-model="youtubelink"
                                id="youtubeLink"
                                ref="youtubeInput"
                            />
                            <span
                                class="error text-danger"
                                v-if="validationErrors.youtubelink"
                                >{{ validationErrors.youtubelink }}</span
                            >
                        </div>
                        <button
                            class="mt-4 btn radius"
                            type="button"
                            @click="clear(this.idd)"
                        >
                            Clear
                        </button>
                        <button
                            class="mt-4 btn btn-primary radius"
                            type="submit"
                        >
                            Submit
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import config from "../../config";
import axios from "axios";

export default {
    data() {
        return {
            idd:null,
            EditIds: null,
            parentId: "",
            youtubelink: "",
            validationErrors: {
                youtubelink: "",
            },
        };
    },

    methods: {
        clear(idd) {
            console.log(idd)
            const url = new URL(window.location.href);
            const id = url.pathname.split("/").pop();
            const isNum = /^\d+$/.test(id);
            if (isNum) {
                this.parentId = id;
            }
            const data = {
                youtubelink: this.youtubelink,
                parent_id: this.parentId,
            };
            console.log(data);
            if (confirm(`Are you sure you want to remove the Clear and not show in preview page?`)) {
                this.youtubelink = "";
              
                let token = localStorage.getItem("token");

                axios
                    .delete(
                        `${config.apiUrl}/api/my-product-delete-youtubelink/${idd}`,

                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        }
                    )
                    .then((res) => {
                        console.log("Error", res);
                        // this.$router.replace("/admin");
                        // window.location.reload();
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }

        },
        validateForm() {
            this.validationErrors.youtubelink = this.youtubelink
                ? ""
                : "YouTube Link is required";

            return Object.values(this.validationErrors).every(
                (error) => !error
            );
        },
        getFormDataW(id) {
            let token = localStorage.getItem("token");
            axios
                .get(`${config.apiUrl}/api/my-product-get-youtube/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((res) => {
                    // console.log(res, "resposnse");
                    if (res.data.result.length == 0) {
                        // console.log("iff sildebar")
                    } else {
                        this.youtubelink = res.data.result.youtubelink;
                        this.idd=res.data.result.id
                        
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        submitForm() {
            const url = new URL(window.location.href);
            const id = url.pathname.split("/").pop();
            const isNum = /^\d+$/.test(id);
            if (isNum) {
                this.parentId = id;
            }

            if (this.validateForm()) {
                const data = {
                    youtubelink: this.youtubelink,
                    parent_id: this.parentId,
                };
                console.log(data);

                let token = localStorage.getItem("token");

                axios
                    .post(
                        `${config.apiUrl}/api/my-product-youtube-link`,
                        data,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    )
                    .then((res) => {
                        console.log(res.data);
                        // this.$router.replace("/admin");
                        window.location.reload();
                        // Handle response
                        // this.$router.push({ name: AddFeatureVue});
                    })
                    .catch((error) => {
                        console.error(error);
                        // Handle error
                    });
            } else {
                this.$refs.youtubeInput.focus();
            }
        },
        //   Update(){
        //   console.log('upadte')
        //   return
        //   const url = new URL(window.location.href);
        //               const id = url.pathname.split("/").pop();
        //               const isNum = /^\d+$/.test(id);
        //               if (isNum) {
        //                   this.parentId = id;

        //               }

        //       if (this.validateForm()) {
        //           this.formData = new FormData(); // Clear the formData before appending new data
        //           this.features.forEach((feature, index) => {
        //               this.formData.append(`name[${index}]`, feature.name);
        //               this.formData.append(
        //                   `description[${index}]`,
        //                   feature.description
        //               );
        //               this.formData.append(`icon[${index}]`, feature.image);
        //               this.formData.append(`parent_id[${index}]`, this.parentId);
        //           });

        //           let token = localStorage.getItem("token");

        //           axios
        //               .put(
        //                   `${config.apiUrl}/api/my-product-youtube-link/${this.EditIds}`,
        //                   this.formData,
        //                   {
        //                       headers: {
        //                           Authorization: `Bearer ${token}`,
        //                           "Content-Type": "multipart/form-data",
        //                       },
        //                   }
        //               )
        //               .then((res) => {
        //                   console.log("Error", res);
        //               })
        //               .catch((error) => {
        //                   console.error(error);
        //               });

        //           this.features = [
        //               {
        //                   name: "",
        //                   description: "",
        //                   image: "",
        //               },
        //           ];
        //           this.validationErrors = {
        //               name: [],
        //               description: [],
        //               image: [],
        //           };
        //           this.imagePreview = [];
        //       }
        // }
    },
    mounted() {
        const url = new URL(window.location.href);
        const id = url.pathname.split("/").pop();
        const isNum = /^\d+$/.test(id);
        if (isNum) {
            this.getFormDataW(id);
            this.EditIds = id;
        }
    },
};
</script>
