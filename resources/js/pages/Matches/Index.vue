<script setup lang="ts">
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const page = usePage();

const errorFlashMessage = page.props.errors.message;
const successFlashMessage = page.props.flash.success;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
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

const props = defineProps(['matches']);
</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="container mx-auto max-w-4xl p-6">
                    <div class="mb-8 overflow-hidden rounded-lg border border-gray-300 bg-white shadow-md dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                            <h2 class="text-center text-2xl font-bold text-gray-800 dark:text-white">🏆 Match Results</h2>
                        </div>
                        <div class="p-4">
                            <Table class="w-full">
                                <TableHeader>
                                    <TableRow class="bg-gray-50 dark:bg-gray-700">
                                        <TableHead class="text-center font-semibold">Home Team</TableHead>
                                        <TableHead class="text-center font-semibold">Away Team</TableHead>
                                        <TableHead class="text-center font-semibold">Score</TableHead>
                                        <TableHead class="text-center font-semibold">Match Date</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="match in props.matches.data"
                                        :key="match.id"
                                        class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                                        @click="$inertia.visit(`/matches/results/${match.id}`)"
                                    >
                                        <TableCell class="text-center font-medium">{{ match.home_team }}</TableCell>
                                        <TableCell class="text-center font-medium">{{ match.away_team }}</TableCell>
                                        <TableCell
                                            v-if="match.home_score === '-' && match.away_score === '-'"
                                            class="text-center text-xl font-bold text-red-600"
                                        >
                                            -
                                        </TableCell>
                                        <TableCell v-else class="text-center text-xl font-bold text-red-600">
                                            {{ match.home_score }} : {{ match.away_score }}
                                        </TableCell>
                                        <TableCell class="text-center">{{ match.scheduled_time }}</TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
