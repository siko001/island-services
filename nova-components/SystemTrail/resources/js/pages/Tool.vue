<template>
  <div style="max-height:2000px; height:140vh;" class="p-4 relative h-[100vh] sm:p-8  scrollbar-hidden overflow-scroll main-system-template">
    <div class="text-4xl mx-auto w-full  mb-8">System Logs</div>
    <div v-if="canViewAuditTrail">
      <div v-if="logs.data.length === 0" class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200">
        <p class="text-gray-800 text-2xl font-bold">No logs found.</p>
      </div>
      <div v-for="(log, index) in logs.data" :key="log.id" class="bg-white shadow rounded-lg p-6 mb-4 border border-gray-200">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-lg font-semibold text-gray-800">
            Log #{{ log.id }}: {{ log.name }} </h3>
          <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800 font-medium">
            {{ capitalizeFirst(log.status) }}
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
          <div>
            <div>
              <span class="font-medium">Batch ID:</span> #{{ log.batch_id }}
            </div>
            <div>
              <span class="font-medium">User ID:</span> {{ log.user_id }}
            </div>
            <div>
              <span class="font-medium">User Performing Action:</span> {{ log.username }}
            </div>
            <div>
              <span class="font-medium">Actionable:</span> {{ getActionableName(log) }} #{{ log.actionable_id }}
            </div>
            <div>
              <span class="font-medium">Target:</span> {{ getClassBasename(log.target_type) }} #{{ log.target_id }}
            </div>
            <div>
              <span class="font-medium">Data:</span> {{ getClassBasename(log.model_type) }} {{ log.model_id ? '#' + log.model_id : '' }}
            </div>
          </div>

          <div>
            <div>
              <span class="font-medium">Created:</span> {{ formatDate(log.created_at) }}
            </div>
            <div>
              <span class="font-medium">Updated:</span> {{ formatDate(log.updated_at) }}
            </div>
            <div>
              <span class="font-medium">Exception:</span> <span v-if="log.exception" class="text-red-600">{{ log.exception }}</span> <span v-else class="text-gray-400">None</span>
            </div>
          </div>
        </div>

        <div v-if="log.changes && log.changes !== '[]'" class="mt-4">
          <div class="font-semibold text-gray-800 mb-1">
            {{ changeTitle(log.name) }}
          </div>

          <div class="bg-gray-50 rounded p-3 text-xs">
            <ul class="list-disc pl-5 space-y-1">
              <li v-for="(value, key) in parseChanges(log.changes)" :key="key">
                <span class="font-bold text-black">{{ formatKey(key) }}:</span> <span class="text-gray-700">{{ formatValue(value) }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- Pagination Links -->
      <div v-if="logs.data.length > 1" class="mt-6 flex justify-center space-x-2">
        <button :disabled="!logs.prev_page_url" @click="fetchLogs(logs.prev_page_url)" class="px-3 py-1 border rounded disabled:opacity-50">
          Previous
        </button>

        <span class="px-3 py-1 border rounded bg-gray-200 font-semibold">Page {{ logs.current_page }} of {{ logs.last_page }}</span>

        <button :disabled="!logs.next_page_url" @click="fetchLogs(logs.next_page_url)" class="px-3 py-1 border rounded disabled:opacity-50">
          Next
        </button>
      </div>
    </div>

    <div v-else class="bg-red-100 text-red-800 p-8 rounded-lg mb-4">
      <p class="text-3xl font-semibold">You do not have permission to view these logs.</p>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
import {onMounted, ref} from 'vue';
import dayjs from 'dayjs';

export default {
  name: 'AuditTrail',

  setup() {
    const logs = ref({current_page: 1, last_page: 1, next_page_url: null, prev_page_url: null});
    const user = ref(null);
    const canViewAuditTrail = ref(false);


    const fetchLogs = async(url = '/nova-vendor/system-trail') => {
      try {
        const response = await axios.get(url);
        logs.value = response.data.logs;
        user.value = response.data.user;

        window.scrollTo({top: 0, behavior: 'smooth'});
        document.querySelector('.main-system-template')?.scrollTo({top: 0, behavior: 'smooth'});

        return response;
      } catch(error) {
        console.error('Failed to load logs:', error);
        return null;  // or throw error if preferred
      }
    };

    onMounted(async() => {
      const response = await fetchLogs();
      if(response?.data) {
        canViewAuditTrail.value = response.data.canView;
      }
    });

    const capitalizeFirst = (str) => {
      if(!str) return '';
      return str.charAt(0).toUpperCase() + str.slice(1);
    };

    const formatDate = (dateStr) => {
      return dayjs(dateStr).format('DD/MM/YYYY : HH:mm:ss');
    };

    const getClassBasename = (fullClassName) => {
      if(!fullClassName) return '';
      return fullClassName.split('\\').pop();
    };

    const parseChanges = (changes) => {
      if(typeof changes === 'object') return changes;
      try {
        return JSON.parse(changes);
      } catch {
        return {};
      }
    };

    const formatKey = (key) => {
      return key.replace(/_/g, ' ').replace(/\b\w/g, char => char.toUpperCase());
    };

    const formatValue = (value) => {
      if(typeof value === 'boolean') {
        return value ? 'true' : 'false';
      }
      return value;
    };

    const changeTitle = (name) => {
      switch(name) {
        case 'Create':
          return 'Details:';
        case 'Delete':
          return 'Deleted Details:';
        case 'Attach':
          return 'Attached Details:';
        default:
          return 'Changes:';
      }
    };

    const getActionableName = (log) => {
      if(!log || !log.actionable_type) return '';
      const baseName = getClassBasename(log.actionable_type);
      return baseName;
    };

    return {
      logs,
      user,
      canViewAuditTrail,
      fetchLogs,
      capitalizeFirst,
      formatDate,
      getClassBasename,
      parseChanges,
      formatKey,
      formatValue,
      changeTitle,
      getActionableName,
    };
  },
};
</script>

<style scoped>
.size-6 {
  width: 1.5rem;
  height: 1.5rem;
}
</style>
