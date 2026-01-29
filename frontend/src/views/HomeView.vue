<template>
  <div class="min-h-screen bg-gray-50 font-sans">
    
    <LandingNavbar :user="user" @logout="logout" />
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
            PD No. 1107 mandates the NPRCRTC, together with the Philippine Root Crops Research and Training Center at Visayas State University to spearhead, coordinate, and implement the national root crops research, development, and extension programs.
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
              To develop efficient root crops production and utilization systems.
            </p>
          </div>

          <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
              <Eye class="w-8 h-8" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Vision</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
              A prime mover of sustainable rootcrops industry.
            </p>
          </div>
        </div>
      </section>

      <section class="container mx-auto">
        <div class="grid md:grid-cols-2 gap-8">
          <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
              <Flag class="w-8 h-8" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our GOAL</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
              To increase productivity, intensity pro-active extension, develop diversified utilization of rootcrops, strengthen linkages and improve organizational capacity
            </p>
          </div>

          <div class="bg-white p-10 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300 group">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:scale-110 transition-transform">
              <ListChecks class="w-8 h-8" />
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Our OBJECTIVE</h2>
            <p class="text-gray-600 leading-relaxed text-lg">
              Develop profitable a sustainable root crop production and industry thru the generation of applicable technologies and useful information from its research, training, extension and production activities
            </p>
          </div>
        </div>
      </section>

      <section class="container mx-auto">
        <div class="text-center max-w-3xl mx-auto mb-12">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">Our Divisions</h2>
          <p class="text-gray-600">The NPRCRTC is organized into specialized divisions, each dedicated to a specific pillar of the root crop industry.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-green-600 mb-6 group-hover:bg-green-600 group-hover:text-white transition-colors">
              <Sprout class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Crop Improvement</h3>
            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
              <li>Evaluate, improve and develop varieties</li>
              <li>Maintain promising germplasm</li>
              <li>Clean up services of parent materials</li>
              <li>Expert services on crop improvement</li>
            </ul>
          </div>

          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors">
              <ListChecks class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Management & Seed Production</h3>
            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
              <li>Develop production techniques & cropping systems</li>
              <li>Integrated pest management strategies</li>
              <li>Distribute seed board approved varieties</li>
              <li>Soil analysis and pest diagnosis</li>
            </ul>
          </div>

          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-amber-600 group-hover:text-white transition-colors">
              <Leaf class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Processing & Utilization</h3>
            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
              <li>Harvest/postharvest handling technologies</li>
              <li>Expand processing and value-addition</li>
              <li>Waste management systems</li>
              <li>Utilizing agricultural wastes</li>
            </ul>
          </div>

          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-6 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
              <Target class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Engineering</h3>
            <p class="text-sm text-gray-600 leading-relaxed">Design and develop low-cost efficient machineries, tools and facilities in root crop production, postharvest and processing.</p>
          </div>

          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 mb-6 group-hover:bg-purple-600 group-hover:text-white transition-colors">
              <Flag class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Socio-Economics & Policy</h3>
            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
              <li>Market research and social marketing</li>
              <li>Indigenous knowledge and gender studies</li>
              <li>Draft policies and industry guidelines</li>
              <li>Enterprise development documentation</li>
            </ul>
          </div>

          <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-green-200 transition-all group">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
              <Mail class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Training & Extension</h3>
            <ul class="text-sm text-gray-600 space-y-2 list-disc pl-4">
              <li>Coordinate training programs</li>
              <li>Publish IEC materials on root crops</li>
              <li>Host conferences, symposia and seminars</li>
              <li>Support library and technical services</li>
            </ul>
          </div>
        </div>
      </section>

      <section class="container mx-auto">
        <div class="bg-green-50 rounded-[40px] p-8 md:p-12 border border-green-100">
          <div class="flex flex-col md:flex-row gap-8 items-center">
            <div class="md:w-1/3">
              <h2 class="text-3xl font-black text-green-900 mb-4">Technical Services</h2>
              <p class="text-green-800/80 mb-6">We provide specialized laboratory and material services to farmers and stakeholders.</p>
              <div class="inline-block bg-green-600 text-white px-6 py-2 rounded-full text-sm font-bold">Available Now</div>
            </div>
            <div class="md:w-2/3 grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h4 class="font-bold text-gray-900 mb-2">Planting Materials</h4>
                <p class="text-xs text-gray-500">Tissue-cultured plants, basic seeds, and dispersal materials for Potato, Sweet Potato, and Yam.</p>
              </div>
              <div class="bg-white p-6 rounded-2xl shadow-sm">
                <h4 class="font-bold text-gray-900 mb-2">Plant Disease Clinic</h4>
                <p class="text-xs text-gray-500">Bacterial wilt diagnosis, Soil analysis, and Virus testing (ELISA) for root crops.</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section class="grid md:grid-cols-3 gap-6">
        <div class="bg-green-900 text-white p-8 rounded-2xl flex items-center gap-4">
            <div class="bg-green-800 p-3 rounded-lg">
                <MapPin class="w-6 h-6" />
            </div>
            <div>
                <h3 class="font-bold">Visit Us</h3>
                <p class="text-green-100 text-sm">Km. 6, La Trinidad, Benguet 2601 Philippines</p>
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
import { Sprout, Target, Eye, ArrowRight, Leaf, MapPin, Phone, Mail, Flag, ListChecks } from 'lucide-vue-next';
import LandingNavbar from '../components/LandingNavbar.vue';
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue'

// --- 1. STATE MANAGEMENT ---

const router = useRouter()
const user = ref(null)

// --- 2. ACTIONS ---

const logout = () => {
  localStorage.removeItem('user');
  user.value = null;
  router.push('/');
}

// --- 3. LIFECYCLE HOOKS ---
onMounted(() => {
  // 1. Check for logged-in user
  const storedUser = localStorage.getItem('user')
  
  if (storedUser) {
    // If found, load their data
    user.value = JSON.parse(storedUser)
  }

})
</script>