<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- System Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">System Statistics</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-blue-600">{{ systemStats.totalUsers }}</p>
                                    <p class="text-sm text-gray-600">Total Users</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-green-600">{{ systemStats.totalUploads }}</p>
                                    <p class="text-sm text-gray-600">Total Uploads</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-yellow-600">{{ systemStats.activeUploads }}</p>
                                    <p class="text-sm text-gray-600">Active Processing</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-3xl font-bold text-red-600">{{ systemStats.failedUploads }}</p>
                                    <p class="text-sm text-gray-600">Failed Uploads</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                            <div class="space-y-3">
                                <div v-for="activity in recentActivity" :key="activity.id" class="flex items-center space-x-3">
                                    <div :class="getActivityIconClass(activity.type)" class="w-8 h-8 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-bold">{{ getActivityIcon(activity.type) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ activity.description }}</p>
                                        <p class="text-xs text-gray-500">{{ formatDate(activity.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">User Management</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Uploads
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Last Active
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">
                                                            {{ user.name.charAt(0).toUpperCase() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ user.email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ user.uploads_count }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(user.last_login_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                @click="viewUserDetails(user.id)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                View
                                            </button>
                                            <button
                                                @click="toggleUserStatus(user.id, user.is_active)"
                                                :class="user.is_active ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900'"
                                            >
                                                {{ user.is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- System Uploads -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">All Uploads</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            User
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Filename
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Uploaded
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="upload in allUploads" :key="upload.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ upload.user.name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ upload.original_filename }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusClass(upload.status)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                                {{ upload.status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ formatDate(upload.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <button
                                                @click="viewUploadDetails(upload.id)"
                                                class="text-blue-600 hover:text-blue-900 mr-3"
                                            >
                                                View
                                            </button>
                                            <button
                                                v-if="upload.status === 'failed'"
                                                @click="retryUpload(upload.id)"
                                                class="text-yellow-600 hover:text-yellow-900"
                                            >
                                                Retry
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import axios from 'axios'

const systemStats = ref({
    totalUsers: 0,
    totalUploads: 0,
    activeUploads: 0,
    failedUploads: 0
})

const recentActivity = ref([])
const users = ref([])
const allUploads = ref([])

const loadSystemStats = async () => {
    try {
        const response = await axios.get('/api/admin/stats')
        systemStats.value = response.data.data
    } catch (error) {
        console.error('Failed to load system stats:', error)
    }
}

const loadRecentActivity = async () => {
    try {
        const response = await axios.get('/api/admin/activity')
        recentActivity.value = response.data.data
    } catch (error) {
        console.error('Failed to load recent activity:', error)
    }
}

const loadUsers = async () => {
    try {
        const response = await axios.get('/api/admin/users')
        users.value = response.data.data
    } catch (error) {
        console.error('Failed to load users:', error)
    }
}

const loadAllUploads = async () => {
    try {
        const response = await axios.get('/api/admin/uploads')
        allUploads.value = response.data.data
    } catch (error) {
        console.error('Failed to load uploads:', error)
    }
}

const getStatusClass = (status) => {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'processing': 'bg-blue-100 text-blue-800',
        'completed': 'bg-green-100 text-green-800',
        'failed': 'bg-red-100 text-red-800'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
}

const getActivityIcon = (type) => {
    const icons = {
        'user_registered': 'U',
        'file_uploaded': 'F',
        'file_processed': 'P',
        'system_error': 'E'
    }
    return icons[type] || 'A'
}

const getActivityIconClass = (type) => {
    const classes = {
        'user_registered': 'bg-blue-100 text-blue-600',
        'file_uploaded': 'bg-green-100 text-green-600',
        'file_processed': 'bg-yellow-100 text-yellow-600',
        'system_error': 'bg-red-100 text-red-600'
    }
    return classes[type] || 'bg-gray-100 text-gray-600'
}

const formatDate = (date) => {
    return new Date(date).toLocaleDateString()
}

const viewUserDetails = (userId) => {
    router.visit(`/admin/users/${userId}`)
}

const toggleUserStatus = async (userId, currentStatus) => {
    try {
        await axios.patch(`/api/admin/users/${userId}/toggle-status`)
        loadUsers()
    } catch (error) {
        console.error('Failed to toggle user status:', error)
    }
}

const viewUploadDetails = (uploadId) => {
    router.visit(`/admin/uploads/${uploadId}`)
}

const retryUpload = async (uploadId) => {
    try {
        await axios.post(`/api/admin/uploads/${uploadId}/retry`)
        loadAllUploads()
    } catch (error) {
        console.error('Failed to retry upload:', error)
    }
}

onMounted(() => {
    loadSystemStats()
    loadRecentActivity()
    loadUsers()
    loadAllUploads()
})
</script>
