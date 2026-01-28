<template>
  <div class="min-h-screen bg-gray-50 font-sans">
    
    <LandingNavbar />
    <div class="p-10">
      <h1 class="text-3xl font-bold text-green-700">Welcome Home!</h1>
      
      <p v-if="user" class="text-gray-600 mt-2">
        Hello, <strong>{{ user.full_name }}</strong> ({{ user.role }})
      </p>

      <button @click="logout" class="mt-4 bg-red-500 text-white px-4 py-2 rounded">
        Logout
      </button>
    </div>
    <div class="space-y-16 pb-12 p-4 md:p-8 max-w-7xl mx-auto">
      
      <section class="relative bg-green-900 text-white rounded-3xl overflow-hidden shadow-xl min-h-[500px] flex items-center">
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1615485925763-867862f80eb3?q=80&w=2070&auto=format&fit=crop')] bg-cover bg-center opacity-30 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-green-950 via-green-900/90 to-green-800/50"></div>
        
        <div class="relative z-10 container mx-auto px-8 max-w-5xl py-20">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-800/50 border border-green-700 text-green-100 text-sm font-medium mb-6 backdrop-blur-sm">
            <Sprout class="w-4 h-4" />
            <span>Excellence in Highland Agriculture</span>
          </div>
          <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
            Northern Philippines <br />
            <span class="text-green-400">RootCrops</span> Research & Training  Center
          </h1>
          <p class="text-xl text-gray-200 mb-8 leading-relaxed max-w-2xl">
            Based at Benguet State University, we are dedicated to improving the production, utilization, and commercialization of root crops to support food security and sustainable livelihoods in the highlands.
          </p>
          <div class="flex flex-wrap gap-4">
            <router-link 
              to="/researches" 
              class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-bold transition-all flex items-center gap-2 shadow-lg shadow-green-900/50 transform hover:-translate-y-1"
            >
              Browse Researches
              <ArrowRight class="w-5 h-5" />
            </router-link>
            <button class="bg-white/10 hover:bg-white/20 text-white px-8 py-4 rounded-xl font-bold transition-all backdrop-blur-sm border border-white/10">
              Learn More
            </button>
          </div>
        </div>
      </section>

      <section class="container mx-auto">
        <div class="grid md:grid-cols-2 gap-8">
          <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
              <Target class="w-8 h-8" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
              To generate, verify, and disseminate technologies for the sustained growth of the root crop industry in the Northern Philippines, ensuring food security and economic stability for local farmers.
            </p>
          </div>

          <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
              <Eye class="w-8 h-8" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
              A premier center of excellence in root crops research, training, and extension services, recognized globally for its contribution to sustainable highland agriculture and development.
            </p>
          </div>
        </div>
      </section>

      <!-- <section class="bg-white rounded-3xl p-12 shadow-sm border border-gray-100 text-center">
        <div class="max-w-3xl mx-auto mb-16">
          <span class="text-green-600 font-semibold tracking-wider text-sm uppercase mb-2 block">Our Research Focus</span>
          <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Priority Root Crops</h2>
          <p class="text-gray-600 text-lg">
            Our research and development efforts focus on the genetic conservation, quality improvement, and value addition of these key commodities.
          </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-12">
          <div v-for="crop in crops" :key="crop.name" class="flex flex-col items-center group">
            <div 
              class="w-28 h-28 rounded-full flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110 shadow-sm ring-8 ring-opacity-50"
              :class="`${crop.color} ${crop.ring}`"
            >
              <Leaf class="w-12 h-12" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ crop.name }}</h3>
            <p class="text-gray-500 font-medium">{{ crop.desc }}</p>
          </div>
        </div>
      </section> -->
      
      <section class="grid md:grid-cols-3 gap-6">
        <div class="bg-green-900 text-white p-8 rounded-2xl flex items-center gap-4">
            <div class="bg-green-800 p-3 rounded-lg">
                <MapPin class="w-6 h-6" />
            </div>
            <div>
                <h3 class="font-bold">Visit Us</h3>
                <p class="text-green-100 text-sm">km6, La Trinidad, Benguet</p>
            </div>
        </div>
        <div class="bg-green-800 text-white p-8 rounded-2xl flex items-center gap-4">
            <div class="bg-green-700 p-3 rounded-lg">
                <Phone class="w-6 h-6" />
            </div>
            <div>
                <h3 class="font-bold">Call Us</h3>
                <p class="text-green-100 text-sm">+63 74 422 2402</p>
            </div>
        </div>
        <div class="bg-green-700 text-white p-8 rounded-2xl flex items-center gap-4">
            <div class="bg-green-600 p-3 rounded-lg">
                <Mail class="w-6 h-6" />
            </div>
            <div>
                <h3 class="font-bold">Email Us</h3>
                <p class="text-green-100 text-sm">rootCropsResearch@bsu.edu.ph</p>
            </div>
        </div>
      </section>

    </div>
  </div>
</template>

<script setup>
import { Sprout, Target, Eye, ArrowRight, Leaf, MapPin, Phone, Mail } from 'lucide-vue-next';
import LandingNavbar from '../components/LandingNavbar.vue';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue'

const crops = [
  { name: 'Sweet Potato', desc: 'Camote', color: 'bg-orange-50 text-orange-600', ring: 'ring-orange-100' },
  { name: 'White Potato', desc: 'Patatas', color: 'bg-yellow-50 text-yellow-600', ring: 'ring-yellow-100' },
  { name: 'Taro', desc: 'Gabi', color: 'bg-emerald-50 text-emerald-600', ring: 'ring-emerald-100' },
  { name: 'Purple Yam', desc: 'Ube', color: 'bg-purple-50 text-purple-600', ring: 'ring-purple-100' }
];

// --- 1. STATE MANAGEMENT ---

const router = useRouter()
const user = ref(null)

// --- 2. ACTIONS ---

// 👇 THIS IS THE MISSING LOGOUT FUNCTION
const logout = () => {
  // 1. Remove the user data from browser storage
  localStorage.removeItem('user');
  
  // 2. Reset the local state (optional, but good practice)
  user.value = null;

  // 3. Redirect back to the Login Page
  router.push('/');
}

// --- 3. LIFECYCLE HOOKS ---
onMounted(() => {
  // 1. Check for logged-in user
  const storedUser = localStorage.getItem('user')
  
  if (storedUser) {
    // If found, load their data
    user.value = JSON.parse(storedUser)
  } else {
    // If NOT found, kick them back to the Login page
    router.push('/signin')
  }
  
  // Note: I removed fetchResearches() because it is not defined in this file.
  // This page is just the dashboard/welcome screen.
})
</script>