import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'

export function useResearches(user) {
  // STATE
  const researches = ref([])
  const isLoading = ref(false)
  const activeTab = ref('All')

  const filters = ref({
    search: '',
    startDate: '',
    endDate: '',
    docType: 'All',
    status: 'All',
  })

  // COMPUTED LOGIC
  const filteredResearches = computed(() => {
    return researches.value.filter(item => {
      
      // 1. HIDE ARCHIVED (Admin Tab Logic)
      if (activeTab.value === 'Archived') {
        return (item.isArchived == 1 || item.isArchived === true)
      }
      if (item.isArchived == 1 || item.isArchived === true) return false

      // 2. Keyword Search (Title OR Summary/Abstract)
      const term = filters.value.search.toLowerCase()
      const matchesKeyword = item.title.toLowerCase().includes(term) || 
                            (item.author && item.author.toLowerCase().includes(term)) ||
                            (item.abstract && item.abstract.toLowerCase().includes(term))

      // 3. Date Filter

      const itemDate = new Date(item.deadlineDate || item.date); // Ensure you pick the correct date field
      const start = filters.value.startDate ? new Date(filters.value.startDate) : null;
      const end = filters.value.endDate ? new Date(filters.value.endDate) : null;

      const matchesDate = (!start || itemDate >= start) && 
                    (!end || itemDate <= end);


      // 4. Document Type Filter
      const matchesDocType = filters.value.docType === 'All' || item.docType === filters.value.docType

      // 5. Status Filter
      const matchesStatus = filters.value.status === 'All' || item.status === filters.value.status

      // Combine all filters
      if (!matchesKeyword || !matchesDate || !matchesDocType || !matchesStatus) return false

      // 6. Tab Permission Logic
      // Note: We access user.value because 'user' is a Ref passed from the component
      const currentUser = user.value

      // 6. Existing Tab Permission Logic
      if (activeTab.value === 'All') {
        return item.status === 'Published';
      }
      if (activeTab.value === 'Review Queue') {
        return currentUser?.role === 'Admin' && (item.status === 'Under Review' || item.status === 'Needs Revision')
      }
      if (activeTab.value === 'My Submissions') {
        return item.submitterId == currentUser?.id 
      }
      if (activeTab.value === 'Research Logs') {
        return currentUser?.role === 'Admin'
      }

      return true
    })
  })

  // CORE API METHODS (fetches data from API)
  const fetchResearches = async () => {
    try {
      isLoading.value = true
      
      const params = {
        search: filters.value.search,
        crop_type: 'All' // Default: Show all crops
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

  // ADMIN ACTIONS
  const approveResearch = async (id) => {
    if (!confirm('Are you sure you want to approve and publish this research?')) return;

    try {
      // Note: Changed from fetch() to axios for consistency
      await axios.put(`http://localhost:8080/api/researches/${id}/approve`);

      // Update local state immediately
      const index = researches.value.findIndex(r => r.id === id);
      if (index !== -1) {
        researches.value[index].status = 'Published';
      }
      alert('Research published successfully!');
    } catch (error) {
      console.error('Error:', error);
      alert('Failed to approve research.');
    }
  };

  // The Watcher
  let debounceTimer = null
  watch(filters, () => {
    if (debounceTimer) clearTimeout(debounceTimer)
    debounceTimer = setTimeout(() => {
      fetchResearches()
    }, 500)
  }, { deep: true })

  return {
    researches,
    filteredResearches,
    filters,
    activeTab,
    isLoading,
    fetchResearches,
    approveResearch
  }
}