<script setup>

import {getToday} from "@/common";
import {onMounted, reactive, ref, computed} from "vue";
import {Inertia} from "@inertiajs/inertia";
import {Head, Link} from "@inertiajs/inertia-vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import Micromodal from "@/Components/Micromodal.vue";




const props = defineProps({
    //'customers': Array,
    'items': Array,

})

const itemList = ref([]);

const form = reactive({
    date:null,
    customer_id:null,
    status:true,
    items:[]
});

onMounted(() => {
    form.date = getToday();
    props.items.forEach(item => {
        itemList.value.push({
            id:item.id,
            name: item.name,
            price: item.price,
            quantity: 0
        })
    })
})

const totalPrice = computed( () => {
    let total = 0;
    itemList.value.forEach(item => {
        total += item.price * item.quantity;
    })

    return total;
})

const storePurchase = () => {
    itemList.value.forEach(item => {
        if (item.quantity > 0) {
            form.items.push({
                id: item.id,
                quantity: item.quantity,

            })
        }
    })
    Inertia.post(route('purchases.store'), form);
}

const quantity = ["0", "1", "2", "3", "4", "5"];

const setCustomerId = id => { form.customer_id = id }

</script>

<template>

    <Head title="購入画面" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">購入画面</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <section class="text-gray-600 body-font relative">
                            <form @submit.prevent="storePurchase">
                                <div class="container px-5 py-4 mx-auto">
                                    <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                        <div class="p-2 w-full">
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label for="date" class="leading-7 text-sm text-gray-600">購入日</label>
                                                    <input type="date" id="date" name="date" v-model="form.date" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
<!--                                                    <InputError :message="errors.date"  class="mt-2"/>-->
                                                </div>
                                            </div>

                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label class="leading-7 text-sm text-gray-600">顧客名</label>
                                                    <Micromodal @update:customerId="setCustomerId" />

<!--                                                    <select name="customer" v-model="form.customer_id" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">-->
<!--                                                        <option v-for="customer in customers" :value="customer.id" :key="customer.id">-->
<!--                                                            {{customer.id}} :{{customer.name}}-->
<!--                                                        </option>-->
<!--                                                    </select>-->
<!--                                                    <InputError :message="errors.customer_id" class="mt-2"  />-->
                                                </div>
                                            </div>
                                            <div class="p-2 w-full　mt-8">
                                                <table class="table-auto w-full text-left whitespace-no-wrap">
                                                    <thead>
                                                    <tr>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100 rounded-tl rounded-bl">id</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">商品名</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">価格</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">数量</th>
                                                        <th class="px-4 py-3 title-font tracking-wider font-medium text-gray-900 text-sm bg-gray-100">小計</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    <tr v-for="item in itemList" :key="item.id">
                                                        <td class="border-b-2 border-gray-200 px-4 py-3">{{item.id}}</td>
                                                        <td class="border-b-2 border-gray-200 px-4 py-3">{{item.name}}</td>
                                                        <td class="border-b-2 border-gray-200 px-4 py-3">{{item.price}}</td>
                                                        <td class="border-b-2 border-gray-200 px-4 py-3 ">
                                                            <select name="quantity" v-model="item.quantity">
                                                                <option v-for="q in quantity" :value="q" :key="q">
                                                                    {{q}}
                                                                </option>
                                                            </select>
                                                        </td>
                                                        <td class="border-b-2 border-gray-200 px-4 py-3 ">{{item.price * item.quantity}}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="p-2 w-full">
                                                <div class="relative">
                                                    <label class="leading-7 text-sm text-gray-600">合計金額</label>
                                                    <div class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                                        {{totalPrice}}円
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="p-2 w-full">
                                                <button class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </div>
<!--    <form @submit.prevent="storePurchase">-->
<!--    日付-->
<!--    <input type="date" name="date" v-model="form.date"><br>-->
<!--    顧客名<br>-->
<!--    <select name="customer" v-model="form.customer_id">-->
<!--        <option v-for="customer in customers" :value="customer.id" :key="customer.id">-->
<!--           {{customer.id}} :{{customer.name}}-->
<!--        </option>-->
<!--    </select>-->
<!--    <br>-->
<!--    商品サービス<br>-->
<!--    <table>-->
<!--        <thead>-->
<!--            <tr>-->
<!--                <th>ID</th>-->
<!--                <th>商品名</th>-->
<!--                <th>価格</th>-->
<!--                <th>数量</th>-->
<!--                <th>小計</th>-->
<!--            </tr>-->
<!--        </thead>-->
<!--        <tbody>-->
<!--        <tr v-for="item in itemList">-->
<!--            <td>{{item.id}}</td>-->
<!--            <td>{{item.name}}</td>-->
<!--            <td>{{item.price}}</td>-->
<!--            <td><select name="quantity" v-model="item.quantity">-->
<!--                <option v-for="q in quantity" :value="q" :key="q">-->
<!--                    {{q}}-->
<!--                </option>-->
<!--            </select></td>-->
<!--            <td>{{item.price * item.quantity}}</td>-->
<!--        </tr>-->
<!--        </tbody>-->
<!--    </table>-->
<!--    <p>合計</p>-->
<!--    {{totalPrice}}円<br>-->
<!--        <button>登録する</button>-->
<!--    </form>-->
    </AuthenticatedLayout>
</template>
