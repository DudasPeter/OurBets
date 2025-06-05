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

const props = defineProps(['match']);

const form = useForm({
    home_team: props.match.data.home_team,
    away_team: props.match.data.away_team,
    home_score: props.match.data.home_score,
    away_score: props.match.data.away_score,
    scheduled_time: props.match.data.scheduled_time,
});

const updateMatch = () => form.patch(route('matches.update', { id: props.match.data.id }));
</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 rounded-xl border md:min-h-min">
                <div class="container mx-auto p-6">
                    <div class="overflow-hidden rounded-lg border border-gray-300 shadow-md">
                        <form @submit.prevent="updateMatch">
                            <Table>
                                <TableHeader>
                                    <TableRow class="border-b">
                                        <TableHead class="w-[50px] px-6 py-4 text-center">Home Team</TableHead>
                                        <TableHead class="w-[30px] px-6 py-4 text-center font-bold">Final</TableHead>
                                        <TableHead class="w-[50px] px-6 py-4 text-center">Away Team</TableHead>
                                        <TableHead class="w-[30px] px-6 py-4 text-center font-bold">Final</TableHead>
                                        <TableHead class="w-[50px] px-6 py-4 text-center">Date/Time</TableHead>
                                        <TableHead class="w-[50px] px-6 py-4 text-center"></TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow class="border-b hover:bg-white">
                                        <TableCell class="px-6 py-4 text-center">
                                            <Input class="text-center" id="home_team" v-model="form.home_team" placeholder="Home Team"></Input>
                                            <InputError :message="form.errors.home_team"></InputError>
                                        </TableCell>
                                        <TableCell class="px-6 py-4 text-center">
                                            <Input class="text-center" id="home_score" v-model="form.home_score" placeholder="Home Score"></Input>
                                            <InputError :message="form.errors.home_score"></InputError>
                                        </TableCell>
                                        <TableCell class="px-6 py-4 text-center">
                                            <Input class="text-center" id="away_team" v-model="form.away_team" placeholder="Away Team"></Input>
                                            <InputError :message="form.errors.away_team"></InputError>
                                        </TableCell>
                                        <TableCell class="px-6 py-4 text-center">
                                            <Input class="text-center" id="away_score" v-model="form.away_score" placeholder="Away Score"></Input>
                                            <InputError :message="form.errors.away_score"></InputError>
                                        </TableCell>
                                        <TableCell>
                                            <div class="text-center">
                                                <input class="text-center" id="scheduled_time" v-model="form.scheduled_time" type="datetime-local" />
                                                <InputError :message="form.errors.scheduled_time"></InputError>
                                            </div>
                                        </TableCell>
                                        <TableCell>
                                            <Button class="cursor-pointer" type="submit">Submit</Button>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
