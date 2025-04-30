<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  match: Object,
  userBet: Object,
  stats: Object,
  canBet: Boolean,
  isAdmin: Boolean
});

// Form for placing bets
const betForm = useForm({
  match_id: props.match.id,
  predicted_home_score: props.userBet ? props.userBet.predicted_home_score : 0,
  predicted_away_score: props.userBet ? props.userBet.predicted_away_score : 0,
});

// Form for updating match status and scores (admin only)
const matchForm = useForm({
  home_score: props.match.home_score || 0,
  away_score: props.match.away_score || 0,
  status: props.match.status,
});

const showBetModal = ref(false);
const showAdminModal = ref(false);

const formatDateTime = (dateString) => {
  return new Date(dateString).toLocaleString();
};

const placeBet = () => {
  betForm.post(route('bets.store'), {
    preserveScroll: true,
    onSuccess: () => {
      showBetModal.value = false;
    },
  });
};

const updateMatch = () => {
  matchForm.put(route('matches.update', props.match.id), {
    preserveScroll: true,
    onSuccess: () => {
      showAdminModal.value = false;
    },
  });
};
</script>

<template>
  <Head :title="`${match.home_team} vs ${match.away_team}`" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ match.home_team }} vs {{ match.away_team }}
        </h2>
        <div class="flex space-x-2">
          <button v-if="canBet" @click="showBetModal = true" 
                  class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500">
            {{ userBet ? 'Update Bet' : 'Place Bet' }}
          </button>
          <button v-if="isAdmin" @click="showAdminModal = true" 
                  class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Update Match
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Match Details -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Match Details
              </h3>
              <span class="px-3 py-1 text-xs rounded-full" 
                    :class="{
                      'bg-yellow-100 text-yellow-800': match.status === 'scheduled',
                      'bg-blue-100 text-blue-800': match.status === 'in_progress',
                      'bg-green-100 text-green-800': match.status === 'finished'
                    }">
                {{ match.status.replace('_', ' ').toUpperCase() }}
              </span>
            </div>
            
            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
              Scheduled for {{ formatDateTime(match.match_time) }}
            </div>
            
            <div v-if="match.status === 'finished'" class="flex justify-center items-center py-4">
              <div class="text-center px-6">
                <div class="text-lg font-semibold">{{ match.home_team }}</div>
                <div class="text-3xl font-bold mt-2">{{ match.home_score }}</div>
              </div>
              <div class="text-xl font-bold px-4">vs</div>
              <div class="text-center px-6">
                <div class="text-lg font-semibold">{{ match.away_team }}</div>
                <div class="text-3xl font-bold mt-2">{{ match.away_score }}</div>
              </div>
            </div>
            <div v-else class="flex justify-center items-center py-4">
              <div class="text-center px-6">
                <div class="text-lg font-semibold">{{ match.home_team }}</div>
              </div>
              <div class="text-xl font-bold px-4">vs</div>
              <div class="text-center px-6">
                <div class="text-lg font-semibold">{{ match.away_team }}</div>
              </div>
            </div>
          </div>
          
          <!-- Your Bet -->
          <div v-if="userBet" class="mt-8 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-2">
              Your Prediction
            </h4>
            
            <div class="flex justify-center items-center py-2">
              <div class="text-center px-6">
                <div class="text-sm font-semibold">{{ match.home_team }}</div>
                <div class="text-2xl font-bold mt-1">{{ userBet.predicted_home_score }}</div>
              </div>
              <div class="text-md font-bold px-4">-</div>
              <div class="text-center px-6">
                <div class="text-sm font-semibold">{{ match.away_team }}</div>
                <div class="text-2xl font-bold mt-1">{{ userBet.predicted_away_score }}</div>
              </div>
            </div>
            
            <div v-if="match.status === 'finished'" class="mt-2 text-center">
              <span class="font-semibold">Points earned: </span>
              <span class="text-lg font-bold">{{ userBet.points_earned }}</span>
            </div>
          </div>
        </div>
        
        <!-- Betting Statistics -->
        <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
          <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            Betting Statistics
          </h3>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Home Win Predictions</div>
              <div class="text-2xl font-bold">{{ stats.homeWinPercentage }}%</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Draw Predictions</div>
              <div class="text-2xl font-bold">{{ stats.drawPercentage }}%</div>
            </div>
            
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="text-sm text-gray-500 dark:text-gray-400">Away Win Predictions</div>
              <div class="text-2xl font-bold">{{ stats.awayWinPercentage }}%</div>
            </div>
          </div>
          
          <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
            Total bets placed: {{ stats.totalBets }}
          </div>
        </div>
      </div>
    </div>

    <!-- Bet Modal -->
    <div v-if="showBetModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
          {{ userBet ? 'Update Your Bet' : 'Place Your Bet' }}
        </h3>
        
        <form @submit.prevent="placeBet">
          <div class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ match.home_team }} Score
              </label>
              <input 
                type="number" 
                min="0"
                v-model="betForm.predicted_home_score"
                class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
              >
            </div>
            <div v-if="betForm.errors.predicted_home_score" class="text-red-500 text-xs mt-1">
              {{ betForm.errors.predicted_home_score }}
            </div>
          </div>
          
          <div class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ match.away_team }} Score
              </label>
              <input 
                type="number" 
                min="0"
                v-model="betForm.predicted_away_score"
                class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
              >
            </div>
            <div v-if="betForm.errors.predicted_away_score" class="text-red-500 text-xs mt-1">
              {{ betForm.errors.predicted_away_score }}
            </div>
          </div>
          
          <div class="flex justify-end space-x-2">
            <button 
              type="button"
              @click="showBetModal = false"
              class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
            >
              Cancel
            </button>
            <button 
              type="submit"
              :disabled="betForm.processing"
              class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50"
            >
              {{ userBet ? 'Update Bet' : 'Place Bet' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Admin Modal -->
    <div v-if="showAdminModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
      <div class="bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
          Update Match
        </h3>
        
        <form @submit.prevent="updateMatch">
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Status
            </label>
            <select 
              v-model="matchForm.status"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
            >
              <option value="scheduled">Scheduled</option>
              <option value="in_progress">In Progress</option>
              <option value="finished">Finished</option>
            </select>
            <div v-if="matchForm.errors.status" class="text-red-500 text-xs mt-1">
              {{ matchForm.errors.status }}
            </div>
          </div>
          
          <div v-if="matchForm.status !== 'scheduled'" class="mb-4">
            <div class="flex justify-between items-center mb-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ match.home_team }} Score
              </label>
              <input 
                type="number" 
                min="0"
                v-model="matchForm.home_score"
                class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600"
              >
            </div>
            <div v-if="matchForm.errors.home_score" class="text-red-500 text-xs mt-1">
              {{ matchForm.errors.home_score }}
            </div>
          </div>
          
          <div v-if="matchForm.status !== 'scheduled'" class="mb-6">
            <div class="flex justify-between items-center mb-2">
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ match.away

