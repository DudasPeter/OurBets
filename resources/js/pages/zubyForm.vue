<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
});

const submitForm = () => {
    form.post('/zubyForm');
};
</script>

<template>
    <div
        class="flex h-14 min-h-screen flex-col items-center justify-center bg-[#FDFDFC] bg-linear-to-bl from-violet-500 to-fuchsia-500 p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a]"
    >
        <div class="mx-auto w-full rounded-lg bg-cyan-400 p-6 shadow-2xl ring shadow-yellow-500 ring-yellow-500 lg:w-2/3 dark:bg-[#1b1b18]">
            <h1 class="text-center text-4xl font-bold">Čo navrhuješ?</h1>
            <div class="mt-6 flex list-inside flex-col items-center justify-center space-y-6 text-center text-lg">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- <label class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100" for="name">Čo navrhuješ?</label> -->
                    <input
                        type="text"
                        name="name"
                        id="name"
                        v-model="form.name"
                        class="block w-full rounded-md border-0 py-1.5 text-sm shadow-xl ring-1 shadow-orange-300 ring-yellow-300 ring-inset placeholder:text-gray-400 focus:ring-2 focus:ring-indigo-600 focus:ring-inset sm:leading-6"
                    />
                    <Button variant="outline" type="submit" :disabled="form.processing">Toto chcem!</Button>
                </form>
            </div>
        </div>
    </div>
</template>
