<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const form = useForm({
    name: ''
});

const submitForm = () => {
    form.post('/zubyForm');
};
</script>


<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a] h-14 bg-linear-to-bl from-violet-500 to-fuchsia-500">
        <div
            class="w-full mx-auto rounded-lg bg-cyan-400 p-6 shadow-2xl ring ring-yellow-500 shadow-yellow-500 dark:bg-[#1b1b18] lg:w-2/3">
            <h1 class="text-4xl font-bold text-center">
                Čo navrhuješ?
            </h1>
            <div class="flex flex-col mt-6 justify-center items-center text-center space-y-6 list-inside text-lg">
                <form @submit.prevent="submitForm" class="space-y-4">
                    <!-- <label class="text-sm font-medium leading-6 text-gray-900 dark:text-gray-100" for="name">Čo navrhuješ?</label> -->
                    <input type="text" name="name" id="name" v-model="form.name" 
                    class="block w-full rounded-md border-0 py-1.5 text-sm shadow-xl shadow-orange-300 ring-1 ring-inset ring-yellow-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:leading-6">
                    <Button variant="outline" type="submit" :disabled="form.processing">Toto chcem!</Button>
                </form>
            </div>
        </div>
    </div>
</template>
