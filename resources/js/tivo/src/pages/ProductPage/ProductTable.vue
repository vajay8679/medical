<template>
    <table class="custom-table" v-if="compareProduct.length>0" >
       
        <thead class="header-table">
            <tr>
                <th>Professional Series Built-in All-in-One Pump System™</th>
                <th v-for="item in compareProduct" :key="item.id" style="text-transform: capitalize">
                    {{ item.name }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in labels" :key="item.id">
                <td class="white tr-border table-text" style="text-transform: capitalize">
                    {{ item.name }}
                </td>

                <td v-for="product in compareProduct" :key="product.id">
                    <ul v-if="product.assign_feature.length > 0">
                        <li
                            v-for="(feature, index) in product.assign_feature"
                            :key="index"
                        >
                            <img
                                v-if="feature.id === item.id"
                                src="https://www.medicalsaunas.com/images/frozen/check-j16.png"
                                class="table-w"
                                alt="comapre tick "
                            />
                        </li>
                    </ul>
                    <ul v-else>
                        <li></li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
import config from "../../config";
import axios from "axios";

export default {
    props: {
        compareProduct: {
            type: Array,
            required: true,
            default: () => [],
        },
    },
    data() {
        return {
            labels: "",
        };
    },
    methods: {
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
                    this.checkboxValues = Array(this.labels.length).fill(false);
                })
                .catch((error) => {
                    console.error(error);
                });
        },
    },
    mounted() {
        this.fetchLabels();
    },
};
</script>

<style>
.custom-table img {
    width: 20px;
    /* height: 20px;
    vertical-align: middle; */
}

.custom-table {
    width: 100%;
    border-collapse: collapse;
}

.custom-table th {
    background-color: #fff;
    font-size: 16px;
}

.custom-table tbody tr:hover {
    background-color: #fff;
}

.custom-table th {
    background-color: #fff;
    font-size: 12px;
}
.custom-table td {
    padding: 8px;
    /* / text-align: left; / */
    border-bottom: 1px solid #fff;
    font-size: 15px;
}

.header-table {
    height: 74px;
}
</style>
