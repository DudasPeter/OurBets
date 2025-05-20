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

const page = usePage();
const props = defineProps(['match']);
const isAdmin = page.props.auth.user.is_admin;

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
                                    <TableCell v-if="isAdmin === 1 || isAdmin === true" class="text-center">
                                        <Button @click="$inertia.visit(`${page.url}/edit`)" class="cursor-pointer">Edit</Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>

                        <div v-if="props.match.bets && props.match.bets.length" class="mt-8">
                            <h2 class="text-xl font-bold mb-4">All Bets</h2>
                            <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden">
                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead class="text-center">User</TableHead>
                                            <TableHead class="text-center">Prediction</TableHead>
<!--                                            <TableHead class="text-center">Actual Score</TableHead>-->
                                            <TableHead class="text-center">Points</TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow
                                            v-for="bet in props.match.bets"
                                            :key="bet.id"
                                            class="hover:bg-white"
                                            :class="{'bg-blue-50': bet.user_id === page.props.auth.user.id}"
                                        >
                                            <TableCell class="text-center">
                                                {{ bet.user ? bet.user.name : 'Unknown User' }}
                                                <span v-if="bet.user_id === page.props.auth.user.id" class="text-xs text-blue-600 ml-1">(You)</span>
                                            </TableCell>
                                            <TableCell class="text-center font-bold">
                                                {{ bet.prediction_home }} : {{ bet.prediction_away }}
                                            </TableCell>
<!--                                            <TableCell class="text-center">-->
<!--                                                {{ props.match.home_score }} : {{ props.match.away_score }}-->
<!--                                            </TableCell>-->
                                            <TableCell class="text-center">{{ bet.points_awarded }}</TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
