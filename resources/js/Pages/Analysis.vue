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
    type:'perDay'
});

const data = reactive({});

const getData = async() => {
    try {
        await axios.get('/api/analysis', {
            params: {
                startDate: form.startDate,
                endDate: form.endDate,
                type: form.type
            }
        })
            .then(res => {
                data.data = res.data.data;
                data.labels = res.data.labels;
                data.totals = res.data.totals;
                data.type = res.data.type;
                console.log(res.data.data);
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
                                <input type="radio" id="perDay" v-model="form.type" value="perDay"
                                       checked><label for="perDay" class="mr-4">日別</label>
                                <input type="radio" id="perMonty" v-model="form.type" value="perMonth"
                                       ><label for="perMonty" class="mr-4">月別</label>
                                <input type="radio" id="perYear" v-model="form.type" value="perYear"
                                       ><label for="perYear" class="mr-4">年別</label>
                                <input type="radio" id="decile" v-model="form.type" value="decile"
                                ><label for="decile" class="mr-4">デシル</label>
                            </div>
                            <div class="p-2 w-full">
                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">分析</button>
                            </div>
                        </form>
                        <div v-show="data.data">
                        <Chart :data="data" />
                        <ResultTable :data="data" />
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
