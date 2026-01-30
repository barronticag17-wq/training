
<template>
  <div class="min-h-screen bg-gray-50">
    <LandingNavbar :user="user" @logout="handleLogout" />

    <div class="space-y-6 p-6">
      
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Research Management</h1>
          <p class="text-gray-500">Collaborate, review, and discover root crop studies.</p>
        </div>
        <button 
          v-if="user && user.role !== 'User'"
          @click="isModalOpen = true" 
          class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 font-bold transition-all shadow-lg shadow-green-100"
        >
          <Plus class="w-5 h-5" />
          New Submission
        </button>
      </div>

      <div v-if="user" class="flex border-b border-gray-200 overflow-x-auto whitespace-nowrap scrollbar-hide">
        <button 
          v-for="tab in (user.role === 'User' ? ['All'] : ['All', 'My Submissions'])"
          :key="tab"
          @click="activeTab = tab"
          class="px-6 py-3 font-bold text-sm transition-all border-b-2"
          :class="activeTab === tab ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
        >
          {{ tab === 'All' ? 'Public Gallery' : tab }}
        </button>
        
        <template v-if="user.role === 'Admin'">
          <button 
            v-for="tab in ['Review Queue', 'Research Logs', 'Archived']" 
            :key="tab"
            @click="activeTab = tab"
            class="px-6 py-3 font-bold text-sm transition-all border-b-2"
            :class="activeTab === tab ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            {{ tab }}
          </button>
        </template>
      </div>

      <div 
        v-if="user && user.role === 'Admin' && deadlineAlerts.length > 0 && activeTab !== 'Research Logs'" 
        class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden"
      >
        <div class="px-6 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
          <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></div>
            <h3 class="text-xs font-black text-gray-500 uppercase tracking-widest">
              Pending Reviews ({{ deadlineAlerts.length }})
            </h3>
          </div>
        </div>

        <div class="max-h-60 overflow-y-auto p-2 space-y-2 custom-scrollbar">
          <div 
            v-for="alert in deadlineAlerts" 
            :key="alert.id" 
            class="flex items-center gap-3 p-3 rounded-xl transition-all group hover:shadow-md"
            :class="alert.isOverdue ? 'bg-red-50 border border-red-100' : 'bg-amber-50 border border-amber-100'"
          >
            <div 
              class="p-2 rounded-lg bg-white shadow-sm flex-shrink-0"
              :class="alert.isOverdue ? 'text-red-500' : 'text-amber-500'"
            >
              <AlertTriangle class="w-5 h-5" />
            </div>

            <div class="flex-grow min-w-0">
              <div class="flex items-center gap-2">
                <p class="text-sm font-bold text-gray-800 truncate">{{ alert.title }}</p>
                <span v-if="alert.isOverdue" class="px-2 py-0.5 rounded text-[10px] font-black bg-red-200 text-red-800 uppercase">Late</span>
              </div>
              <p class="text-xs font-medium opacity-80 mt-0.5 flex items-center gap-1">
                <span class="font-bold">Author:</span> {{ alert.author || 'Unknown' }} • 
                <span :class="alert.isOverdue ? 'text-red-700 font-bold' : 'text-amber-700'">
                  {{ alert.isOverdue ? `Overdue by ${Math.abs(alert.daysLeft)} days` : `Due in ${alert.daysLeft} days` }}
                </span>
              </p>
            </div>

            <button 
              @click="openPdfViewer(alert)" 
              class="opacity-0 group-hover:opacity-100 transition-opacity bg-white px-4 py-2 rounded-lg text-xs font-bold border border-gray-200 shadow-sm hover:text-green-600 hover:border-green-200 whitespace-nowrap"
            >
              Review Now
            </button>
          </div>
        </div>
      </div>

      <!-- <pre class="bg-slate-900 text-yellow-400 p-4 text-[10px] overflow-auto rounded-xl">
        API LOADING: {{ isLoading }}
        USER LOGGED IN: {{ !!user }}
        
        RAW RESEARCHES COUNT: {{ researches?.length || 0 }}
        FILTERED COUNT: {{ filteredResearches?.length || 0 }}

        RAW DATA PREVIEW:
        {{ researches && researches.length > 0 ? researches : 'No data in researches array' }}
      </pre> -->
      <ResearchFilterBar v-model="filters" />

      <!-- Card or List Toggle -->
      <div class="flex justify-end mb-4 gap-2">
        <button 
          @click="viewMode = 'card'"
          class="p-2 rounded-lg transition-colors"
          :class="viewMode === 'card' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-500'"
        >
          <LayoutGrid class="w-5 h-5" />
        </button>
        <button 
          @click="viewMode = 'list'"
          class="p-2 rounded-lg transition-colors"
          :class="viewMode === 'list' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-500'"
        >
          <List class="w-5 h-5" />
        </button>
      </div>

      <div v-if="isLoading" class="py-20 text-center">
        <Loader2 class="w-10 h-10 animate-spin text-green-600 mx-auto" />
      </div>

      <div v-else-if="filteredResearches.length === 0" class="py-20 text-center">
        <div class="inline-block p-4 bg-gray-100 rounded-full mb-4"><List class="w-8 h-8 text-gray-400" /></div>
        <p class="text-gray-500 font-bold">No researches found.</p>
      </div>

      <div v-else 
          :class="viewMode === 'card'?'grid grid-cols-1 md:grid-cols-2 gap-8' : 'flex flex-col gap-4'"
      >

        <ResearchItem 
          v-for="item in filteredResearches" 
          :key="item.id" 
          :item="item" 
          :user="user"
          :view-mode="viewMode"
          @viewPdf="openPdfViewer"
          @viewComments="openComments"
          @approve="approveResearch"
          @archive="archiveResearch"
        />
      </div>

      <div v-if="isModalOpen" class="fixed inset-0 bg-green-950/40 z-50 flex items-center justify-center p-4 backdrop-blur-md">
        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
          <div class="p-10 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">New Research</h2>
            <button @click="isModalOpen = false" class="bg-gray-100 p-2 rounded-full text-gray-400 hover:text-red-500 transition-colors"><X class="w-6 h-6" /></button>
          </div>
          <form @submit.prevent="handleSubmit" class="p-10 space-y-8">
            <div class="grid md:grid-cols-2 gap-8">
              <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Title</label>
                <input v-model="formData.title" required class="w-full p-4 bg-gray-50 border-b-2 border-gray-200 focus:border-green-600 outline-none font-bold text-lg" />
              </div>
              <div class="space-y-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Author</label>
                <input v-model="formData.author" required class="w-full p-4 bg-gray-50 border-b-2 border-gray-200 focus:border-green-600 outline-none font-bold" />
              </div>
              <div class="space-y-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Deadline</label>
                <input v-model="formData.deadlineDate" required type="date" class="w-full p-4 bg-gray-50 border-b-2 border-gray-200 focus:border-green-600 outline-none font-bold" />
              </div>
              <div class="space-y-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Document Type</label>
                <div class="relative">
                  <select 
                    v-model="formData.docType" 
                    required
                    class="w-full p-4 bg-gray-50 border-b-2 border-gray-200 focus:border-green-600 outline-none font-bold appearance-none cursor-pointer"
                  >
                    <option value="" disabled>Select Type</option>
                    <option value="Thesis">Thesis</option>
                    <option value="Dissertation">Dissertation</option>
                    <option value="Journal">Journal Article</option>
                    <option value="Report">Technical Report</option>
                  </select>
                  <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
                    <ChevronDown class="w-5 h-5" />
                  </div>
                </div>
              </div>
            </div>
            <div class="space-y-2">
              <div class="flex justify-between items-center px-1">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest">Abstract</label>
                <button type="button" @click="handleAIAnalyze" :disabled="isAnalyzing" class="text-xs flex items-center gap-2 text-green-600 font-black uppercase tracking-widest">
                  <Sparkles class="w-4 h-4" /> {{ isAnalyzing ? 'Analyzing...' : 'Smart Tags' }}
                </button>
              </div>
              <textarea v-model="formData.abstract" required rows="6" class="w-full p-6 bg-gray-50 rounded-3xl outline-none transition-all text-gray-700 font-medium"></textarea>
            </div>
            <div class="space-y-2">
              <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">PDF Document</label>
              <div @click="$refs.fileInputRef.click()" class="border-2 border-dashed rounded-3xl p-8 flex flex-col items-center justify-center cursor-pointer" :class="pdfFile ? 'bg-green-50 border-green-300' : 'bg-gray-50 border-gray-200'">
                <input type="file" ref="fileInputRef" @change="handleFileChange" accept="application/pdf" class="hidden" />
                <div v-if="pdfFile" class="text-green-800 font-bold">{{ pdfFile.name }}</div>
                <div v-else class="text-gray-400 font-bold">Click to Upload PDF</div>
              </div>
            </div>
            <div class="flex justify-end gap-4 pt-6">
              <button type="button" @click="isModalOpen = false" class="px-8 py-4 text-gray-400 font-bold">Discard</button>
              <button type="submit" class="px-10 py-4 bg-green-600 text-white font-black uppercase rounded-2xl hover:bg-green-700 shadow-xl">Submit</button>
            </div>
          </form>
        </div>
      </div>

      <div v-if="isPdfViewerOpen && selectedResearch" class="fixed inset-0 bg-white z-[60] flex flex-col overflow-hidden">
        <div class="p-4 border-b flex justify-between items-center bg-gray-50">
          <div class="flex items-center gap-4">
            <button @click="isPdfViewerOpen = false" class="hover:bg-gray-200 p-2 rounded-full transition-colors">
              <X class="w-6 h-6 text-gray-600" />
            </button>
            <h3 class="font-black text-gray-900 truncate max-w-md md:max-w-2xl">
              {{ selectedResearch.title }}
            </h3>
          </div>
          
          <a :href="selectedResearch.pdfUrl" target="_blank" class="text-xs font-bold text-green-600 bg-green-50 px-3 py-2 rounded-lg">
            Open in New Tab
          </a>
        </div>

        <div class="flex-grow w-full h-full bg-gray-100">
          <iframe 
            :src="selectedResearch.pdfUrl" 
            class="w-full h-full border-none" 
            frameborder="0"
          ></iframe>
        </div>
      </div>

      <div v-if="isCommentModalOpen && selectedResearch" class="fixed inset-0 bg-green-950/40 z-50 flex items-center justify-center p-4 backdrop-blur-md">
        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-xl flex flex-col h-[70vh]">
          <div class="p-8 border-b flex justify-between items-center">
            <h3 class="font-black">Feedback Loop</h3>
            <button @click="isCommentModalOpen = false"><X class="w-5 h-5 text-gray-400" /></button>
          </div>
          <div class="flex-grow overflow-y-auto p-8 space-y-6">
            <div v-if="selectedResearch.comments.length === 0" class="text-center text-gray-400 font-bold">No feedback yet.</div>
            <div v-for="c in selectedResearch.comments" :key="c.id" class="flex flex-col" :class="c.senderId === user?.id ? 'items-end' : 'items-start'">
              <div class="max-w-[85%] p-4 rounded-3xl text-sm font-medium" :class="c.senderId === user?.id ? 'bg-green-600 text-white' : 'bg-gray-100'">{{ c.text }}</div>
              <span class="text-[10px] font-black text-gray-300 mt-1 uppercase">{{ c.senderName }}</span>
            </div>
          </div>
          <div class="p-8 border-t bg-gray-50/50 rounded-b-[40px]">
            <div class="flex gap-3 bg-white p-2 rounded-2xl border shadow-sm">
              <input v-model="commentText" @keydown.enter="handlePostComment" type="text" class="flex-grow px-4 outline-none font-medium text-sm" placeholder="Type a message..." />
              <button @click="handlePostComment" class="bg-green-600 text-white p-2.5 rounded-xl"><Send class="w-4 h-4" /></button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import LandingNavbar from '../components/LandingNavbar.vue';
