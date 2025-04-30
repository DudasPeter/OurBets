<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

defineProps({
  leaderboard: Object,
  userRank: Number,
  userPoints: Number
});
</script>

<template>
  <Head title="Leaderboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Global Leaderboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Your Position -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Your Position
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Rank</div>
              <div class="text-2xl font-bold">#{{ userRank }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Points</div>
              <div class="text-2xl font-bold">{{ userPoints }}</div>
            </div>
          </div>
          
          <div class="mt-4 text-right">
            <Link :href="route('leaderboard.user-stats')" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
              View Your Stats
            </Link>
          </div>
        </div>
        
        <!-- Global Rankings -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Global Rankings
          </h3>
          
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Rank
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    User
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Points
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="(user, index) in leaderboard.data" :key="user.id" :class="{'bg-indigo-50 dark:bg-indigo-900/20': user.id === $page.props.auth.user.id}">
                  <td class="px-6 py-4 whitespace-nowrap">
                    #{{ leaderboard.from + index }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ user.name }}
                    <span v-if="user.id === $page.props.auth.user.id" class="ml-2 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100">
                      You
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right font-medium">
                    {{ user.total_points }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mt-4">
            <Pagination :links="leaderboard.links" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

