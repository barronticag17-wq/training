<template>
  <div 
    v-if="viewMode === 'card'"
    class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:border-green-200 hover:shadow-xl transition-all flex flex-col group relative overflow-hidden h-full"
  >
    <div 
      v-if="user"
      class="absolute top-0 right-0 px-4 py-1 text-[10px] font-black uppercase tracking-tighter"
      :class="statusClasses"
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
            <span v-if="item.deadlineDate" class="flex items-center gap-1 font-bold" :class="isOverdue ? 'text-red-500' : 'text-gray-400'">
              <Calendar class="w-3.5 h-3.5" />
              Due: {{ item.deadlineDate }}
            </span>
          </div>
        </div>
      </div>

      <div class="flex gap-2">
        <button 
          @click="$emit('viewPdf', item)" 
          :disabled="!item.pdfUrl"
          class="flex-grow flex items-center justify-center gap-2 py-3 rounded-2xl text-xs font-bold transition-all"
          :class="item.pdfUrl ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
        >
          <FileText class="w-4 h-4" />
          Read Article
        </button>
        
        <button @click="$emit('viewComments', item)" class="flex items-center justify-center gap-2 bg-gray-50 hover:bg-gray-100 text-gray-600 px-5 py-3 rounded-2xl text-xs font-bold transition-all relative">
          <MessageSquare class="w-4 h-4" />
          <span v-if="item.comments.length > 0" class="absolute -top-2 -right-1 bg-green-600 text-white w-5 h-5 rounded-full flex items-center justify-center text-[10px] ring-4 ring-white">
            {{ item.comments.length }}
          </span>
        </button>

        <template v-if="user?.role === 'Admin'">
          <button v-if="['Under Review', 'Needs Revision'].includes(item.status)" @click="$emit('approve', item.id)" class="bg-green-500 hover:bg-green-600 text-white px-3 rounded-2xl transition-all">
            <CheckCircle class="w-4 h-4" />
          </button>
          <button @click="$emit('archive', item.id)" class="px-3 bg-red-50 text-red-600 hover:bg-red-100 rounded-2xl transition-all">
            <ArchiveIcon class="w-4 h-4" />
          </button>
        </template>
        
        <button v-if="user?.id === item.submitterId && ['Draft', 'Needs Revision'].includes(item.status)" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-3 rounded-2xl text-xs font-bold">
          <Edit3 class="w-4 h-4" />
        </button>
      </div>
    </div>
  </div>

  <div 
    v-else
    class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 hover:border-green-200 hover:shadow-md transition-all flex items-center gap-6 group relative"
  >
    <div class="absolute left-0 top-0 bottom-0 w-1.5 rounded-l-xl" :class="statusClasses.split(' ')[0].replace('bg-', 'bg-')"></div>

    <div class="flex-shrink-0">
        <div class="w-10 h-10 bg-green-50 rounded-full flex items-center justify-center font-bold text-green-600 border border-green-100 text-sm">
            {{ item.author.charAt(0) }}
        </div>
    </div>

    <div class="flex-grow min-w-0 flex flex-col gap-1">
        <div class="flex items-center gap-2">
            <span class="text-[10px] font-black text-green-600 uppercase tracking-widest bg-green-50 px-2 py-0.5 rounded-md">
                {{ item.cropType }}
            </span>
            <h3 class="text-sm font-bold text-gray-900 truncate group-hover:text-green-700 transition-colors">
                {{ item.title }}
            </h3>
            <span v-if="item.status" class="ml-2 text-[10px] font-bold px-2 py-0.5 rounded-full" :class="statusClasses">
                {{ item.status }}
            </span>
        </div>
        <div class="flex items-center gap-4 text-xs text-gray-500">
            <span class="font-medium text-gray-700">{{ item.author }}</span>
            <span v-if="item.deadlineDate" class="flex items-center gap-1" :class="isOverdue ? 'text-red-500 font-bold' : ''">
                 <Calendar class="w-3 h-3" /> {{ item.deadlineDate }}
            </span>
            <span v-if="item.comments.length > 0" class="flex items-center gap-1 text-green-600 font-bold">
                 <MessageSquare class="w-3 h-3" /> {{ item.comments.length }} comments
            </span>
        </div>
    </div>

    <div class="flex items-center gap-2">
        <button 
          @click="$emit('viewPdf', item)" 
          :disabled="!item.pdfUrl"
          class="p-2 rounded-lg transition-all"
          :class="item.pdfUrl ? 'bg-green-50 text-green-600 hover:bg-green-600 hover:text-white' : 'bg-gray-50 text-gray-300'"
          title="Read Article"
        >
          <FileText class="w-4 h-4" />
        </button>

        <button @click="$emit('viewComments', item)" class="p-2 bg-gray-50 text-gray-500 hover:bg-gray-100 hover:text-gray-700 rounded-lg transition-all" title="View Comments">
          <MessageSquare class="w-4 h-4" />
        </button>

        <template v-if="user?.role === 'Admin'">
          <button v-if="['Under Review', 'Needs Revision'].includes(item.status)" @click="$emit('approve', item.id)" class="p-2 bg-green-50 text-green-600 hover:bg-green-500 hover:text-white rounded-lg transition-all" title="Approve">
            <CheckCircle class="w-4 h-4" />
          </button>
          <button @click="$emit('archive', item.id)" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-all" title="Archive">
            <ArchiveIcon class="w-4 h-4" />
          </button>
        </template>
        
        <button v-if="user?.id === item.submitterId && ['Draft', 'Needs Revision'].includes(item.status)" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white rounded-lg transition-all" title="Edit">
          <Edit3 class="w-4 h-4" />
        </button>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { Calendar, FileText, MessageSquare, CheckCircle, Archive as ArchiveIcon, Edit3 } from 'lucide-vue-next'

// Added 'viewMode' to props
const props = defineProps({
    item: Object,
    user: Object,
    viewMode: {
        type: String,
        default: 'card' // Options: 'card' | 'list'
    }
})

const emit = defineEmits(['viewPdf', 'viewComments', 'approve', 'archive'])

const isOverdue = computed(() => {
  if (!props.item.deadlineDate) return false
  return new Date(props.item.deadlineDate) < new Date() && props.item.status !== 'Published'
})

const statusClasses = computed(() => {
  switch(props.item.status) {
    case 'Published': return 'bg-green-100 text-green-700'
    case 'Needs Revision': return 'bg-red-100 text-red-700'
    case 'Under Review': return 'bg-amber-100 text-amber-700'
    default: return 'bg-gray-100 text-gray-600'
  }
})
</script>