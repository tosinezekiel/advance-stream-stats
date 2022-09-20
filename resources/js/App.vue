<template>
    <Guest v-if="!isLoggedIn">
        <router-view></router-view>
    </Guest>
    <Auth v-else>
        <router-view></router-view>
    </Auth>
</template>
<script setup>
    import { ref, computed } from "vue";
    import { useStore } from 'vuex';

    const store = useStore()

    const isLoggedIn = computed(() => {
        return store.getters['auth/isLoggedIn']
    })
</script>