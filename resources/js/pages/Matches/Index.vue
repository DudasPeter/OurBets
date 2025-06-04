<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import Swal from 'sweetalert2'
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const  page = usePage();

const errorFlashMessage = page.props.errors.message
const successFlashMessage = page.props.flash.success

const Toast = Swal.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});


if (errorFlashMessage){
    Toast.fire({
        icon: "error",
        title: errorFlashMessage
    });
}

if (successFlashMessage){
    Toast.fire({
        icon: "success",
        title: successFlashMessage
    })
}

const props = defineProps(['matches']);

</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="container mx-auto p-6 max-w-4xl">
                    <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden bg-white dark:bg-gray-800 mb-8">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-white">🏆 Match Results</h2>
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
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer"
                                        @click="$inertia.visit(`/matches/results/${match.id}`)"
                                    >
                                        <TableCell class="text-center font-medium">{{ match.home_team }}</TableCell>
                                        <TableCell class="text-center font-medium">{{ match.away_team }}</TableCell>
                                        <TableCell v-if="match.home_score === '-' && match.away_score === '-'" class="text-center font-bold text-red-600 text-xl">
                                            -
                                        </TableCell>
                                        <TableCell v-else class="text-center font-bold text-red-600 text-xl">
                                            {{ match.home_score }} : {{ match.away_score }}
                                        </TableCell>
                                        <TableCell class="text-center">{{ match.scheduled_time}}</TableCell>
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
