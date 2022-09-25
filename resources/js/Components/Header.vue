<template>
  <nav class="bg-gray-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
        <div class="flex">
            <div class="hidden md:ml-6 md:flex md:items-center md:space-x-4">
                <router-link to="/dashboard" class="text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</router-link>
            </div>
        </div>
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <router-link to="/pricing" class="relative inline-flex items-center rounded-md border border-transparent bg-indigo-500 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                    </svg>
                    <span class="ml-2">Go pro</span>
                </router-link>
            </div>
            <div class="hidden md:ml-4 md:flex md:flex-shrink-0 md:items-center">
            <div class="relative ml-3">
                <div>
                    <button @click="toggle" type="button" class="w-10 h-10 bg-gray-100 rounded-full flex justify-center items-center" aria-expanded="false" aria-haspopup="true">
                            <span class="text-2xl">{{ nameInitial }}</span>
                    </button>
                </div>
                <div v-if="state.toggle" class="absolute left-0 right-2 z-10 mt-2 w-48 origin-top-left rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">

                    <router-link to="/settings" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-1">Settings</router-link>

                    <a href="#" @click.prevent="handleLogout" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Sign out</a>
                </div>
            </div>
            </div>
        </div>
        </div>
    </div>
  </nav>
</template>

<script setup>
    import { reactive, computed } from 'vue'
    import { useStore } from 'vuex';
    import { useRouter} from 'vue-router'
    import TokenService from "@/services/token";

    const store = useStore()
    const router = useRouter();

    const state = reactive({toggle:false})
    const toggle = () => {
        state.toggle = !state.toggle
    }

    const nameInitial = computed(() => {
        return TokenService.getUser().first_name.toUpperCase().charAt(0)
    })

    const handleLogout = () => {
        store.dispatch("auth/logout", {}).then(() => {
            router.push("/");
        });
    }
</script>