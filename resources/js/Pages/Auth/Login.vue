<template>
    <div class="max-w-sm mx-auto px-4 py-8">
        <h1 class="text-3xl text-slate-800 font-bold">Login! ✨</h1>
        <span class="font-semibold text-gray-400">So nice to see you again ;)</span>
        <form>
            <div class="space-y-4 mt-2">
                <div>
                    <label for="email" class="block text-sm font-medium mb-1">
                        Email
                    </label>
                    <input v-model="email" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state.errors.hasOwnProperty('email')">
                        {{ state.errors.email[0] }}
                    </span>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium mb-1">
                        Password
                    </label>
                    <input v-model="password" class="pl-3 h-12 block w-full max-w-lg rounded-md border border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"/>
                    <span class="text-xs text-red-600" v-if="state.errors.hasOwnProperty('password')">
                        {{ state.errors.password[0] }}
                    </span>
                </div>
                <button
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest transition ease-in-out duration-150 btn bg-indigo-500 active:bg-indigo-900 hover:bg-indigo-600 text-white"    
                    @click.prevent="handleSubmit"
                    :disabled="state.loading"

                >
                    {{state.loading ? `Loading...` : `Submit`}}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useStore } from 'vuex';
import { useRouter} from 'vue-router'

const store = useStore()
const router = useRouter();

const email = ref("");
const password = ref("");

const state = reactive({ errors: {}, loading: false })

const handleSubmit = () => {
    state.loading = !state.loading;
    state.errors = {};
    store.dispatch("auth/login", {
            "email": email.value, 
            "password" : password.value
        }).then(() => {
            state.loading = false;
            router.push("/dashboard");
        },(error) => {
        state.loading = false;
        state.errors = error.response.data.errors;
    });
}
</script>