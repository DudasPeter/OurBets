<script setup lang="ts">
import { Pagination, PaginationContent, PaginationEllipsis, PaginationItem, PaginationNext, PaginationPrevious } from '@/components/ui/pagination';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const props = defineProps(['bets']);

console.log(props.bets.data);
</script>

<template>
    <Head title="Bets" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="container mx-auto p-6">
                    <div class="overflow-hidden rounded-lg border border-gray-300 shadow-md">
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
                                <TableRow v-for="bet in props.bets.data" :key="bet.id" class="hover:bg-white">
                                    <TableCell class="text-center">{{ bet.user.name }}</TableCell>
                                    <TableCell class="text-center">{{ bet.game.home_team }}</TableCell>
                                    <TableCell class="text-center font-bold text-red-600">
                                        {{ bet.prediction_home }} : {{ bet.prediction_away }}
                                    </TableCell>
                                    <TableCell class="text-center">{{ bet.game.away_team }}</TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                        <Pagination
                            :items-per-page="props.bets.per_page"
                            :default-page="props.bets.current_page"
                            :total-items="props.bets.total"
                            @update:page="(newPage) => $inertia.get('/bets', { page: newPage })"
                        >
                            <PaginationContent>
                                <PaginationPrevious :disabled="!props.bets.prev_page_url" @click="$inertia.get(props.bets.prev_page_url)" />

                                <PaginationItem :value="1" :is-active="props.bets.current_page === 1" @click="$inertia.get('/bets', { page: 1 })">
                                    1
                                </PaginationItem>

                                <PaginationEllipsis v-if="props.bets.current_page > 3" />

                                <PaginationItem
                                    v-if="props.bets.current_page > 2"
                                    :value="props.bets.current_page - 1"
                                    :is-active="false"
                                    @click="$inertia.get('/bets', { page: props.bets.current_page - 1 })"
                                >
                                    {{ props.bets.current_page - 1 }}
                                </PaginationItem>

                                <PaginationItem
                                    v-if="props.bets.current_page !== 1"
                                    :value="props.bets.current_page"
                                    :is-active="true"
                                    @click="$inertia.get('/bets', { page: props.bets.current_page })"
                                >
                                    {{ props.bets.current_page }}
                                </PaginationItem>

                                <PaginationItem
                                    v-if="props.bets.current_page < props.bets.last_page - 1"
                                    :value="props.bets.current_page + 1"
                                    :is-active="false"
                                    @click="$inertia.get('/bets', { page: props.bets.current_page + 1 })"
                                >
                                    {{ props.bets.current_page + 1 }}
                                </PaginationItem>

                                <PaginationEllipsis v-if="props.bets.current_page < props.bets.last_page - 2" />

                                <PaginationItem
                                    v-if="props.bets.last_page !== 1 && props.bets.current_page !== props.bets.last_page"
                                    :value="props.bets.last_page"
                                    :is-active="props.bets.current_page === props.bets.last_page"
                                    @click="$inertia.get('/bets', { page: props.bets.last_page })"
                                >
                                    {{ props.bets.last_page }}
                                </PaginationItem>

                                <PaginationNext :disabled="!props.bets.next_page_url" @click="$inertia.get(props.bets.next_page_url)" />
                            </PaginationContent>
                        </Pagination>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
