<template>
    <div class="mt-5">
        <h1 class="text-3xl">Hi {{ displayName }}, Welcome home!😊</h1>
    </div>
    <div class="mt-20">
        <h3 class="text-lg font-medium leading-6 text-gray-900">Overview</h3>
        <Overview />
    </div>
    <div class="mt-8">
        <h3 class="text-lg font-medium leading-6 text-gray-900">
            Pro Stats 
            <span class="ml-2 inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">Pro users only</span>
        </h3>
        <BlankStats v-if="!isSubscribed" />
        <ProStats v-if="isSubscribed" />
    </div>
</template>
<script setup>
    import Overview from '../Components/Overview.vue'
    import ProStats from '../Components/ProStats.vue'
    import BlankStats from '../Components/BlankStats.vue'
    import { computed } from "vue"
    import TokenService from "@/services/token";

    const displayName = computed(() => {
        return TokenService.getUser().first_name
    })

    const isSubscribed = computed(() => {
        return TokenService.getUser().subscribed
    })

</script>
