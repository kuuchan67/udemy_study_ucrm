<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import FlashMessage from "@/Components/FlashMessage.vue";
import Pagination from "@/Components/Pagination.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref} from "vue";

const search = ref('');

defineProps({
    'customers':Object
});

const searchCustomers = () => {
    Inertia.get(route('customers.index'), {search:search.value});
}

</script>

<template>
    <Head title="顧客一覧" ></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">顧客一覧</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <section class="text-gray-600 body-font">

                            <div class="container px-5 py-10 mx-auto">
                                <FlashMessage />
                                <div class="flex pl-4 my-4 lg:w-2/3 w-full mx-auto">
                                    <input type="text" name="search" v-model="search">
                                    <button class="bg-blue-300 text-white py-2 px-2 ml-4" @click="searchCustomers">検索</button>
                                </div>
                                <div class="flex pl-4 my-4 lg:w-2/3 w-full mx-auto">
                                    <Link as="button" :href="route('customers.create')" class="flex ml-auto text-white bg-indigo-500 border-0 py-2 px-6 focus:outline-none hover:bg-indigo-600 rounded">顧客追加</Link>
                                </div>
                                <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">カナ</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">tel</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr v-for="customer in customers.data" :key="customer.id">
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.id }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.name }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ customer.kana }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3 ">{{ customer.tel }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="lg:w-2/3 w-full mx-auto"><Pagination class="mt-6" :links="customers.links"></Pagination></div>
                        </section>


                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
