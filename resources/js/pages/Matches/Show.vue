<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, SharedData } from '@/types';
import { Head, router, usePage } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Button } from '@/components/ui/button';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const page = usePage<SharedData>();
const props = defineProps(['match']);
const isAdmin = page.props.auth.user.is_admin; //SQLite have just numbers (1,0) instead of boolean(true,false), in other DB change also type in types/index.ts

</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="container mx-auto p-6 max-w-4xl">
                    <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden bg-white dark:bg-gray-800 mb-8">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Match Details</h1>
                        </div>
                        <div class="p-4">
                            <Table class="w-full">
                                <TableHeader>
                                    <TableRow class="bg-gray-50 dark:bg-gray-700">
                                        <TableHead class="text-center font-semibold">Home Team</TableHead>
                                        <TableHead class="text-center font-semibold">Score</TableHead>
                                        <TableHead class="text-center font-semibold">Away Team</TableHead>
                                        <TableHead class="text-center font-semibold">Match Date</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <TableCell class="text-center font-medium">{{ props.match.home_team }}</TableCell>
                                        <TableCell class="text-center font-bold text-red-600 text-xl">
                                            {{ props.match.home_score }} : {{ props.match.away_score }}
                                        </TableCell>
                                        <TableCell class="text-center font-medium">{{ props.match.away_team }}</TableCell>
                                        <TableCell class="text-center">{{ props.match.scheduled_time}}</TableCell>
                                        <TableCell v-if="isAdmin === 1 || isAdmin === true" class="text-center">
                                            <Button @click="router.visit(`${page.url}/edit`)" class="cursor-pointer bg-blue-600 hover:bg-blue-700">Edit</Button>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <div v-if="props.match.bets && props.match.bets.length" class="rounded-lg border border-gray-300 shadow-md overflow-hidden bg-white dark:bg-gray-800">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h2 class="text-xl font-bold text-center text-gray-800 dark:text-white">All Bets</h2>
                        </div>
                        <div class="p-4">
                            <Table class="w-full">
                                <TableHeader>
                                    <TableRow class="bg-gray-50 dark:bg-gray-700">
                                        <TableHead class="text-center font-semibold">User</TableHead>
                                        <TableHead class="text-center font-semibold">Prediction</TableHead>
<!--                                        <TableHead class="text-center">Actual Score</TableHead>-->
                                        <TableHead class="text-center font-semibold">Points</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="bet in props.match.bets"
                                        :key="bet.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700"
                                        :class="{'bg-blue-50 dark:bg-blue-900/30': bet.user_id === page.props.auth.user.id}"
                                    >
                                        <TableCell class="text-center">
                                            {{ bet.user ? bet.user.name : 'Unknown User' }}
                                            <span v-if="bet.user_id === page.props.auth.user.id" class="text-xs text-blue-600 dark:text-blue-400 ml-1">(You)</span>
                                        </TableCell>
                                        <TableCell class="text-center font-bold">
                                            {{ bet.prediction_home }} : {{ bet.prediction_away }}
                                        </TableCell>
<!--                                        <TableCell class="text-center">-->
<!--                                            {{ props.match.home_score }} : {{ props.match.away_score }}-->
<!--                                        </TableCell>-->
                                        <TableCell class="text-center font-medium">{{ bet.points_awarded }}</TableCell>
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