import ResearchFilterBar from '../components/ResearchFilterBar.vue';
import ResearchItem from '../components/ResearchItem.vue';

// Import Logic
import { useResearches } from '../composables/useResearches';
import { ref, 
        computed, 
        onMounted 
} from 'vue';

import { useRouter } from 'vue-router';
import axios from 'axios';
import { Plus, 
        AlertTriangle, 
        Loader2, 
        List, 
        X, 
        Sparkles, 
        Send, 
        LayoutGrid
} from 'lucide-vue-next';

// --- 1. SETUP ---
const router = useRouter();
const user = ref(null);

// Initialize Composable
const { 
  researches, 
  filteredResearches, 
  filters, 
  activeTab, 
  isLoading, 
  fetchResearches, 
  approveResearch 
} = useResearches(user);

// --- 2. LOCAL UI STATE (Modals/Forms) ---
const isModalOpen = ref(false);
const isPdfViewerOpen = ref(false);
const isCommentModalOpen = ref(false);
const selectedResearch = ref(null);
const commentText = ref('');
const isAnalyzing = ref(false);
const fileInputRef = ref(null);
const pdfFile = ref(null);

const formData = ref({
  title: '',
  author: '',
  abstract: '',
  docType: 'Thesis',
  status: 'Under Review',
  deadlineDate: '',
  tags: []
});

