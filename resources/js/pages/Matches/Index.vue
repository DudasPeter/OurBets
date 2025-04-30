<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
  upcomingMatches: Array,
  recentMatches: Array,
  isAdmin: Boolean
});

const showCreateModal = ref(false);
const form = ref({
  home_team: '',
  away_team: '',
  match_time: '',
});
const errors = ref({});

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString();
};

const createMatch = () => {
  router.post(route('matches.store'), form.value, {
    onSuccess: () => {
      showCreateModal.value = false;
      form.value = {
        home_team: '',
        away_team: '',
        match_time: '',
      };
      errors.value = {};
    },
    onError: (err) => {
      errors.value = err;
    }
  });
};
</script>

<template>
  <Head title="Matches" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          Matches
        </h2>
        <button v-if="isAdmin" @click="showCreateModal = true" 
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
          Add Match
        </button>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Upcoming Matches -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Upcoming Matches
          </h3>
          
          <div v-if="upcomingMatches.length === 0" class="text-gray-500 dark:text-gray-400">
            No upcoming matches.
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Match
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Date & Time
                  </th>
                  <th scope="col"

