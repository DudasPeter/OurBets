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

const flashMessage = page.props.errors.message


const errorToast = Swal.mixin({
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

if (flashMessage){
    errorToast.fire({
        icon: "error",
        title: flashMessage
    });
}


const props = defineProps(['matches']);

</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableHeader>
                        <TableRow class="border-b">
                            <TableHead class="w-[150px] text-center py-4 px-6">Home Team</TableHead>
                            <TableHead class="w-[100px] font-bold text-center py-4 px-6">Final</TableHead>
                            <TableHead class="w-[150px] text-center py-4 px-6">Away Team</TableHead>
                            <TableHead class="w-[100px] font-bold text-center py-4 px-6">Final</TableHead>
                            <TableHead class="w-[150px] text-center py-4 px-6">Status</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="match in props.matches" :key="match.id" class="border-b hover:bg-gray-50">
                            <TableCell class="text-center py-4 px-6">{{ match.home_team }}</TableCell>
                            <TableCell class="font-bold text-center py-4 px-6">{{ match.home_score }}</TableCell>
                            <TableCell class="text-center py-4 px-6">{{ match.away_team }}</TableCell>
                            <TableCell class="font-bold text-center py-4 px-6">{{ match.away_score }}</TableCell>
                            <TableCell class="text-center py-4 px-6">{{ match.status || match.scheduled_time }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AppLayout>
</template>
