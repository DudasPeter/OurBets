<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const page = usePage()
const props = defineProps(['match']);

console.log(props.match.scheduled_time);

</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="container mx-auto p-6">
                    <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="text-center">Home Team</TableHead>
                                    <TableHead class="text-center">Score</TableHead>
                                    <TableHead class="text-center">Away Team</TableHead>
                                    <TableHead class="text-center">Match Date</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow class="hover:bg-white">
                                    <TableCell class="text-center">{{ props.match.home_team }}</TableCell>
                                    <TableCell class="text-center font-bold text-red-600">
                                        {{ props.match.home_score }} : {{ props.match.away_score }}
                                    </TableCell>
                                    <TableCell class="text-center">{{ props.match.away_team }}</TableCell>
                                    <TableCell class="text-center">{{ props.match.scheduled_time}}</TableCell>
                                    <TableCell class="text-center">
                                        <Button @click="$inertia.visit(`${page.url}/edit`)" class="cursor-pointer">Edit</Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
