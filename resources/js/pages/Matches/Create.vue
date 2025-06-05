<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];

const form = useForm({
    home_team: '',
    away_team: '',
    home_score: '-',
    away_score: '-',
    scheduled_time: '',
});

const createMatch = () => form.post(route('matches.store'));
</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="container mx-auto max-w-4xl p-6">
                    <div class="mb-8 overflow-hidden rounded-lg border border-gray-300 bg-white shadow-md dark:bg-gray-800">
                        <div class="border-b border-gray-200 bg-gray-50 p-4 dark:border-gray-600 dark:bg-gray-700">
                            <h1 class="text-center text-2xl font-bold text-gray-800 dark:text-white">Create Match</h1>
                        </div>
                        <div class="p-4">
                            <form @submit.prevent="createMatch">
                                <Table class="w-full">
                                    <TableHeader>
                                        <TableRow class="bg-gray-50 dark:bg-gray-700">
                                            <TableHead class="text-center font-semibold">Home Team</TableHead>
                                            <TableHead class="text-center font-semibold">Home Score</TableHead>
                                            <TableHead class="text-center font-semibold">Away Team</TableHead>
                                            <TableHead class="text-center font-semibold">Away Score</TableHead>
                                            <TableHead class="text-center font-semibold">Match Date</TableHead>
                                            <TableHead class="text-center font-semibold"></TableHead>
                                        </TableRow>
                                    </TableHeader>
                                    <TableBody>
                                        <TableRow class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="home_team" v-model="form.home_team" placeholder="Home Team"></Input>
                                                <InputError :message="form.errors.home_team"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="home_score" v-model="form.home_score" placeholder="Home Score"></Input>
                                                <InputError :message="form.errors.home_score"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="away_team" v-model="form.away_team" placeholder="Away Team"></Input>
                                                <InputError :message="form.errors.away_team"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="away_score" v-model="form.away_score" placeholder="Away Score"></Input>
                                                <InputError :message="form.errors.away_score"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <input
                                                    class="w-full rounded-md border border-gray-300 p-2 text-center dark:border-gray-600 dark:bg-gray-700"
                                                    id="scheduled_time"
                                                    v-model="form.scheduled_time"
                                                    type="datetime-local"
                                                />
                                                <InputError :message="form.errors.scheduled_time"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Button type="submit" class="bg-blue-600 hover:bg-blue-700">Submit</Button>
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
