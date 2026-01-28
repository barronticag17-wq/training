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
          <router-link 
            to="/" 
            :class="isActive('/')"
            class="px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2"
          >
            <HomeIcon class="w-4 h-4" />
            Home
          </router-link>
          <router-link 
            to="/researches" 
            :class="isActive('/researches')"
            class="px-4 py-2 rounded-md text-sm font-medium transition-colors flex items-center gap-2"
          >
            <FileText class="w-4 h-4" />
            Researches
          </router-link>
        </div>

        <div class="flex items-center gap-4">
          <template v-if="user">
            <div class="flex items-center gap-4">
              <div class="hidden md:flex flex-col items-end">
                <span class="text-sm font-semibold text-gray-800">{{ user.name }}</span>
                <span class="text-xs text-gray-500">{{ user.role }}</span>
              </div>
              <button 
                @click="logout"
                class="p-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors"
                title="Logout"
              >
                <LogOut class="w-5 h-5" />
              </button>
            </div>
            <router-link 
              to="/admin"
              class="hidden md:flex bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Dashboard
            </router-link>
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
    
    <div class="md:hidden flex border-t border-gray-100">
      <router-link 
        to="/" 
        class="flex-1 py-3 text-center text-sm font-medium"
        :class="route.path === '/' ? 'text-green-600' : 'text-gray-500'"
      >
        Home
      </router-link>
      <router-link 
        to="/researches" 
        class="flex-1 py-3 text-center text-sm font-medium"
        :class="route.path === '/researches' ? 'text-green-600' : 'text-gray-500'"
      >
        Researches
      </router-link>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { Sprout, Home as HomeIcon, FileText, LogIn, LogOut } from 'lucide-vue-next';


// 1. Hook into the Router
const route = useRoute();
const router = useRouter();

// 2. Mock User State (Replace this with your real Pinia/Auth store later)
// const user = ref({ name: 'Dr. Santos', role: 'Researcher' }); 
const user = null; // Set to null to see the Login button

// 3. Helper to style active links
const isActive = (path) => {
  return route.path === path 
    ? 'text-green-700 bg-green-50' 
    : 'text-gray-600 hover:text-green-600 hover:bg-gray-50';
};

// 4. Logout Action
const logout = () => {
  // Add your logout logic here (e.g., clearing tokens)
  console.log('Logging out...');
  router.push('/signin');
};
</script>