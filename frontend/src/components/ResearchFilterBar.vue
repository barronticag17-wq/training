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
      <div class="relative">
        <Calendar class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4" />
        <input 
          :value="modelValue.date"
          @input="update('date', $event.target.value)"
          type="date"
          class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 outline-none appearance-none bg-white text-sm font-bold text-gray-600"
        />
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
import { Search, Calendar, FileText, CheckCircle, Filter } from 'lucide-vue-next'

const props = defineProps(['modelValue']) // Receives the 'filters' object
const emit = defineEmits(['update:modelValue'])

const update = (key, value) => {
  // Emits a new object with the updated field
  emit('update:modelValue', { ...props.modelValue, [key]: value })
}
</script>