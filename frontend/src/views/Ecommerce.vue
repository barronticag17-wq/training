<template>
  <admin-layout>
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-black dark:text-white">User Management</h1>
      <p class="text-gray-500 text-sm">Create, Read, Update, and Delete users from the CI4 Database.</p>
    </div>

    <div class="mb-6 p-4 bg-white rounded-sm border border-stroke shadow-default dark:border-strokedark dark:bg-boxdark">
      <h3 class="font-medium text-black dark:text-white mb-4">
        {{ isEditing ? '📝 Editing User: ' + currentUserId : '➕ Add New User' }}
      </h3>
      
      <div class="flex flex-col md:flex-row gap-4">
        <input 
          v-model="newUser.username" 
          type="text" 
          placeholder="Username" 
          class="w-full rounded border-[1.5px] border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input text-black dark:text-white"
        />
        <input 
          v-model="newUser.email" 
          type="email" 
          placeholder="Email" 
          class="w-full rounded border-[1.5px] border-stroke bg-transparent py-2 px-4 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input text-black dark:text-white"
        />
        
        <div class="flex gap-2">
          <button 
            @click="isEditing ? saveUpdate() : addUser()"
            :class="isEditing ? 'bg-orange-500 hover:bg-orange-600' : 'bg-primary hover:bg-opacity-90'"
            class="rounded px-6 py-2 font-medium text-black whitespace-nowrap transition"
          >
            {{ isEditing ? 'Save Changes' : 'Add User' }}
          </button>

          <button 
            v-if="isEditing" 
            @click="cancelEdit" 
            class="rounded bg-gray-400 px-6 py-2 font-medium text-black hover:bg-gray-500 transition"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>

    <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="px-4 py-6 md:px-6 xl:px-7.5">
        <h2 class="text-xl font-bold text-black dark:text-white mb-4">Database Records</h2>
        
        <div class="flex flex-col">
          <div class="grid grid-cols-4 rounded-sm bg-gray-2 dark:bg-meta-4">
            <div class="p-2.5 xl:p-5 text-sm font-medium uppercase text-black dark:text-white">ID</div>
            <div class="p-2.5 xl:p-5 text-sm font-medium uppercase text-black dark:text-white">Username</div>
            <div class="p-2.5 xl:p-5 text-sm font-medium uppercase text-black dark:text-white">Email</div>
            <div class="p-2.5 xl:p-5 text-sm font-medium uppercase text-center text-black dark:text-white">Actions</div>
          </div>

          <div v-if="users.length > 0">
            <div 
              v-for="user in users" 
              :key="user.id" 
              class="grid grid-cols-4 border-b border-stroke dark:border-strokedark items-center"
            >
              <div class="p-2.5 xl:p-5 text-black dark:text-white">{{ user.id }}</div>
              <div class="p-2.5 xl:p-5 text-black dark:text-white">{{ user.username }}</div>
              <div class="p-2.5 xl:p-5 text-primary font-medium">{{ user.email }}</div>
              <div class="p-2.5 xl:p-5 flex justify-center gap-4">
                <button @click="editUser(user)" class="text-primary hover:underline font-medium">
                  Edit
                </button>
                <button @click="deleteUser(user.id)" class="text-danger hover:underline font-medium">
                  Delete
                </button>
              </div>
            </div>
          </div>
          
          <div v-else class="p-10 text-center text-gray-400 italic">
            No users found in the database.
          </div>
        </div>
      </div>
    </div>
  </admin-layout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '../components/layout/AdminLayout.vue'

// --- 1. STATE MANAGEMENT ---
const users = ref([])
const newUser = ref({ username: '', email: '' })
const isEditing = ref(false)
const currentUserId = ref(null)

// --- 2. API FUNCTIONS ---

// Fetch all users
const fetchUsers = async () => {
  try {
    const response = await axios.get('http://localhost:8080/api/users')
    users.value = response.data
  } catch (err) {
    console.error('Fetch Error:', err)
  }
}

// Add a new user
const addUser = async () => {
  if (!newUser.value.username || !newUser.value.email) {
    alert('Please enter both username and email.')
    return
  }
  try {
    await axios.post('http://localhost:8080/api/users', newUser.value)
    newUser.value = { username: '', email: '' }
    fetchUsers() // Refresh the table
  } catch (err) {
    console.error('Add Error:', err)
  }
}

// Prepare form for editing
const editUser = (user) => {
  isEditing.value = true
  currentUserId.value = user.id
  // Fill the form fields with user data
  newUser.value = { username: user.username, email: user.email }
  // Scroll to top so user sees the form
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

// Save the edited user
const saveUpdate = async () => {
  try {
    await axios.put(`http://localhost:8080/api/users/${currentUserId.value}`, newUser.value)
    cancelEdit() // Reset the form
    fetchUsers() // Refresh the table
  } catch (err) {
    console.error('Update Error:', err)
  }
}

// Delete a user
const deleteUser = async (id) => {
  if (confirm('Are you sure you want to delete this user?')) {
    try {
      await axios.delete(`http://localhost:8080/api/users/${id}`)
      fetchUsers()
    } catch (err) {
      console.error('Delete Error:', err)
    }
  }
}

// Reset form and mode
const cancelEdit = () => {
  isEditing.value = false
  currentUserId.value = null
  newUser.value = { username: '', email: '' }
}

// --- 3. LIFECYCLE ---
onMounted(fetchUsers)
</script>