const viewMode = ref('card') // 'card' or 'list'


// --- 3. DEADLINE ALERTS (✅ UPDATED: Admin Review Queue) ---
const deadlineAlerts = computed(() => {
  // Only Admins need to see the global "Review Queue" alerts
  if (!user.value || user.value.role !== 'Admin') return [];
  
  const now = new Date();
  
  return researches.value
    // Filter: All items that are 'Under Review', have a deadline, and are NOT archived
    .filter(r => r.status === 'Under Review' && r.deadlineDate && !r.isArchived)
    .map(r => {
      const deadline = new Date(r.deadlineDate);
      const diffDays = Math.ceil((deadline.getTime() - now.getTime()) / (1000 * 60 * 60 * 24));
      
      return { 
        id: r.id, 
        title: r.title, 
        daysLeft: diffDays, 
        isOverdue: diffDays < 0 // Helper for red styling
      };
    })
    // Sort: Overdue items (negative numbers) first, then nearest deadlines
    .sort((a, b) => a.daysLeft - b.daysLeft);
});

// --- 4. FORM ACTIONS (Kept local for now) ---
const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) pdfFile.value = file;
};

const handleAIAnalyze = () => {
  isAnalyzing.value = true;
  setTimeout(() => { 
    isAnalyzing.value = false; 
    alert("AI Analysis Complete (Mock)"); 
  }, 1500);
};

