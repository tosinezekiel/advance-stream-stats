<template>
    <section>
        <div class="shadow sm:overflow-hidden sm:rounded-md">
            <div class="space-y-6 bg-white py-6 px-4 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg font-medium leading-6 text-gray-900">Subscriptions</h2>
            </div>
                <fieldset>
                    <legend class="sr-only">Subscriptions</legend>
                    <div class="relative -space-y-px rounded-md bg-white">
                        <div v-if="isSubscribed" >
                            <div class="mt-8 flex flex-col">
                            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Plan</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Ends At</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created At</th>
                                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">Action</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        <tr v-for="(subscription, index) in subscriptions">
                                            <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ subscription?.name }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ subscription?.braintree_plan }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ subscription?.ends_at || 'N/A' }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ subscription?.status }}</td>
                                            <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ subscription?.created_at }}</td>
                                            <td v-if="subscription?.status == 'Active'" class="relative whitespace-nowrap pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                                <button @click="cancelSubscription(subscription)" class="block w-full rounded-md border border-gray-800 bg-gray-800 py-2 text-center text-sm font-semibold text-white hover:bg-gray-900">
                                                    Cancel
                                                </button>  
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            </div>
                            </div>
                            </div>
                        </div>
                    <div v-else class="text-left relative flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.875 14.25l1.214 1.942a2.25 2.25 0 001.908 1.058h2.006c.776 0 1.497-.4 1.908-1.058l1.214-1.942M2.41 9h4.636a2.25 2.25 0 011.872 1.002l.164.246a2.25 2.25 0 001.872 1.002h2.092a2.25 2.25 0 001.872-1.002l.164-.246A2.25 2.25 0 0116.954 9h4.636M2.41 9a2.25 2.25 0 00-.16.832V12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 12V9.832c0-.287-.055-.57-.16-.832M2.41 9a2.25 2.25 0 01.382-.632l3.285-3.832a2.25 2.25 0 011.708-.786h8.43c.657 0 1.281.287 1.709.786l3.284 3.832c.163.19.291.404.382.632M4.5 20.25h15A2.25 2.25 0 0021.75 18v-2.625c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125V18a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        <span class="text-left text-sm">
                            You have no running subscription.</span>
                    </div>
                    </div>
                </fieldset>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6" v-if="state.subscribed">
            <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-gray-800 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-900">Cancel</button>
            </div>
        </div>
    </section>
</template>
<script setup>
    import { reactive, computed, onMounted } from "vue";
    import { useRouter} from 'vue-router';
    import { useStore } from 'vuex';

    const router = useRouter();
    const store = useStore();

    const state = reactive({
        processSubscription: false,
    })

    const isSubscribed = computed(() => {
        return store.getters['auth/isSubscribed']
    })

    const subscriptions = computed(() => {
        return store.getters['auth/getSubscriptions'];
    })

    function cancelSubscription(subscription){
        state.processSubscription = true;
        store.dispatch("auth/cancel", subscription.id).then(() => {
            state.processSubscription = false;
            router.go();
        },(error) => {
            state.processSubscription = false;
            alert(error.response.data.error)
        });
    } 
</script>
