<template>
  <div class="min-h-screen bg-gray-50">
    <LandingNavbar />
    <div class="space-y-6 p-6">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Research Management</h1>
          <p class="text-gray-500">Collaborate, review, and discover root crop studies.</p>
        </div>
        <button 
          v-if="user" 
          @click="isModalOpen = true" 
          class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl flex items-center gap-2 font-bold transition-all shadow-lg shadow-green-100"
        >
          <Plus class="w-5 h-5" />
          New Submission
        </button>
      </div>

      <div v-if="user" class="flex border-b border-gray-200 overflow-x-auto whitespace-nowrap scrollbar-hide">
        <button 
          @click="activeTab = 'All'"
          class="px-6 py-3 font-bold text-sm transition-all border-b-2"
          :class="activeTab === 'All' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
        >
          Public Gallery
        </button>
        <button 
          @click="activeTab = 'My Submissions'"
          class="px-6 py-3 font-bold text-sm transition-all border-b-2"
          :class="activeTab === 'My Submissions' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
        >
          My Workspace
        </button>
        <template v-if="user.role === 'Admin'">
          <button 
            @click="activeTab = 'Review Queue'"
            class="px-6 py-3 font-bold text-sm transition-all border-b-2"
            :class="activeTab === 'Review Queue' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            Review Queue
          </button>
          <button 
            @click="activeTab = 'Research Logs'"
            class="px-6 py-3 font-bold text-sm transition-all border-b-2"
            :class="activeTab === 'Research Logs' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            Research Logs
          </button>
          <button 
            @click="activeTab = 'Archived'"
            class="px-6 py-3 font-bold text-sm transition-all border-b-2"
            :class="activeTab === 'Archived' ? 'border-green-600 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
          >
            Archived
          </button>
        </template>
      </div>

      <div v-if="user && deadlineAlerts.length > 0 && activeTab !== 'Research Logs'" class="space-y-2">
        <div 
          v-for="alert in deadlineAlerts" 
          :key="alert.id" 
          class="flex items-center gap-3 p-4 rounded-xl border-2"
          :class="alert.isOverdue ? 'bg-red-50 border-red-200 text-red-800' : 'bg-amber-50 border-amber-200 text-amber-800'"
        >
          <AlertTriangle class="w-6 h-6 flex-shrink-0" />
          <div class="flex-grow">
            <p class="text-sm font-bold">Action Required: {{ alert.title }}</p>
            <p class="text-xs opacity-80">
              {{ alert.isOverdue ? 'Deadline has passed. Please contact Admin immediately.' : `Your target completion is in ${alert.daysLeft} days.` }}
            </p>
          </div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4">
        <div class="relative flex-grow">
          <Search class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
          <input 
            v-model="searchTerm"
            type="text" 
            placeholder="Search by title, author, or keywords..." 
            class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition-all" 
          />
        </div>
        <div class="relative min-w-[220px]">
          <select 
            v-model="filterType"
            class="w-full pl-4 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white font-medium" 
          >
            <option value="All">All Commodities</option>
            <option v-for="type in ['Sweet Potato', 'White Potato', 'Cassava', 'Taro', 'Ube', 'Other']" :key="type" :value="type">
              {{ type }}
            </option>
          </select>
          <Filter class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4 pointer-events-none" />
        </div>
      </div>

      <div v-if="activeTab === 'Research Logs' || activeTab === 'Archived'" class="bg-white border border-gray-100 rounded-3xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left border-collapse">
            <thead>
              <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Research Title</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Commodity</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Submitted</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Activity</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-center">Status</th>
                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
              <tr v-for="item in filteredResearches" :key="item.id" class="hover:bg-gray-50/80 transition-colors">
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="font-bold text-gray-900 text-sm line-clamp-1">{{ item.title }}</span>
                    <span class="text-xs text-gray-500">{{ item.author }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span class="text-[10px] font-black text-green-600 bg-green-50 px-2 py-1 rounded-md uppercase">
                    {{ item.cropType }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1.5 text-xs text-gray-500">
                    <Calendar class="w-3 h-3" />
                    {{ item.date }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-1.5 text-xs font-semibold">
                    <div v-if="item.status === 'Published'" class="text-green-600 flex items-center gap-1">
                      <CheckCircle class="w-3 h-3" />
                      {{ item.approvedDate || 'N/A' }}
                    </div>
                    <div v-else-if="item.comments.length > 0" class="text-amber-600 flex items-center gap-1">
                      <MessageSquare class="w-3 h-3" />
                      {{ item.comments[item.comments.length - 1].date }}
                    </div>
                    <span v-else class="text-gray-300 font-normal">Pending Activity</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex justify-center">
                    <span 
                      class="text-[10px] font-black px-2 py-1 rounded-full border"
                      :class="getStatusColor(item.status)"
                    >
                      {{ item.status.toUpperCase() }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button 
                      v-if="item.pdfUrl" 
                      @click="openPdfViewer(item)" 
                      class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all" 
                      title="Read PDF"
                    >
                      <FileText class="w-4 h-4" />
                    </button>
                    <button @click="openComments(item)" class="p-2 text-gray-400 hover:text-green-600 hover:bg-white rounded-lg transition-all" title="View Details">
                      <ExternalLink class="w-4 h-4" />
                    </button>
                    <button v-if="user?.role === 'Admin'" @click="archiveResearch(item.id)" class="p-2 text-gray-400 hover:text-red-500 hover:bg-white rounded-lg transition-all" title="Archive">
                      <ArchiveIcon class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-if="filteredResearches.length === 0" class="py-20 text-center">
          <List class="w-12 h-12 text-gray-200 mx-auto mb-4" />
          <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">No research logs match your criteria</p>
        </div>
      </div>

      <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div 
          v-for="item in filteredResearches" 
          :key="item.id" 
          class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:border-green-200 hover:shadow-xl transition-all flex flex-col group relative overflow-hidden"
        >
          <div 
            v-if="user"
            class="absolute top-0 right-0 px-4 py-1 text-[10px] font-black uppercase tracking-tighter"
            :class="{
              'bg-green-100 text-green-700': item.status === 'Published',
              'bg-red-100 text-red-700': item.status === 'Needs Revision',
              'bg-amber-100 text-amber-700': item.status === 'Under Review',
              'bg-gray-100 text-gray-600': item.status === 'Draft'
            }"
          >
            {{ item.status }}
          </div>

          <div class="flex justify-between items-start mb-4">
            <span class="text-xs font-black text-green-600 uppercase tracking-widest bg-green-50 px-3 py-1.5 rounded-full">
              {{ item.cropType }}
            </span>
          </div>
          
          <h3 class="text-xl font-bold text-gray-900 group-hover:text-green-700 transition-colors mb-4 leading-snug">
            {{ item.title }}
          </h3>
          
          <p class="text-gray-500 text-sm mb-6 line-clamp-3 italic">
            "{{ item.abstract }}"
          </p>
          
          <div class="flex flex-wrap gap-2 mb-6">
            <span v-for="tag in item.tags" :key="tag" class="text-[10px] font-bold bg-gray-50 text-gray-500 border border-gray-100 px-2 py-1 rounded-md">
              #{{ tag.toUpperCase() }}
            </span>
          </div>

          <div class="mt-auto border-t border-gray-50 pt-6 space-y-4">
            <div class="flex flex-col gap-2">
              <div class="flex items-center justify-between text-xs">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-green-50 rounded-full flex items-center justify-center font-bold text-green-600 border border-green-100">
                    {{ item.author.charAt(0) }}
                  </div>
                  <span class="font-bold text-gray-700">{{ item.author }}</span>
                </div>
                <div class="flex flex-col items-end gap-1">
                  <span 
                    v-if="item.deadlineDate" 
                    class="flex items-center gap-1 font-bold"
                    :class="new Date(item.deadlineDate) < new Date() && item.status !== 'Published' ? 'text-red-500' : 'text-gray-400'"
                  >
                    <Calendar class="w-3.5 h-3.5" />
                    Due: {{ item.deadlineDate }}
                  </span>
                  <span v-if="item.status === 'Published' && item.approvedDate" class="flex items-center gap-1 font-bold text-green-600 bg-green-50 px-2 py-0.5 rounded-md">
                    <History class="w-3.5 h-3.5" />
                    Approved: {{ item.approvedDate }}
                  </span>
                </div>
              </div>
            </div>

            <div class="flex gap-2">
              <button 
                @click="openPdfViewer(item)" 
                :disabled="!item.pdfUrl"
                class="flex-grow flex items-center justify-center gap-2 py-3 rounded-2xl text-xs font-bold transition-all"
                :class="item.pdfUrl ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
              >
                <FileText class="w-4 h-4" />
                Read Article
              </button>

              <button 
                @click="openComments(item)" 
                class="flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-600 px-5 py-3 rounded-2xl text-xs font-bold transition-all relative"
              >
                <MessageSquare class="w-4 h-4" />
                <span v-if="item.comments.length > 0" class="absolute -top-2 -right-1 bg-green-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] ring-4 ring-white">
                  {{ item.comments.length }}
                </span>
              </button>

              <template v-if="user?.role === 'Admin'">
                <button 
                  v-if="item.status === 'Under Review' || item.status === 'Needs Revision'"
                  @click="approveResearch(item.id)" 
                  class="bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-2xl text-xs font-bold transition-all flex items-center gap-2"
                >
                  <CheckCircle class="w-4 h-4" />
                  Approve
                </button>
                <button @click="archiveResearch(item.id)" class="p-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-2xl transition-all" title="Archive">
                  <ArchiveIcon class="w-5 h-5" />
                </button>
              </template>
              
              <button 
                v-if="user?.id === item.submitterId && (item.status === 'Draft' || item.status === 'Needs Revision')"
                class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 rounded-2xl text-xs font-bold flex items-center gap-2"
              >
                <Edit3 class="w-4 h-4" />
                Update
              </button>
            </div>
          </div>
        </div>
      </div>

      <div v-if="isModalOpen" class="fixed inset-0 bg-green-950/40 z-50 flex items-center justify-center p-4 backdrop-blur-md">
        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto">
          <div class="p-10 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white z-10">
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">New Research</h2>
            <button @click="isModalOpen = false" class="bg-gray-100 p-2 rounded-full text-gray-400 hover:text-red-500 transition-colors">
              <X class="w-6 h-6" />
            </button>
          </div>
          
          <form @submit.prevent="handleSubmit" class="p-10 space-y-8">
            <div class="grid md:grid-cols-2 gap-8">
              
              <div class="space-y-2 md:col-span-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Research Title</label>
                <input 
                  v-model="formData.title" 
                  required 
                  class="w-full p-4 bg-gray-50 border-transparent border-b-2 border-b-gray-200 focus:border-b-green-600 outline-none transition-all font-bold text-lg" 
                  placeholder="E.g. Sustainable Taro Farming" 
                />
              </div>

              <div class="space-y-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Author / Researcher</label>
                <input 
                  v-model="formData.author" 
                  required 
                  class="w-full p-4 bg-gray-50 border-transparent border-b-2 border-b-gray-200 focus:border-b-green-600 outline-none font-bold" 
                  placeholder="E.g. Dr. Juan Dela Cruz" 
                />
              </div>

              <div class="space-y-2">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Target Deadline</label>
                <input 
                  v-model="formData.deadlineDate" 
                  required 
                  type="date" 
                  class="w-full p-4 bg-gray-50 border-transparent border-b-2 border-b-gray-200 focus:border-b-green-600 outline-none font-bold" 
                />
              </div>
            </div>

            <div class="space-y-2">
              <div class="flex justify-between items-center px-1">
                <label class="text-sm font-black text-gray-400 uppercase tracking-widest">Research Abstract</label>
                <button type="button" @click="handleAIAnalyze" :disabled="isAnalyzing" class="text-xs flex items-center gap-2 text-green-600 hover:text-green-700 font-black tracking-widest uppercase">
                  <Loader2 v-if="isAnalyzing" class="w-4 h-4 animate-spin" />
                  <Sparkles v-else class="w-4 h-4" />
                  {{ isAnalyzing ? 'Analyzing...' : 'Smart Tags' }}
                </button>
              </div>
              <textarea v-model="formData.abstract" required rows="6" class="w-full p-6 bg-gray-50 border-2 border-transparent focus:border-green-100 focus:bg-white rounded-3xl outline-none transition-all text-gray-700 leading-relaxed font-medium" placeholder="Briefly describe the significance and methodology..."></textarea>
            </div>

            <div class="space-y-2">
              <label class="text-sm font-black text-gray-400 uppercase tracking-widest px-1">Research Document (PDF)</label>
              <div 
                @click="$refs.fileInputRef.click()"
                class="border-2 border-dashed rounded-3xl p-8 flex flex-col items-center justify-center transition-all cursor-pointer"
                :class="pdfFile ? 'bg-green-50 border-green-300' : 'bg-gray-50 border-gray-200 hover:border-green-400'"
              >
                <input 
                  type="file" 
                  ref="fileInputRef" 
                  @change="handleFileChange" 
                  accept="application/pdf" 
                  class="hidden" 
                />
                <div v-if="pdfFile" class="flex flex-col items-center gap-2">
                  <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <FileText class="text-white w-6 h-6" />
                  </div>
                  <p class="font-bold text-green-800 text-sm">{{ pdfFile.name }}</p>
                  <p class="text-xs text-green-500 font-medium">Click to change file</p>
                </div>
                <div v-else class="flex flex-col items-center gap-2 text-gray-400">
                  <FileUp class="w-10 h-10 mb-2" />
                  <p class="font-bold">Select PDF Document</p>
                  <p class="text-xs">Drag and drop or click to browse</p>
                </div>
              </div>
            </div>

            <div class="flex justify-end gap-4 pt-6">
              <button type="button" @click="isModalOpen = false" class="px-8 py-4 text-gray-400 font-bold hover:text-gray-600 transition-colors">Discard</button>
              <button type="submit" class="px-10 py-4 bg-green-600 text-white font-black tracking-widest uppercase rounded-2xl hover:bg-green-700 shadow-xl shadow-green-200 transition-all active:scale-95">
                Submit Research
              </button>
            </div>
          </form>
        </div>
      </div>

      <div v-if="isPdfViewerOpen && selectedResearch" class="fixed inset-0 bg-green-950/60 z-[60] flex items-center justify-center p-4 backdrop-blur-lg">
        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-5xl h-[90vh] flex flex-col overflow-hidden">
          <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center text-green-600">
                <FileText class="w-6 h-6" />
              </div>
              <div>
                <h3 class="font-black text-gray-900 leading-none mb-1 line-clamp-1">{{ selectedResearch.title }}</h3>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Document Viewer • {{ selectedResearch.author }}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button @click="isPdfViewerOpen = false" class="bg-gray-100 p-2 rounded-full text-gray-400 hover:text-red-500 transition-colors">
                <X class="w-6 h-6" />
              </button>
            </div>
          </div>
          <div class="flex-grow bg-gray-100 relative">
            <iframe 
              :src="selectedResearch.pdfUrl" 
              class="w-full h-full" 
              title="Research PDF Viewer"
              frameborder="0"
            ></iframe>
          </div>
        </div>
      </div>

      <div v-if="isCommentModalOpen && selectedResearch" class="fixed inset-0 bg-green-950/40 z-50 flex items-center justify-center p-4 backdrop-blur-md">
        <div class="bg-white rounded-[40px] shadow-2xl w-full max-w-xl flex flex-col h-[70vh]">
          <div class="p-8 border-b border-gray-100 flex justify-between items-center">
            <div>
              <h3 class="font-black text-gray-900 leading-none mb-1">Feedback Loop</h3>
              <p class="text-xs text-gray-400 font-bold uppercase tracking-widest truncate max-w-[200px]">{{ selectedResearch.title }}</p>
            </div>
            <button @click="isCommentModalOpen = false" class="bg-gray-100 p-2 rounded-full text-gray-400 hover:text-red-500 transition-colors">
              <X class="w-5 h-5" />
            </button>
          </div>
          
          <div class="flex-grow overflow-y-auto p-8 space-y-6">
            <div v-if="selectedResearch.comments.length === 0" class="text-center py-10">
              <MessageSquare class="w-12 h-12 text-gray-200 mx-auto mb-4" />
              <p class="text-gray-400 font-bold">No feedback yet.</p>
            </div>
            <div 
              v-for="c in selectedResearch.comments" 
              :key="c.id" 
              class="flex flex-col"
              :class="c.senderId === user?.id ? 'items-end' : 'items-start'"
            >
              <div 
                class="max-w-[85%] p-4 rounded-3xl text-sm font-medium"
                :class="c.senderId === user?.id ? 'bg-green-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-800 rounded-tl-none'"
              >
                {{ c.text }}
              </div>
              <span class="text-[10px] font-black text-gray-300 mt-1 uppercase tracking-tighter">
                {{ c.senderName }} • {{ c.date }}
              </span>
            </div>
          </div>

          <div class="p-8 border-t border-gray-100 bg-gray-50/50 rounded-b-[40px]">
            <div class="flex gap-3 items-center bg-white p-2 rounded-2xl border border-gray-200 shadow-sm">
              <input 
                v-model="commentText"
                @keydown.enter="handlePostComment"
                type="text" 
                class="flex-grow px-4 outline-none font-medium text-sm" 
                placeholder="Type a message or revision note..." 
              />
              <button @click="handlePostComment" class="bg-green-600 text-white p-2.5 rounded-xl hover:bg-green-700 transition-all">
                <Send class="w-4 h-4" />
              </button>
            </div>
            <div v-if="user?.role === 'Admin' && selectedResearch.status !== 'Published'" class="flex gap-4 mt-4">
              <button 
                @click="flagRevision"
                class="flex-1 py-2 text-[10px] font-black uppercase tracking-widest text-red-600 border border-red-200 rounded-xl hover:bg-red-50"
              >
                Flag Revision Needed
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import LandingNavbar from '../components/LandingNavbar.vue';
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { 
  Plus, Search, Filter, Sparkles, X, Loader2, Calendar, 
  CheckCircle, AlertTriangle, Info, MessageSquare, Send, 
  Archive as ArchiveIcon, Edit3, Trash2, History, List, ExternalLink,
  FileText, Upload, Eye, FileUp
} from 'lucide-vue-next'

// --- 1. STATE MANAGEMENT ---

// User & Data State
const user = ref(null)
const researches = ref([])
const isLoading = ref(false)

// UI State (Modals & Viewers)
const isModalOpen = ref(false)
const isCommentModalOpen = ref(false)
const isPdfViewerOpen = ref(false)
const selectedResearch = ref(null)
const commentText = ref('')
const isAnalyzing = ref(false)

// Search & Filter State
const searchTerm = ref('')
const filterType = ref('All')
const activeTab = ref('All')

// Form State
const fileInputRef = ref(null)
const pdfFile = ref(null)
const formData = ref({
  title: '',
  author: '',
  abstract: '',
  cropType: 'Sweet Potato',
  status: 'Under Review',
  deadlineDate: '',
  tags: []
})

const router = useRouter()

// --- 2. COMPUTED PROPERTIES ---

// Logic for calculating deadlines and overdue alerts
const deadlineAlerts = computed(() => {
  if (!user.value) return []
  const now = new Date()
  
  return researches.value
    .filter(r => r.submitterId === user.value.id && r.deadlineDate && r.status !== 'Published' && !r.isArchived)
    .map(r => {
      const deadline = new Date(r.deadlineDate)
      const diffDays = Math.ceil((deadline.getTime() - now.getTime()) / (1000 * 60 * 60 * 24))
      return { id: r.id, title: r.title, daysLeft: diffDays, isOverdue: diffDays < 0 }
    })
    .filter(alert => alert.daysLeft <= 14) 
})

// Logic for Filtering Tabs, Search, and Dropdowns
const filteredResearches = computed(() => {
  return researches.value.filter(item => {
    
    // 1. HANDLE ARCHIVE TAB (NEW LOGIC)
    if (activeTab.value === 'Archived') {
      // Only show items where isArchived is true/1
      return (item.isArchived == 1 || item.isArchived === true)
    }
    
    // 1. HIDE ARCHIVED ITEMS
    if (item.isArchived == 1 || item.isArchived === true) return false

    // 2. Search & Type Filter
    const matchesSearch = item.title.toLowerCase().includes(searchTerm.value.toLowerCase()) || 
                          item.author.toLowerCase().includes(searchTerm.value.toLowerCase())
    const matchesFilter = filterType.value === 'All' || item.cropType === filterType.value
    
    if (!matchesSearch || !matchesFilter) return false

    // 3. Tab Filters
    if (activeTab.value === 'Review Queue') {
      return user.value?.role === 'Admin' && (item.status === 'Under Review' || item.status === 'Needs Revision')
    }
    if (activeTab.value === 'My Submissions') {
      return item.submitterId === user.value?.id
    }
    if (activeTab.value === 'Research Logs') {
      return user.value?.role === 'Admin'
    }
    
    // Default: Public Gallery
    if (user.value?.role === 'Admin') return true 
    if (user.value?.role === 'Researcher') return item.status === 'Published' || item.submitterId === user.value.id
    
    return item.status === 'Published'
  })
})

// --- 3. CORE API METHODS ---

// Fetch Data from CodeIgniter
const fetchResearches = async () => {
  try {
    isLoading.value = true
    
    const params = {
      search: searchTerm.value,
      crop_type: filterType.value
    }

    const response = await axios.get('http://localhost:8080/api/researches', { params })
    
    researches.value = response.data.map(item => ({
      id: item.id,
      title: item.title,
      author: item.author || 'Unknown Author', 
      abstract: item.abstract,
      cropType: item.crop_type,
      date: item.created_at?.split(' ')[0],
      deadlineDate: item.deadline_date,
      status: item.status,
      submitterId: item.submitter_id,
      pdfUrl: item.pdf_path ? `http://localhost:8080/uploads/researches/${item.pdf_path}` : null,
      tags: [], 
      comments: [],
      isArchived: item.is_archived == 1
    }))
    
  } catch (error) {
    console.error('Fetch error:', error)
  } finally {
    isLoading.value = false
  }
}

// Submit New Research (with File Upload)
const handleSubmit = async () => {
  if (formData.value.title && formData.value.abstract) {
    try {
      const submissionData = new FormData();
      
      submissionData.append('title', formData.value.title);
      submissionData.append('author', formData.value.author);
      submissionData.append('abstract', formData.value.abstract);
      submissionData.append('deadline_date', formData.value.deadlineDate);
      submissionData.append('crop_type', 'Sweet Potato'); 
      submissionData.append('status', user.value?.role === 'Admin' ? 'Published' : 'Under Review');
      submissionData.append('submitter_id', user.value?.id || 1);

      if (pdfFile.value) {
        submissionData.append('pdf_file', pdfFile.value);
      }

      await axios.post('http://localhost:8080/api/researches', submissionData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });

      alert('Research submitted successfully!');
      isModalOpen.value = false;
      
      // Reset form
      formData.value = { title: '', author: 'user.value.name', abstract: '', cropType: 'Sweet Potato', status: 'Under Review', deadlineDate: '', tags: [] };
      pdfFile.value = null;
      
      // Refresh the grid to show new item
      fetchResearches(); 
      
    } catch (error) {
      console.error('Submission error:', error);
      alert('Failed to submit. See console.');
    }
  }
};

// --- 4. WATCHERS (Real-time Search) ---
let debounceTimer = null
watch([searchTerm, filterType], () => {
  if (debounceTimer) clearTimeout(debounceTimer)
  debounceTimer = setTimeout(() => {
    fetchResearches()
  }, 500)
})

// --- 5. UI HELPER METHODS ---

const getStatusColor = (status) => {
  switch(status) {
    case 'Published': return 'bg-green-100 text-green-700 border-green-200'
    case 'Needs Revision': return 'bg-red-100 text-red-700 border-red-200'
    case 'Under Review': return 'bg-amber-100 text-amber-700 border-amber-200'
    default: return 'bg-gray-100 text-gray-700 border-gray-200'
  }
}

const handleFileChange = (event) => {
  const file = event.target.files[0]
  if (file) pdfFile.value = file
}

const handleAIAnalyze = async () => {
  if (!formData.value.title || !formData.value.abstract) {
    return alert("Fill title and abstract first.")
  }
  isAnalyzing.value = true
  setTimeout(() => {
    formData.value.tags = ['ai-generated', 'smart-tag', 'root-crop']
    isAnalyzing.value = false
  }, 1500)
}

// Viewer & Comments Logic
const openPdfViewer = (item) => {
  if (!item.pdfUrl) return alert("No PDF file attached.")
  selectedResearch.value = item
  isPdfViewerOpen.value = true
}

const openComments = (item) => {
  selectedResearch.value = item
  isCommentModalOpen.value = true
}

const handlePostComment = () => {
  if (selectedResearch.value && commentText.value.trim()) {
    const newComment = {
        id: Date.now().toString(),
        senderId: user.value.id,
        senderName: user.value.name,
        text: commentText.value,
        date: new Date().toISOString().split('T')[0]
    }
    selectedResearch.value.comments.push(newComment)
    commentText.value = ''
  }
}

// Admin Actions
const approveResearch = (id) => {
    const item = researches.value.find(r => r.id === id)
    if(item) {
        item.status = 'Published'
        item.approvedDate = new Date().toISOString().split('T')[0]
    }
}

const flagRevision = () => {
    if(selectedResearch.value) {
        selectedResearch.value.status = 'Needs Revision'
        handlePostComment() 
        isCommentModalOpen.value = false
    }
}

const archiveResearch = async (id) => {
  if (confirm('Are you sure you want to archive this research? It will be moved to the Archive page.')) {
    try {
      // 1. Call the backend API
      await axios.put(`http://localhost:8080/api/researches/${id}/archive`);
      
      // 2. Refresh the list (This removes it from the current view because fetchResearches filters out archived items)
      await fetchResearches();
      
      // Optional: Show success alert
      // alert('Moved to Archive');
    } catch (error) {
      console.error('Archive error:', error);
      alert('Failed to archive item.');
    }
  }
}

// --- 6. LIFECYCLE HOOKS ---
onMounted(() => {
  // 1. Check LocalStorage for User
  const storedUser = localStorage.getItem('user')
  
  if (storedUser) {
    // 2. Load User Data
    user.value = JSON.parse(storedUser)
    
    // 3. Pre-fill the "Author" field in the form with the logged-in user's name
    // Note: Database uses 'full_name', frontend logic used 'name'.
    // Adjust based on your AuthApi response.
    const realName = user.value.full_name || user.value.name;
    formData.value.author = realName;
    user.value.name = realName; // Standardize for UI
    
    // 4. Fetch Data
    fetchResearches()
  } else {
    // 5. No User? Go back to Login
    router.push('/signin') 
  }
})
</script>