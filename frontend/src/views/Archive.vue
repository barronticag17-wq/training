<template>
  <div class="space-y-6 p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Research Archive</h1>
        <p class="text-gray-500">Items here are hidden from the public gallery.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      
      <div 
        v-for="item in archivedItems" 
        :key="item.id" 
        class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 opacity-75 grayscale hover:grayscale-0 hover:opacity-100 transition-all"
      >
        <div class="flex justify-between items-start mb-3">
          <span class="text-xs font-bold text-gray-500 uppercase tracking-wide bg-gray-100 px-2 py-1 rounded">
            {{ item.cropType }}
          </span>
          <span class="text-xs text-red-500 border border-red-200 px-2 py-1 rounded-full font-medium">
            Archived
          </span>
        </div>
        
        <h3 class="text-lg font-bold text-gray-700 mb-2">{{ item.title }}</h3>
        <p class="text-sm text-gray-400 mb-4 line-clamp-2">{{ item.abstract }}</p>
        
        <button 
          @click="restoreResearch(item.id)"
          class="w-full mt-auto flex items-center justify-center gap-2 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors font-medium text-sm"
        >
          <ArchiveRestore class="w-4 h-4" />
          Restore to Repository
        </button>
      </div>

      <div v-if="archivedItems.length === 0" class="col-span-full py-20 text-center border-2 border-dashed border-gray-200 rounded-2xl">
        <FileText class="w-12 h-12 text-gray-300 mx-auto mb-4" />
        <p class="text-gray-400 font-medium">No archived items found.</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { ArchiveRestore, FileText } from 'lucide-vue-next'

// --- MOCK DATA ---
// In a real app, this would come from the same Store/API as your Researches page.
// I've added some dummy data here so you can see it working immediately.
const researches = ref([
  {
    id: 'archived-1',
    title: 'Old Study on Potato Blight 2019',
    author: 'Dr. Old',
    abstract: 'This study is outdated and has been superseded by newer findings in 2023.',
    cropType: 'White Potato',
    isArchived: true
  },
  {
    id: 'archived-2',
    title: 'Discarded Draft: Ube Planting',
    author: 'Researcher X',
    abstract: 'Initial findings were inconclusive regarding the soil acidity levels.',
    cropType: 'Ube',
    isArchived: true
  }
])

// --- COMPUTED ---
const archivedItems = computed(() => {
  return researches.value.filter(r => r.isArchived)
})

// --- ACTIONS ---
const restoreResearch = (id) => {
  if (confirm('Are you sure you want to restore this item to the public repository?')) {
    // Logic to flip the boolean
    const item = researches.value.find(r => r.id === id)
    if (item) {
      item.isArchived = false
      // In a real app, you would make an API call here:
      // await axios.put(`/api/research/${id}/restore`)
    }
  }
}
</script>