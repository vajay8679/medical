<template>
    <div>
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="product-info">
                        <form @submit.prevent="submitForm">
                            <div>
                                <label for="username">UserName</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="username"
                                    placeholder="Enter Username"
                                    v-model="ratingObject.userName"
                                    ref="userNameInput"
                                />
                                <span
                                    class="text-danger"
                                    v-if="errors.userName !== ''"
                                >
                                    {{ errors.userName }}
                                </span>
                            </div>
                            <div>
                                <label for="rating">Rating</label>
                                <input
                                    class="form-control"
                                    type="number"
                                    id="rating"
                                    placeholder="Enter Product Rating Only Integer Value"
                                    v-model="ratingObject.ratingNumber"
                                    ref="ratingNumberInput"
                                />
                                <span
                                    class="text-danger"
                                    v-if="errors.ratingNumber !== ''"
                                >
                                    {{ errors.ratingNumber }}
                                </span>
                            </div>
                            <div>
                                <label for="location">Location</label>
                                <input
                                    class="form-control"
                                    type="text"
                                    id="location"
                                    placeholder="Enter Location"
                                    v-model="ratingObject.location"
                                    ref="locationInput"
                                />
                                <span
                                    class="text-danger"
                                    v-if="errors.location !== ''"
                                >
                                    {{ errors.location }}
                                </span>
                            </div>

                            <div>
                                <label for="desc">Review</label>
                                <textarea
                                    class="form-control"
                                    placeholder="Enter Review"
                                    v-model="ratingObject.review"
                                    name="w3review"
                                    rows="4"
                                    cols="50"
                                    ref="reviewInput"
                                ></textarea>
                                <span
                                    class="text-danger"
                                    v-if="errors.review !== ''"
                                >
                                    {{ errors.review }}
                                </span>
                            </div>

                            <button
                                class="mt-4 ml-4 btn btn-primary radius"
                                type="submit"
                            >
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <span> Table to display reviews</span>
        <hr />
        <div class="table-responsive">
            <table class="table mt-4" v-if="reviews.length > 0">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>User Name</th>
                        <th>Rating</th>
                        <th>Location</th>
                        <th>Review</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(review, index) in reviews" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td class="capitalize">{{ review.user_name }}</td>
                        <td class="capitalize">{{ review.rating }}</td>
                        <td class="capitalize">{{ review.location }}</td>
                        <td class="capitalize">
                            {{ getFirstFiveWords(review.review) }}
                        </td>
                        <td>
                            <i class="fa fa-pencil-square-o fa-lg" @click="editReview(index)"></i>
                            <i class="fa fa-trash-o fa-lg m-l-5" @click="deleteReview(index)"></i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="reviews != null">
            <ul class="pagination justify-content-end pagination-primary">
                <li
                    style="margin-top: 2px; margin-right: 5px"
                    class="page-item d-flex"
                >
                    <span class="mt-3 mx-1" style="color: black"
                        >Total Count
                        <button
                            style="
                                border: none;
                                margin-top: 7px;
                                border-radius: 7px;
                                color: white;
                                background: rgb(13, 110, 253);
                                height: 30px;
                            "
                        >
                            {{ pagination.total }}
                        </button></span
                    >
                </li>
                <li>
                    <Paginations
                        :totalCount="pagination.total"
                        :currentPage="pagination.current_page"
                        :pageSize="pagination.per_page"
                        @page-change="handlePageChange"
                    />
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import config from "../../config";
import axios from "axios";
import Paginations from "../Paginations.vue";

