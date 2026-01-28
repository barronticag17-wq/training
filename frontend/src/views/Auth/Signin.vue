<template>
  <div class="min-h-[80vh] flex items-center justify-center p-4 bg-gray-50">
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 w-full max-w-md animate-in fade-in duration-500">
      
      <div class="flex flex-col items-center mb-8">
        <div class="w-16 h-16 bg-green-600 rounded-xl flex items-center justify-center shadow-lg mb-4">
          <Sprout class="text-white w-10 h-10" />
        </div>
        <h1 class="text-2xl font-bold text-gray-900">
          {{ isRegister ? 'Create Account' : 'Portal Login' }}
        </h1>
        <p class="text-gray-500 text-center text-sm mt-2">
          Northern Philippines Root Crops <br/>Research & Development Center
        </p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        
        <div v-if="error" class="p-3 bg-red-50 border border-red-100 text-red-600 rounded-lg text-xs text-center font-bold">
          {{ error }}
        </div>

        <div v-if="successMsg" class="p-3 bg-green-50 border border-green-100 text-green-600 rounded-lg text-xs text-center font-bold">
          {{ successMsg }}
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1">Username</label>
          <input v-model="form.username" required type="text" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition-all font-medium" placeholder="jdelacruz" />
        </div>

        <div v-if="isRegister" class="space-y-4 animate-in slide-in-from-top-4 duration-300">
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Full Name</label>
            <input v-model="form.full_name" required type="text" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none" placeholder="Juan Dela Cruz" />
          </div>
          
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-1">Email Address</label>
            <input v-model="form.email" required type="email" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none" placeholder="juan@bsu.edu.ph" />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-1">Role</label>
              <select v-model="form.role" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none bg-white">
                <option value="User">Student/User</option>
                <option value="Researcher">Researcher</option>
                <option value="Admin">Admin</option>
              </select>
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700 mb-1">Department</label>
              <input v-model="form.department" required type="text" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none" placeholder="Dept." />
            </div>
          </div>
        </div>

        <div>
          <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
          <input v-model="form.password" required type="password" class="w-full p-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition-all font-medium" placeholder="••••••••" />
        </div>

        <button 
          type="submit"
          :disabled="isLoading"
          class="w-full bg-green-600 text-white py-4 rounded-xl font-black uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-100 transition-all transform active:scale-[0.98] mt-4 disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center gap-2"
        >
          <Loader2 v-if="isLoading" class="w-4 h-4 animate-spin" />
          {{ isRegister ? 'Create Account' : 'Sign In' }}
        </button>
      </form>

      <div class="mt-6 text-center">
        <button 
          @click="toggleMode" 
          class="text-sm text-gray-500 hover:text-green-600 font-bold transition-colors"
        >
          {{ isRegister ? 'Already have an account? Sign In' : 'New here? Create an Account' }}
        </button>
      </div>

      <p class="mt-8 text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">
        Internal Access Only • Benguet State University
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue';
import { useRouter } from 'vue-router';
import { Sprout, Loader2 } from 'lucide-vue-next';
import axios from 'axios';

const router = useRouter();
const isRegister = ref(false); // Controls form mode
const isLoading = ref(false);
const error = ref('');
const successMsg = ref('');

const form = reactive({
  username: '',
  password: '',
  email: '',
  full_name: '',
  role: 'User',
  department: ''
});

const toggleMode = () => {
  isRegister.value = !isRegister.value;
  error.value = '';
  successMsg.value = '';
  // Reset fields if switching to login
  if (!isRegister.value) {
    form.full_name = '';
    form.department = '';
  }
};

const handleSubmit = async () => {
  error.value = '';
  successMsg.value = '';
  isLoading.value = true;

  try {
    if (isRegister.value) {
      // ... (Registration logic stays the same) ...
      await axios.post('http://localhost:8080/api/auth/register', form);
      successMsg.value = 'Account created! Please sign in.';
      isRegister.value = false; 
    } else {
      // --- LOGIN LOGIC ---
      const response = await axios.post('http://localhost:8080/api/auth/login', {
        username: form.username,
        password: form.password
      });

      // 1. Save user to storage
      const userData = response.data.user;
      localStorage.setItem('user', JSON.stringify(userData));

      // 2. Redirect to Home (UPDATED THIS LINE)
      router.push('/'); 
    }
  } catch (err) {
    // ... (Error handling stays the same) ...
    console.error(err);
    if (err.response && err.response.data.error) {
       error.value = err.response.data.error; // Show server error message
    } else {
       error.value = 'Login failed. Please check credentials.';
    }
  } finally {
    isLoading.value = false;
  }
};
</script>