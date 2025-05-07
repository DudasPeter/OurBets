<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Index',
    },
];

const props = defineProps(['matches']);

console.log(props.matches);
</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[100px]">
                                Home Team
                            </TableHead>
                            <TableHead >
                                Home Score
                            </TableHead>
                            <TableHead>Away Team</TableHead>
                            <TableHead >
                                Away Score
                            </TableHead>
                            <TableHead class="text-right">Scheduled time</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="match in props.matches" :key="match.id">
                            <TableCell>{{ match.home_team }}</TableCell>
                            <TableCell>{{ match.home_score }}</TableCell>
                            <TableCell>{{ match.away_team }}</TableCell>
                            <TableCell>{{ match.away_score }}</TableCell>
                            <TableCell class="text-right">{{ match.scheduled_time }}</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
<!--                <div class="m-4">-->
<!--                    <div v-for="match in props.matches" :key="match.id" class="border rounded-l border-amber-400">-->
<!--                        <h1>{{match.id}}</h1>-->
<!--                        <h1>Home Team: {{match.home_team}}</h1>-->
<!--                        <h1>Away Team: {{match.away_team}}</h1>-->
<!--                        <p>Home Score: {{match.home_score}}</p>-->
<!--                        <p>Away Score: {{match.away_score}}</p>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </AppLayout>
</template>
