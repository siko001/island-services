<template>
  <div class="p-4 h-[100vh] sm:p-8 relative scrollbar-hidden overflow-scroll">
    <div class=" overflow-hidden  flex flex-col gap-4 sm:gap-8">
      <div v-if="logs?.length === 0" class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200">
        <p class="text-gray-800 text-2xl font-bold">No logs found.</p>
      </div>
      <div v-for="log in logs" :key="log.id" class="bg-neutral-800 dark:bg-neutral-200  sm:w-full p-4 py-8 sm:p-8 rounded-xl max-w-[850px] text-white dark:text-black w-full mx-auto shadow">
        <div class="flex flex-col-reverse sm:flex-row items-start justify-between sm:items-center gap-2 sm:gap-8">
          <div>
            <p>
              <span class="font-semibold">
                {{ log.success ? 'Logged in Email:' : 'Attempted Email:' }}
              </span> {{ log.email }} </p>
          </div>
          <div class="p-1 text-xs rounded-sm relative -top-2 sm:-right-2 text-white" :class="log.success ? 'bg-green-700' : 'bg-red-700'">
            <p>{{ log.success ? 'Success' : 'Failed' }}</p>
          </div>
        </div>
        <p>
          <span class="font-bold">IP Address: </span> {{ log?.ip_address }}<br/>
        </p>
        <p>
          <span class="font-bold">Date:</span> {{ formatDate(log?.created_at) }} </p>
        <p>
          <span class="font-bold">Time:</span> {{ formatTime(log?.created_at) }} </p>
        <p class="relative max-sm:left-0.5 -bottom-4 text-xs text-gray-500">
          {{ formatRelative(log?.created_at) }} </p>
      </div>
      <!-- Pagination Controls -->
      <div v-if="pagination && pagination.last_page > 1" class="mt-6 flex justify-center items-center gap-2 text-sm text-white dark:text-black">
        <button class="px-2 py-1 rounded bg-gray-200" @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1">Previous</button>
        <span class="text-dark dark:text-white">Page {{ pagination.current_page }} of {{ pagination.last_page }}</span>
        <button class="px-2 py-1 rounded bg-gray-200" @click="changePage(pagination.current_page + 1)" :disabled="pagination.current_page === pagination.last_page">Next</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import {onMounted, ref} from "vue";

const formatDate = dt => new Date(dt).toLocaleDateString('mt', {day: '2-digit', month: 'short', year: 'numeric'});
const formatTime = dt => new Date(dt).toLocaleTimeString('mt', {hour12: false});
const formatRelative = dt => {
  const diff = (Date.now() - new Date(dt)) / 1000;
  if(diff < 60) return 'just now';
  if(diff < 3600) return Math.floor(diff / 60) + ' minutes ago';
  if(diff < 86400) return Math.floor(diff / 3600) + ' hours ago';
  return Math.floor(diff / 86400) + ' days ago';
}

const logs = ref([]);
const pagination = ref(null);

const fetchLogs = (page = 1) => {
  Nova.request().get(`/nova-vendor/login-logs?page=${page}`).then(response => {
    logs.value = response.data.logs.data; // paginated array
    pagination.value = {
      current_page: response.data.logs.current_page,
      last_page: response.data.logs.last_page,
      total: response.data.logs.total,
    };
  });
};

const changePage = (page) => {
  if(page >= 1 && page <= pagination.value.last_page) {
    fetchLogs(page);
  }
};

onMounted(() => {
  fetchLogs();
});
</script>
