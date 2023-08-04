<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {onMounted, reactive} from "vue";
import {getToday} from "@/common";
import axios from "axios";
import {Link} from "@inertiajs/inertia-vue3";
import Chart from "@/Components/Chart.vue";
import ResultTable from "@/Components/ResultTable.vue";

onMounted( () => {
    form.startDate = getToday();
    form.endDate = getToday();
})

const form = reactive({
    startDate:null,
    endDate:null,
    type:'perDay',
    rfmPrms: [
        14, 28, 60, 90, 7, 5, 3, 2, 300000, 200000, 100000,
        30000
    ],
});

const data = reactive({});

const getData = async() => {
    try {
        await axios.get('/api/analysis', {
            params: {
                startDate: form.startDate,
                endDate: form.endDate,
                type: form.type,
                rfmPrms: form.rfmPrms
            }
        })
            .then(res => {
                data.data = res.data.data;
                if (res.data.labels) data.labels = res.data.labels;
                if (res.data.eachCount) data.eachCount = res.data.eachCount;
                data.totals = res.data.totals;
                data.type = res.data.type;
                console.log(data.eachCount);
            })
    } catch (e) {
        console.log(e.message);
    }
}


</script>

<template>
    <Head title="データ分析" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">データ分析</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="container px-5 py-10 mx-auto">
                        <form @submit.prevent="getData">
                            From:<input type="date" name="startDate" v-model="form.startDate"/>
                            To:<input  type="date" name="endDate" v-model="form.endDate"/>
                            <div class="p-2 w-full">
                                <p>分析方法</p>
                                <input type="radio" id="perDay" name="type" v-model="form.type" value="perDay"
                                       checked><label for="perDay" class="mr-4">日別</label>
                                <input type="radio" id="perMonty" name="type" v-model="form.type" value="perMonth"
                                       ><label for="perMonty" class="mr-4">月別</label>
                                <input type="radio" id="perYear"  name="type" v-model="form.type" value="perYear"
                                       ><label for="perYear" class="mr-4">年別</label>
                                <input type="radio" id="decile" name="type" v-model="form.type" value="decile"
                                ><label for="decile" class="mr-4">デシル分析</label>
                                <input type="radio" id="rfm" name="type" v-model="form.type"
                                       value="rfm"><label for="rfm" class="mr-4">RFM分析</label>
                            </div>
                            <div v-if="form.type === 'rfm' " class="my-4">
                                <table class="mx-auto">
                                    <thead>
                                    <tr>
                                        <th>ランク</th>
                                        <th>R (○日以内)</th>
                                        <th>F (○回以上)</th>
                                        <th>M (○円以上)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>5</td>
                                        <td><input type="number" v-model="form.rfmPrms[0]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[4]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[8]"></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td><input type="number" v-model="form.rfmPrms[1]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[5]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[9]"></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td><input type="number" v-model="form.rfmPrms[2]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[6]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[10]"></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td><input type="number" v-model="form.rfmPrms[3]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[7]"></td>
                                        <td><input type="number" v-model="form.rfmPrms[11]"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-2 w-full">
                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析</button>
                            </div>
                        </form>

                        <div v-show="data.data">
                            <div v-if="data.type !== 'rfm' ">
                                <Chart :data="data" />
                            </div>
                        <ResultTable :data="data" />
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
