<script setup>

import {Head, Link} from "@inertiajs/inertia-vue3";
import Pagination from "@/Components/Pagination.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Inertia} from "@inertiajs/inertia";
import dayjs from "dayjs";

const props = defineProps( {
    'orders': Object,
})
</script>

<template>
    <Head title="購買履歴" ></Head>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">購買履歴</h2>
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
                                <div class="lg:w-2/3 w-full mx-auto overflow-auto">
                                    <table class="table-auto w-full text-left whitespace-no-wrap">
                                        <thead>
                                        <tr>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">名前</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">合計金額</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">ステータス</th>
                                            <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">購入日</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <tr v-for="order in props.orders.data" :key="order.id">
                                            <td class="border-b-2 border-gray-200 px-4 py-3"><Link class="text-blue-400" :href="route('purchases.show', {purchase:order.id})">{{ order.id }}</Link></td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.customer_name }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3">{{ order.total }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3 ">{{ order.status }}</td>
                                            <td class="border-b-2 border-gray-200 px-4 py-3 ">{{ dayjs(order.created_at).format('YYYY-MM-DD HH:mm:ss') }}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="lg:w-2/3 w-full mx-auto"><Pagination class="mt-6" :links="props.orders.links"></Pagination></div>
                        </section>


                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
