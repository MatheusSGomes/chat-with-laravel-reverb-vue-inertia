<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ChatComponent from '@/Components/ChatComponent.vue';

const { friend } = defineProps({
    friend: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-medium leading-tight text-gray-800 dark:text-gray-200">
                {{ friend.name }} <span class="font-semibold">({{ friend.email }})</span>
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">
                        <p><strong>Nome:</strong> {{ friend.name }}</p>
                        <p><strong>Email:</strong> {{ friend.email }}</p>

                        <ChatComponent />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
