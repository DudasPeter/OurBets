<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const props = defineProps(['bets']);

console.log(props.bets);

</script>

<template>
    <Head title="Bets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="container mx-auto p-6">
                    <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="text-center">User</TableHead>
                                    <TableHead class="text-center">Home Team</TableHead>
                                    <TableHead class="text-center">Score</TableHead>
                                    <TableHead class="text-center">Away Team</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="bet in props.bets" :key="bet.id"  class="hover:bg-white">
                                    <TableCell class="text-center">{{ bet.user.name }}</TableCell>
                                    <TableCell class="text-center">{{ bet.gamematch.home_team }}</TableCell>
                                    <TableCell class="text-center font-bold text-red-600">
                                        {{ bet.prediction_home }} : {{ bet.prediction_away }}
                                    </TableCell>
                                    <TableCell class="text-center">{{ bet.gamematch.away_team}}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
