<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm  } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Matches',
        href: '/Matches/Results',
    },
];


const form = useForm({
    'home_team': '',
    'away_team': '',
    'home_score': '-',
    'away_score': '-',
    'scheduled_time': '',
});

const createMatch = () => form.post(route('matches.store'));

</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class="container mx-auto p-6 max-w-4xl">
                    <div class="rounded-lg border border-gray-300 shadow-md overflow-hidden bg-white dark:bg-gray-800 mb-8">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white">Create Match</h1>
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
                                                <Input class="text-center" id="home_team" v-model="form.home_team" placeholder="Home Team" ></Input>
                                                <InputError :message="form.errors.home_team"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="home_score" v-model="form.home_score" placeholder="Home Score" ></Input>
                                                <InputError :message="form.errors.home_score"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="away_team" v-model="form.away_team" placeholder="Away Team" ></Input>
                                                <InputError :message="form.errors.away_team"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <Input class="text-center" id="away_score" v-model="form.away_score" placeholder="Away Score" ></Input>
                                                <InputError :message="form.errors.away_score"></InputError>
                                            </TableCell>
                                            <TableCell class="text-center">
                                                <input class="text-center w-full p-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700" id="scheduled_time" v-model="form.scheduled_time" type="datetime-local" />
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
