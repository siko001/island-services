<template>
  <div>
    <div v-if="allList.length === 0" class="italic text-gray-500 grid grid-cols-[1fr_3fr] py-2 items-center">
      <h2 class="text-gray-400">Permissions</h2>
      <p class="text-gray-400">No permissions assigned</p>
    </div>
    <div v-else class="p-4">
      <h3 class="text-lg font-semibold mb-4">Permissions</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 items-start">
        <div v-for="(perms, group) in groupedPermissions" :key="group" class="rounded border overflow-hidden shadow bg-white dark:bg-gray-900">
          <!-- Accordion Header -->
          <button type="button" @click="toggle(group)" class="flex justify-between items-center w-full px-2 py-1.5 font-bold capitalize text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
            <span>{{ formatGroupName(group) }}
              <span class="ml-2 text-sm text-gray-500">
                ({{ countSelected(perms) }}/{{ perms.length }})
              </span>
            </span> <span>{{ isOpen(group) ? '▲' : '▼' }}</span>
          </button>
          <!-- Accordion Body -->
          <transition name="fade">
            <div v-if="isOpen(group)" class="px-4 py-3 bg-white dark:bg-gray-900">
              <ul class="list-disc pl-2 space-y-1">
                <li v-for="perm in perms" :key="perm">
                  {{ perm }}
                </li>
              </ul>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {FieldValue} from 'laravel-nova'

export default {
  mixins: [FieldValue],
  data() {
    return {
      openGroups: []
    }
  },
  computed: {
    allList() {
      let arr = []
      // Handles object, array or comma-separated string
      if(Array.isArray(this.fieldValue)) {
        arr = this.fieldValue
      } else
        if(typeof this.fieldValue === 'string') {
          if(this.fieldValue.includes(',')) {
            arr = this.fieldValue.split(',').map(s => s.trim()).filter(Boolean)
          } else {
            try {
              const parsed = JSON.parse(this.fieldValue)
              if(Array.isArray(parsed)) {
                arr = parsed
              } else
                if(parsed && typeof parsed === 'object') {
                  arr = Object.entries(parsed).filter(([_, v]) => v).map(([k]) => k)
                } else
                  if(this.fieldValue) {
                    arr = [this.fieldValue]
                  }
            } catch {
              arr = this.fieldValue ? [this.fieldValue] : []
            }
          }
        } else
          if(this.fieldValue && typeof this.fieldValue === 'object') {
            arr = Object.entries(this.fieldValue).filter(([_, v]) => v).map(([k]) => k)
          }
      return arr
    },
    groupedPermissions() {
      const groups = {};
      for(const perm of this.allList) {
        // Extract the last word as resource (like before)
        let resource = perm.split(' ').slice(-1)[0] || 'other';

        // Merge both audit types under "audit_trail"
        if(['audit_trail_login', 'audit_trail_system'].includes(resource)) {
          resource = 'audit_trail';
        }

        if(!groups[resource]) groups[resource] = [];
        groups[resource].push(perm);
      }
      return Object.fromEntries(Object.entries(groups).sort());
    }
  },
  methods: {
    toggle(group) {
      if(this.isOpen(group)) {
        this.openGroups = this.openGroups.filter(g => g !== group)
      } else {
        this.openGroups.push(group)
      }
    },
    isOpen(group) {
      return this.openGroups.includes(group)
    },

    countSelected(perms) {
      return perms.length;
    },

    formatGroupName(group) {
      if(group === 'audit_trail') {
        return 'Audit Trails';
      }
      return group.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    },

  }
}
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
