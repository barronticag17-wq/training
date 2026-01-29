<template>
  <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 space-y-4">
    <div class="relative">
      <Search class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
      <input 
        :value="modelValue.search"
        @input="update('search', $event.target.value)"
        type="text" 
        placeholder="Search keywords in title or summary..." 
        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none transition-all font-medium" 
      />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="flex items-center gap-2 w-full">
        <div class="relative flex-1">
          <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-3.5 h-3.5" />
          <input 
            :value="modelValue.startDate"
            @input="update('startDate', $event.target.value)"
            type="date"
            placeholder="Start"
            class="w-full pl-9 pr-2 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white text-xs font-bold text-gray-600"
          />
          <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-gray-400">From</label>
        </div>

        <span class="text-gray-300 font-bold">-</span>

        <div class="relative flex-1">
          <Calendar class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-3.5 h-3.5" />
          <input 
            :value="modelValue.endDate"
            @input="update('endDate', $event.target.value)"
            type="date"
            placeholder="End"
            class="w-full pl-9 pr-2 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white text-xs font-bold text-gray-600"
          />
          <label class="absolute -top-2 left-3 bg-white px-1 text-[10px] font-bold text-gray-400">To</label>
        </div>

        <button 
          v-if="modelValue.startDate || modelValue.endDate"
          @click="clearDates"
          class="p-2 bg-gray-100 hover:bg-red-50 text-gray-400 hover:text-red-500 rounded-lg transition-colors"
          title="Clear Dates"
        >
          <X class="w-4 h-4" />
        </button>
      </div>

      <div class="relative">
        <FileText class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4" />
        <select 
          :value="modelValue.docType"
          @change="update('docType', $event.target.value)"
          class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white text-sm font-bold text-gray-600"
        >
          <option value="All">All Doc Types</option>
          <option value="Thesis">Thesis</option>
          <option value="Dissertation">Dissertation</option>
          <option value="Journal">Journal Article</option>
          <option value="Report">Technical Report</option>
        </select>
        <Filter class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 w-3 h-3 pointer-events-none" />
      </div>

      <div class="relative">
        <CheckCircle class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4" />
        <select 
          :value="modelValue.status"
          @change="update('status', $event.target.value)"
          class="w-full pl-10 pr-10 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white text-sm font-bold text-gray-600"
        >
          <option value="All">All Statuses</option>
          <option value="Published">Published</option>
          <option value="Under Review">Under Review</option>
          <option value="Needs Revision">Needs Revision</option>
          <option value="Draft">Draft</option>
        </select>
        <Filter class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 w-3 h-3 pointer-events-none" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { Search, Calendar, FileText, CheckCircle, Filter, X } from 'lucide-vue-next'

const props = defineProps(['modelValue']) // Receives the 'filters' object
const emit = defineEmits(['update:modelValue'])

const update = (key, value) => {
  // Emits a new object with the updated field
  emit('update:modelValue', { ...props.modelValue, [key]: value })
}

const clearDates = () => {
  emit('update:modelValue', { 
    ...props.modelValue, 
    startDate: '', 
    endDate: '' 
  })
}
</script>