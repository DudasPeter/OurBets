<script setup lang="ts">
import { Pizza } from 'lucide-vue-next';
import Button from '@/components/ui/button/Button.vue';
import { router, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const redirectToForm = () => {
    router.visit('/zubyForm');
}


const page = usePage();

const errorFlashMessage = page.props.errors.message;
const successFlashMessage = page.props.flash.success;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    },
});

if (errorFlashMessage) {
    Toast.fire({
        icon: 'error',
        title: errorFlashMessage,
    });
}

if (successFlashMessage) {
    Toast.fire({
        icon: 'success',
        title: successFlashMessage,
    });
}

</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a] h-14 bg-linear-to-bl from-violet-500 to-fuchsia-500">
        <div
            class="w-full mx-auto rounded-lg bg-cyan-400 p-6 shadow-2xl ring ring-yellow-500 shadow-yellow-500 dark:bg-[#1b1b18] lg:w-2/3">
            <h1 class="text-4xl font-bold text-center">
                Čo si dnes dáme ?
            </h1>
            <ul class="flex mt-6 flex-col justify-center items-center text-center space-y-6 list-inside text-lg">
                <li class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-hamburger-icon lucide-hamburger block">
                        <path d="M12 16H4a2 2 0 1 1 0-4h16a2 2 0 1 1 0 4h-4.25" />
                        <path d="M5 12a2 2 0 0 1-2-2 9 7 0 0 1 18 0 2 2 0 0 1-2 2" />
                        <path d="M5 16a2 2 0 0 0-2 2 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 2 2 0 0 0-2-2q0 0 0 0" />
                        <path d="m6.67 12 6.13 4.6a2 2 0 0 0 2.8-.4l3.15-4.2" />
                    </svg>
                </li>
                <li>
                    <Pizza :size="48" />
                </li>
                <Button variant="outline" @click="redirectToForm">Buď kreatívna.</Button>
            </ul>
        </div>
    </div>
</template>
