<template>
  <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-4">
      <div class="flex justify-between items-center h-16">
        
        <router-link to="/" class="flex items-center gap-2 group">
          <img 
            src="@/assets/NorthernPhilippinesRootcrops.svg" 
            alt="NPRCRTC Logo" 
            class="h-12 w-auto hover:opacity-90 transition-opacity"
          />
          <div class="flex flex-col">
            <span class="font-bold text-lg text-gray-900 leading-tight">BSU Rootcrops</span>
            <span class="text-xs text-green-600 font-medium tracking-wide">RESEARCH & TRAINING</span>
          </div>
        </router-link>

        <div class="hidden md:flex items-center space-x-2">
          <router-link to="/" :class="isActive('/')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
            <HomeIcon class="w-4 h-4" /> Home
          </router-link>
          <router-link to="/researches" :class="isActive('/researches')" class="px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2">
            <FileText class="w-4 h-4" /> Researches
          </router-link>
        </div>

        <div class="flex items-center gap-4">
          <template v-if="user">
            <div class="flex items-center gap-4">
              <div class="hidden md:flex flex-col items-end border-r pr-4 border-gray-100">
                <span class="text-sm font-semibold text-gray-800">{{ user.full_name }}</span>
                <span class="text-[10px] uppercase font-bold text-green-600">{{ user.role }}</span>
              </div>
              
              <button 
                @click="$emit('logout')"
                class="p-2 rounded-full hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors"
                title="Logout"
              >
                <LogOut class="w-5 h-5" />
              </button>
            </div>
          </template>

          <template v-else>
            <router-link 
              to="/signin"
              :class="isActive('/signin')"
              class="px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2"
            >
              <LogIn class="w-4 h-4" />
              Login
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { Home as HomeIcon, FileText, LogIn, LogOut } from 'lucide-vue-next';

// 1. Receive the user object from HomeView
defineProps({
  user: {
    type: Object,
    default: null
  }
});

// 2. Define the logout event to send back to HomeView
defineEmits(['logout']);

const route = useRoute();

const isActive = (path) => {
  return route.path === path 
    ? 'text-green-700 bg-green-50' 
    : 'text-gray-600 hover:text-green-600 hover:bg-gray-50';
};
</script>

