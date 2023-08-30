<template>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="product-info">
                    <div class="inputField">
                        <input
                            type="text "
                            class="form-control"
                            placeholder="Enter Add New Feature"
                            v-model="addNewItem"
                            ref="newFeatureRef"
                        />
                        <button
                            type="submit"
                            class="btn"
                            @click="addNewFeature()"
                        >
                            Add New Feature
                        </button>
                    </div>
                    <span class="text-danger" v-if="errorMessage !== ''">
                        {{ errorMessage }}
                    </span>
                    <form @submit.prevent="submitForm">
                        <div v-for="(label, index) in labels" :key="index">
                            <!-- {{ label }} -->
                            <label>
                                <input
                                    type="checkbox"
                                    id="myCheck"
                                    v-model="checkboxValues[index]"
                                />
                                <span class="capitalize">{{ label.name }}</span>
                            </label>
                            <span
                                class="error text-danger"
                                v-if="!hasSelectedCheckbox"
                            >
                                Please select at least one option.
                            </span>
                            <img
                                class="imgRight"
                                @click="removeTitle(label.id, index)"
                                src="../../assets/images/logo/x.svg"
                                alt=""
                            />
                        </div>

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
            EditIds: null,
            labels: [], // Array to store labels fetched from the API
            checkboxValues: [], // Array to store checkbox values
            parentId: "",
            hasSelectedCheckbox: true,
            addNewItem: "",
            errorMessage: "",
        };
    },

    created() {
        this.fetchLabels();
    },

    methods: {
        removeTitle(id, index) {
            console.log(id, "id", index);
            if(confirm(`Are you sure you want to remove the Assing Feature?`)){
                this.labels.splice(index, 1);
                let token = localStorage.getItem("token");

                axios
                    .delete(
                        `${config.apiUrl}/api/my-product-delete-addnewfeature/${id}`,

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
        fetchLabels() {
            let token = localStorage.getItem("token");

            axios
                .get(`${config.apiUrl}/api/my-product-getmulti-features`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((response) => {
                    this.labels = response.data.result;
                    // console.log(this.labels,'label')
                    // console.log(this.labels[0].id); // Assuming the API response contains an array of label objects with a 'name' property
                    this.checkboxValues = Array(this.labels.length).fill(false); // Initialize checkboxValues array with false values
                    // console.log(this.checkboxValues)
                })
                .catch((error) => {
                    console.error(error);
                    // Handle error
                });
        },
        addNewFeature() {
            // console.log(this.addNewItem)
            if (!this.addNewItem) {
                this.errorMessage = "The  Add New Feature Field is required";
            }

            if(this.errorMessage !==""){
                this.$refs.newFeatureRef.focus();
            }
            let token = localStorage.getItem("token");
            let data = {
                name: this.addNewItem,
            };

            axios
                .post(
                    `${config.apiUrl}/api/my-product-add-new-multi-feat`,
                    data,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json",
                        },
                    }
                )
                .then((response) => {
                   
                    window.location.reload();
                    this.addNewItem = "";
                })
                .catch((error) => {
                    console.error(error);
                    // Handle error
                });
        },

        submitForm() {
            const url = new URL(window.location.href);
            const id = url.pathname.split("/").pop();
            const isNum = /^\d+$/.test(id);
            if (isNum) {
                this.parentId = id;
            }

            const selectedData = this.labels.reduce((acc, label, index) => {
                // console.log(acc,'acc',label,index)
                if (this.checkboxValues[index]) {
                    acc.push({
                        my_feature_id: label.id,
                        parent_id: this.parentId,
                    });
                }
                return acc;
            }, []);
            if (selectedData.length === 0) {
                this.hasSelectedCheckbox = false; // Set validation error flag to show error message
                return;
            }

            let token = localStorage.getItem("token");

            axios
                .post(
                    `${config.apiUrl}/api/my-product-assing-features`,
                    selectedData,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json",
                        },
                    }
                )
                .then((response) => {
                    // console.log("Response", response);
                    // this.$router.replace("/admin");
                    window.location.reload();

                    // Handle response
                })
                .catch((error) => {
                    console.error(error);
                    // Handle error
                });

            this.checkboxValues = Array(this.labels.length).fill(false); // Reset checkbox values to false
        },
        getFormDataW(id) {
            // return;
            let token = localStorage.getItem("token");
            axios
                .get(
                    `${config.apiUrl}/api/get-my-product-assign-feature/${id}`,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "multipart/form-data",
                        },
                    }
                )
                .then((res) => {
                    // console.log(res.data.result, "resposnse");
                    if (res.data.result.length == 0) {
                        console.log("iff multi");
                    } else {
                        // const Naveen = [];
                        const selectedFeatures = res.data.result.map(
                            (item) => item.my_feature_id
                        );
                        this.checkboxValues = this.labels.map((label) =>
                            selectedFeatures.includes(label.id)
                        );
                        // this.checkboxValues = Naveen;
                    }

                    // let ddd = {
                    //     name: res.data.result.name,
                    //     description: res.data.result.product_price,
                    //     image: res.data.result.product_discount,
                    // };

                    // this.features = [];
                    // this.features.push(ddd);

                    // Handle response
                    // this.$router.push({ name: AddFeatureVue});
                })
                .catch((error) => {
                    console.error(error);
                    // Handle error
                });
        },
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
<style scoped>
.form-control {
    width: 80%;
}
.inputField {
    display: flex;
    margin-bottom: 20px;
}
.capitalize {
    text-transform: capitalize !important;
    margin-left: 3px;
}
.imgRight {
    /* color: white !important; */
    float: right !important;
    margin-right: 60px;
}
</style>