const handleSubmit = async () => {
  try {
    const submissionData = new FormData();
    submissionData.append('title', formData.value.title);
    submissionData.append('author', formData.value.author);
    submissionData.append('abstract', formData.value.abstract);
    submissionData.append('deadline_date', formData.value.deadlineDate);
    submissionData.append('doc_type', formData.value.docType); 
    submissionData.append('status', user.value?.role === 'Admin' ? 'Published' : 'Under Review');
    submissionData.append('submitter_id', user.value?.id);
    if (pdfFile.value) submissionData.append('pdf_file', pdfFile.value);

    await axios.post('http://localhost:8080/api/researches', submissionData, {
      headers: { 'Content-Type': 'multipart/form-data' }
    });

    alert('Success!');
    isModalOpen.value = false;
    fetchResearches(); // Refresh data via composable
  } catch (error) {
    console.error(error);
    alert('Submission failed.');
  }
};

const handlePostComment = () => {
  if (selectedResearch.value && commentText.value.trim()) {
    selectedResearch.value.comments.push({
        id: Date.now(),
        senderId: user.value.id,
        senderName: user.value.name,
        text: commentText.value,
        date: new Date().toISOString()
    });
    commentText.value = '';
  }
};

const openPdfViewer = (item) => {
  selectedResearch.value = item;
  isPdfViewerOpen.value = true;
};

const openComments = (item) => {
  selectedResearch.value = item;
  isCommentModalOpen.value = true;
};

const archiveResearch = async (id) => {
  if (confirm('Archive this item?')) {
    await axios.put(`http://localhost:8080/api/researches/${id}/archive`);
    fetchResearches();
  }
};

const handleLogout = () => {
  localStorage.removeItem('user');
  router.push('/signin');
};

// --- 5. INITIALIZE ---
onMounted(() => {
  const storedUser = localStorage.getItem('user');
  if (storedUser) {
    user.value = JSON.parse(storedUser);
    
    // Set default author name
    const realName = user.value.full_name || user.value.name;
    formData.value.author = realName;
    user.value.name = realName;

    // Trigger data fetch (Composable handles the actual API call)
    fetchResearches();
  } else {
    router.push('/signin');
  }
});
</script>