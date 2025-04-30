<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  user: Object,
  totalPoints: Number,
  rank: Number,
  stats: Object,
  recentBets: Array,
  upcomingBets: Array
});

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString();
};
</script>

<template>
  <Head title="My Statistics" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        My Betting Statistics
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Summary Stats -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Overall Performance
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Rank</div>
              <div class="text-2xl font-bold">#{{ rank }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Points</div>
              <div class="text-2xl font-bold">{{ totalPoints }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Bets</div>
              <div class="text-2xl font-bold">{{ stats.totalBets }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Accuracy</div>
              <div class="text-2xl font-bold">{{ stats.accuracy

