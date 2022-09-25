<template>
    <div>
        <div id="dropin-container"></div>
        <div class="mx-auto max-w-2xl py-24 px-4 sm:px-6 lg:px-8" v-if="selectedPlan">
            <span class="text-3xl italic" v-if="loading">Your subscription is in progress...</span>
            <div class="sm:align-center sm:flex sm:flex-col" v-if="!loading">
                <Payment 
                    v-if="showDropIn"
                    :authorization="token"
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
            <div class="mx-auto max-w-7xl py-24 px-4 sm:px-6 lg:px-8" v-if="plans.length">
                <div class="sm:align-center sm:flex sm:flex-col">
                <h1 class="text-5xl font-bold tracking-tight text-gray-900 sm:text-center">Pricing Plans</h1>
                <p class="mt-5 text-xl text-gray-500 sm:text-center">Get unlimited access to live statistics on the go. Pro Account unlocks additional statistics.</p>
                
                </div>
                <div class="flex mx-auto justify-center max-w-8xl mt-12" v-for="(plan, index) in plans">
                    <div class="p-6 border rounded-md mr-3">
                        <h2 class="text-lg font-medium leading-6 text-gray-900">Monthly</h2>
                        <p class="mt-4 text-sm text-gray-500">Access to all advance streams <br> statistics.</p>
                        <p class="mt-8">
                            <span class="text-xl font-light text-gray-900">Starting at</span><br>
                            <span class="text-4xl font-bold tracking-tight text-gray-900">${{ plan.price }}</span>
                            <span class="text-base font-medium text-gray-500">/mo</span>
                        </p>
                        <button @click="selectPlan(plan, 'monthly')" class="mt-8 block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
                            Subscribe
                        </button>      
                    </div>
                    <div class="p-6 border rounded-md mr-3">
                        <h2 class="text-lg font-medium leading-6 text-gray-900">Annually</h2>
                        <p class="mt-4 text-sm text-gray-500">Access to all advance streams <br> statistics.</p>
                        <p class="mt-8">
                            <span class="text-xl font-light text-gray-900">Get up to 40% off on</span><br>
                            <span class="text-4xl font-bold tracking-tight text-gray-900">${{ plan.price * 12 }}</span>
                            <span class="text-base font-medium text-gray-500">/yr</span>
                        </p>
                        <button @click="selectPlan(plan, 'yearly')" class="mt-8 block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
                            Subscribe
                        </button>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import Payment from '../Components/Payment.vue'
    import SubscriptionService from "@/services/subscription";
export default {
    components:{
        Payment
    },
    data(){
        return {
            token: null,
            instance: null,
            showDropIn: false,
            selectedPlan: null,
            type: "monthly",
            plans: [],
            loading: false,
            subscription: null
        }
    },
    methods:{
        selectPlan(plan, type){
            this.getAuthorization()
            this.selectedPlan = plan
            this.type = type
            this.showDropIn = true
        },
        onLoad (instance) {
            this.instance = instance;
        },
        onLoadFail (instance) {
            console.error('Load fail', instance);
        },
        onSuccess (payload) {
            console.log(payload);

            const data = {
                'token': payload.nonce,
                'planId': this.selectedPlan.id,
                'type': this.type
            }

            SubscriptionService.subscribe(data).then((subscription) => {
                console.log('Your subscription has started.');
                this.loading = true;
                return Promise.resolve(subscription);
                },(error) => {
                this.loading = false;
                console.log("error from backend" + error)
                return Promise.reject(error);
            })

        },
        onError (error) {
            console.log('onError' + error);
        },
        clearPaymentSelection () {
            if (this.instance != null) {
                this.instance.clearSelectedPaymentMethod();
            }
        },
        async getAuthorization(){
            const token = SubscriptionService.getAuthorization();
            this.token = await token
        },
        async getPlans(){
            let data = SubscriptionService.getPlans()
            this.plans = await data
        },

    },
    mounted(){
        this.getPlans();
    }
}
</script>
