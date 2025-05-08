<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm  } from '@inertiajs/vue3';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
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

const createMatch = () => form.post(route('match.store'));



</script>

<template>
    <Head title="Matches" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <div class=" m-4 border border-red-500">
                    <form @submit.prevent="createMatch">
                        <Table>
                            <TableHeader>
                                <TableRow class="border-b">
                                    <TableHead class="w-[50px] text-center py-4 px-6">Home Team</TableHead>
                                    <TableHead class="w-[30px] font-bold text-center py-4 px-6">Final</TableHead>
                                    <TableHead class="w-[50px] text-center py-4 px-6">Away Team</TableHead>
                                    <TableHead class="w-[30px] font-bold text-center py-4 px-6">Final</TableHead>
                                    <TableHead class="w-[50px] text-center py-4 px-6">Date/Time</TableHead>
                                    <TableHead class="w-[50px] text-center py-4 px-6"></TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow class="border-b hover:bg-gray-50">
                                    <TableCell class="text-center py-4 px-6">
                                        <Input class="text-center" id="home_team" v-model="form.home_team" placeholder="Home Team" ></Input>
                                    </TableCell>                                    <TableCell class="text-center py-4 px-6">
                                        <Input class="text-center" id="home_score" v-model="form.home_score" placeholder="Home Score" ></Input>
                                    </TableCell>                                    <TableCell class="text-center py-4 px-6">
                                        <Input class="text-center" id="away_team" v-model="form.away_team" placeholder="Away Team" ></Input>
                                    </TableCell>                                    <TableCell class="text-center py-4 px-6">
                                        <Input class="text-center" id="away_score" v-model="form.away_score" placeholder="Away Score" ></Input>
                                    </TableCell>
                                    <TableCell>
                                        <div class="text-center">
                                            <input class="text-center" id="scheduled_time" v-model="form.scheduled_time" type="datetime-local" />
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Button type="submit">Submit</Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
