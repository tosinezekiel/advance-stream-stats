<template>
    <div>
        <div id="dropin-container"></div>
        <div class="mx-auto max-w-2xl py-24 px-4 sm:px-6 lg:px-8" v-if="state.selectedPlan">
            <span class="text-3xl italic" v-if="state.processSubscription">Your subscription is in progress...</span>
            <div class="sm:align-center sm:flex sm:flex-col" v-else>
                <Payment 
                    v-if="state.showDropIn"
                    :authorization="state.token"
                    :paypal="{flow: 'vault'}"
                    @load="onLoad"
                    @loadFail="onLoadFail"
                    @success="onSuccess"
                    @error="onError"
                    >
                    <template v-slot:button="slotProps">
                        <button type="submit" @click="slotProps.submit" id="submit-button" class="mt-8 block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
                            Pay
                        </button>
                    </template>
                </Payment>
            </div>
        </div>
        <div class="bg-white" v-else>
            <div class="mx-auto max-w-7xl py-24 px-4 sm:px-6 lg:px-8" v-if="state.plans.length">
                <div class="sm:align-center sm:flex sm:flex-col">
                <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-center">Pricing Plans</h1>
                <p class="mt-5 text-xl text-gray-500 sm:text-center">Get unlimited access to live statistics on the go. Pro Account unlocks additional statistics.</p>
                
                </div>
                <div class="flex mx-auto justify-center max-w-8xl mt-12">
                    <div class="p-6 border rounded-md mr-3" v-for="(plan, index) in state.plans">
                        <h2 class="text-lg font-medium leading-6 text-gray-900 capitalize">{{ plan.name }}</h2>
                        <p class="mt-4 text-sm text-gray-500">Access to all advance streams <br> statistics.</p>
                        <p class="mt-8">
                            <span class="text-xl font-light text-gray-900">{{ plan.name == 'monthly' ? `Starting at` :  `Get up to 40% off on` }}</span><br>
                            <span class="text-4xl font-bold tracking-tight text-gray-900">${{ plan.price }}</span>
                            <span class="text-base font-medium text-gray-500">/{{ plan.name == 'monthly' ? `mo` :  `yr` }}</span>
                        </p>
                        <button @click="selectPlan(plan, plan.name)" class="mt-8 block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
                            Subscribe
                        </button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { reactive, onMounted } from "vue";
import { useRouter} from 'vue-router'
import Payment from '../Components/Payment.vue'
import SubscriptionService from "@/services/subscription";

    const router = useRouter();

    const state = reactive({
        token: null,
        instance: null,
        showDropIn: false,
        selectedPlan: null,
        type: "monthly",
        plans: [],
        processSubscription: false,
        subscription: null
    })

    function selectPlan(plan, type){
        state.selectedPlan = plan
        state.type = type
        state.showDropIn = true
    }

    function onLoad (instance) {
        state.instance = instance;
    }

    function onLoadFail (instance) {
        console.error('Load fail', instance);
    }

    function onSuccess (payload) {
        const data = {
            'token': payload.nonce,
            'planId': state.selectedPlan.id,
            'type': state.type
        }

        state.processSubscription = true;
        SubscriptionService.subscribe(data).then((response) => {
                Promise.resolve(response);
                state.processSubscription = true;
                router.go();
                router.push("/settings");
            },(error) => {
                state.processSubscription = false;
                alert(error.response.data.error)
                Promise.reject(error);
        })

    }

    function onError (error) {
        
    }

    function clearPaymentSelection () {
        if (state.instance != null) {
            state.instance.clearSelectedPaymentMethod();
        }
    }

    async function getAuthorization(){
        const token = SubscriptionService.getAuthorization();
        state.token = await token
    }

    async function getPlans(){
        let data = SubscriptionService.getPlans()
        state.plans = await data
    }

    onMounted(() => {
        getPlans();
        getAuthorization();
    })

</script>