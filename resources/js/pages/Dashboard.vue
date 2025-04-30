<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

const upcomingMatches = ref([]);
const recentMatches = ref([]);
const userStats = ref({});
const loading = ref(true);

onMounted(async () => {
  try {
    // Get data for dashboard
    const [matchesResponse, statsResponse] = await Promise.all([
      axios.get(route('matches.index')),
      axios.get(route('leaderboard.user-stats'))
    ]);
    
    upcomingMatches.value = matchesResponse.data.upcomingMatches?.slice(0, 5) || [];
    recentMatches.value = matchesResponse.data.recentMatches?.slice(0, 5) || [];
    userStats.value = statsResponse.data;
    loading.value = false;
  } catch (error) {
    console.error('Error loading dashboard data:', error);
    loading.value = false;
  }
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div v-if="loading" class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <p class="text-gray-500 dark:text-gray-400">Loading dashboard data...</p>
        </div>

        <!-- User Stats Overview -->
        <div v-else class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Your Betting Stats
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Rank</div>
              <div class="text-2xl font-bold">#{{ userStats.rank || 'N/A' }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Points</div>
              <div class="text-2xl font-bold">{{ userStats.totalPoints || 0 }}</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Accuracy</div>
              <div class="text-2xl font-bold">{{ userStats.stats?.accuracy || 0 }}%</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Total Bets</div>
              <div class="text-2xl font-bold">{{ userStats.stats?.totalBets || 0 }}</div>
            </div>
          </div>
          
          <div class="mt-4 text-right">
            <Link :href="route('leaderboard.user-stats')" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
              View Detailed Stats
            </Link>
          </div>
        </div>

        <!-- Upcoming Matches -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Upcoming Matches
          </h2>
          
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
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Your Bet
                  </th>
                  <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="match in upcomingMatches" :key="match.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ match.home_team }} vs {{ match.away_team }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ new Date(match.match_time).toLocaleString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <template v-if="match.bets && match.bets.length > 0">
                      {{ match.bets[0].predicted_home_score }} - {{ match.bets[0].predicted_away_score }}
                    </template>
                    <span v-else class="text-gray-500 dark:text-gray-400">No bet placed</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <Link :href="route('matches.show', match.id)" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
                      View
                    </Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mt-4 text-right">
            <Link :href="route('matches.index')" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
              View All Matches
            </Link>
          </div>
        </div>

        <!-- Recent Results -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Recent Results
          </h2>
          
          <div v-if="recentMatches.length === 0" class="text-gray-500 dark:text-gray-400">
            No recent matches.
          </div>
          
          <div v-else class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Match
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Result
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Your Bet
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Points
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="match in recentMatches" :key="match.id">
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ match.home_team }} vs {{ match.away_team }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ match.home_score }} - {{ match.away_score }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <template v-if="match.bets && match.bets.length > 0">
                      {{ match.bets[0].predicted_home_score }} - {{ match.bets[0].predicted_away_score }}
                    </template>
                    <span v-else class="text-gray-500 dark:text-gray-400">No bet placed</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <template v-if="match.bets && match.bets.length > 0">
                      {{ match.bets[0].points_earned }}
                    </template>
                    <span v-else>0</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          
          <div class="mt-4 text-right">
            <Link :href="route('bets.index')" class="text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300">
              View Your Betting History
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
        </div>
    </AppLayout>
</template>
