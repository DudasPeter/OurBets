<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';

defineProps({
  bets: Object,
  totalPoints: Number,
  rank: Number
});

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString();
};
</script>

<template>
  <Head title="My Bets" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        My Betting History
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Stats Summary -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Your Stats
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Points</div>
              <div class="text-2xl font-bold">{{ totalPoints }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Rank</div>
              <div class="text-2xl font-bold">#{{ rank }}</div>
            </div>
          </div>
          
          <div class="mt-4 text-right">
            <Link :href="route('leaderboard.user-stats')" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
              View Detailed Stats
            </Link>
          </div>
        </div>
        
        <!-- Bets History -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Betting History
          </h3>
          
          <div v-if="bets.data.length === 0" class="text-gray-500 dark:text-gray-400">
            You haven't placed any bets yet.
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Match
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Date
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Your Bet
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Result
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Points
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="bet in bets.data" :key="bet.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ bet.match.home_team }} vs {{ bet.match.away_team }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ formatDateTime(bet.match.match_time) }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ bet.predicted_home_score }} - {{ bet.predicted_away_score }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <template v-if="bet.match.status === 'finished'">
                      {{ bet.match.home_score }} - {{ bet.match.away_score }}
                    </template>
                    <span v-else class="text-gray-500 dark:text-gray-400">
                      {{ bet.match.status === 'scheduled' ? 'Upcoming' : 'In Progress' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <template v-if="bet.match.status === 'finished'">
                      {{ bet.points_earned }}
                    </template>
                    <span v-else>-</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <Link :href="route('matches.show', bet.match.id)" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                      View
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div v-if="bets.data.length > 0" class="mt-4">
            <Pagination :links="bets.links" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

