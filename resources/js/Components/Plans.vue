<template>
    <section>
        <div class="shadow sm:overflow-hidden sm:rounded-md">
            <div class="space-y-6 bg-white py-6 px-4 sm:p-6">
            <div>
                <h2 id="plan-heading" class="text-lg font-medium leading-6 text-gray-900">Plan</h2>
            </div>
                <fieldset>
                    <legend class="sr-only">Plans</legend>
                    <div class="relative -space-y-px rounded-md bg-white">
                    <div v-if="state.subscribed" class="rounded-tl-md rounded-tr-md relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3 focus:outline-none">
                        <span class="flex items-center text-sm">
                        <span class="ml-3 font-medium text-gray-900">Startup</span>
                        </span>
                        <span class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
                        <span class="font-medium">$29 / mo</span>
                        <span>($290 / yr)</span>
                        </span>
                        <span class="ml-6 pl-1 text-sm text-center">Active</span>
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
    import { ref, reactive } from "vue";
    const state = reactive({ subscribed: false })
</script>
