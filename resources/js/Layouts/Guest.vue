<template>
    <div class="flex h-screen overflow-hidden bg-gray-200">
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
            <main>
                <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-6xl mx-auto">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
</template>
<script setup>
    import Header from '../Components/Header.vue';
</script>