export default {
    components: {
        Paginations,
    },
    data() {
        return {
            EditIds: null,
            parentId: "",
            ratingObject: {
                userName: "",
                ratingNumber: null,
                location: "",
                review: "",
            },
            errors: {
                userName: "",
                ratingNumber: "",
                location: "",
                review: "",
            },
            pagination: {
                current_page: null,
                last_page: null,
                per_page: null,
                total: null,
            },
            reviews: [], // Array to store submitted reviews
        };
    },
    methods: {
        submitForm() {
            this.errors = {
                userName: "",
                ratingNumber: "",
                location: "",
                review: "",
            };

            if (!this.ratingObject.userName) {
                this.errors.userName = "The userName Field is required";
            }
            if (!this.ratingObject.ratingNumber) {
                this.errors.ratingNumber = "The ratingNumber Field is required";
            } else if (this.ratingObject.ratingNumber > 5) {
                this.errors.ratingNumber =
                    "The rating should not be greater than 5";
            }
            if (!this.ratingObject.location) {
                this.errors.location = "The location Field is required";
            }
            if (!this.ratingObject.review) {
                this.errors.review = "The review Field is required";
            }

            if (Object.values(this.errors).some((error) => error !== "")) {
                // Focus on the first input field with an error
                if (this.errors.userName !== "") {
                    this.$refs.userNameInput.focus();
                } else if (this.errors.ratingNumber !== "") {
                    this.$refs.ratingNumberInput.focus();
                } else if (this.errors.location !== "") {
                    this.$refs.locationInput.focus();
                } else if (this.errors.review !== "") {
                    this.$refs.reviewInput.focus();
                }
                return;
            }

            const data = {
                user_name: this.ratingObject.userName,
                rating: this.ratingObject.ratingNumber,
                location: this.ratingObject.location,
                review: this.ratingObject.review,
                parent_id: this.parentId,
            };

            let token = localStorage.getItem("token");

            if (this.EditIds !== null) {
                const reviewId = this.reviews[this.EditIds].id;
                // console.log(reviewId, "delete");
                axios
                    .post(
                        `${config.apiUrl}/api/my-product-update-review/${reviewId}`,
                        data,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                "Content-Type": "application/json",
                            },
                        }
                    )
                    .then((res) => {
                        this.ratingObject = {
                            userName: "",
                            ratingNumber: null,
                            location: "",
                            review: "",
                        };
                        this.EditIds = null;
                        window.location.reload();
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } else {
                axios
                    .post(`${config.apiUrl}/api/my-product-review`, data, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json",
                        },
                    })
                    .then((res) => {
                        this.ratingObject = {
                            userName: "",
                            ratingNumber: null,
                            location: "",
                            review: "",
                        };
                        window.location.reload();
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },

        editReview(index) {
            const reviewId = this.reviews[index].id;
            // console.log(reviewId, "edit");

            let token = localStorage.getItem("token");
            axios
                .get(
                    `${config.apiUrl}/api/my-product-get-single-review/${reviewId}`,
                    {
                        headers: {
                            Authorization: `Bearer ${token}`,
                            "Content-Type": "application/json",
                        },
                    }
                )
                .then((res) => {
                    console.log(res);
                    this.ratingObject.userName = res.data.result.user_name;
                    this.ratingObject.ratingNumber = res.data.result.rating;
                    this.ratingObject.location = res.data.result.location;
                    this.ratingObject.review = res.data.result.review;

                    this.EditIds = index;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        deleteReview(index) {
            const reviewId = this.reviews[index].id;
            // console.log(reviewId, "delete");

            let token = localStorage.getItem("token");
            if (confirm("Do you really want to delete?")) {
                axios
                    .delete(
                        `${config.apiUrl}/api/my-product-delete-review/${reviewId}`,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        }
                    )
                    .then((res) => {
                        this.getAllReviews(this.parentId);
                        // this.reviews.splice(index, 1);
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },

        getAllReviews(id) {
            let token = localStorage.getItem("token");

            axios
                .get(`${config.apiUrl}/api/my-product-get-review/${id}`, {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                })
                .then((res) => {
                    // console.log(res, "res");
                    this.reviews = res.data.result.data;
                    this.pagination = res.data.result.pagination;
                })
                .catch((error) => {
                    console.error(error);
                });
        },

        handlePageChange(page) {
            console.log(page, "fffffpage");
            this.pagination.current_page = page;
            let token = localStorage.getItem("token");
            if (this.filterQuery) {
                axios
                    .get(
                        `${config.apiUrl}/api/lead-form?page=${this.pagination.current_page}&search=${this.filterQuery}`,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                            },
                        }
                    )
                    .then((res) => {
                        console.log("Error", res);
                        // this.apiResFormList = res.data.result.data;
                        this.reviews = res.data.result.data;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            } else {
                axios
                    .get(
                        `${config.apiUrl}/api/my-product-get-review/${this.parentId}/?page=${this.pagination.current_page}`,
                        {
                            headers: {
                                Authorization: `Bearer ${token}`,
                                "Content-Type": "application/json",
                            },
                        }
                    )
                    .then((res) => {
                        // console.log("Error", res);

                        // this.apiResFormList = res.data.result.data;
                        this.reviews = res.data.result.data;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }

            console.log("Requested page:", page);
        },
    },
    computed: {
        getFirstFiveWords() {
            return (review) => {
                const words = review.split(" ");
                // console.log(words,'words')
                return words.length > 5
                    ? words.slice(0, 5).join(" ") + "..."
                    : review;
            };
        },
    },

    mounted() {
        const url = new URL(window.location.href);
        const id = url.pathname.split("/").pop();
        const isNum = /^\d+$/.test(id);
        if (isNum) {
            // this.getFormDataW(id);
            this.getAllReviews(id);
            this.parentId = id;
        }
    },
};
</script>

<style scoped>
/* Your styles here... */
.radius {
    border-radius: 22px !important;
}
.capitalize {
    text-transform: capitalize !important;
}

@media only screen and (width: 425px) {
    .tableResponsive {
        width: 100vh !important;
    }
}
@media only screen and (width: 320px) {
    .tableResponsive {
        margin-left: 148px !important;
    }
}
@media only screen and (width: 375px) {
    .tableResponsive {
        margin-left: 200px !important;
    }
}
</style>
