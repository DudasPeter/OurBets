<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];
const props = defineProps(['match']);

const match = props.match[0]


</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class=" m-4 border border-red-500">
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
                            <TableRow class="hover:bg-gray-200 transition">
                                <TableCell class="text-center">{{ match.home_team }}</TableCell>
                                <TableCell class="text-center font-bold text-red-600">
                                    {{ match.home_score }} : {{ match.away_score }}
                                </TableCell>
                                <TableCell class="text-center">{{ match.away_team }}</TableCell>
                                <TableCell class="text-center">{{ match.scheduled_time}}</TableCell>
                                <TableCell class="text-center">
<!--                                    <Button href >Edit</Button>-->
<!--                                    <Link :href="route(url,['match', match.id])">EDIT</Link>-->
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
