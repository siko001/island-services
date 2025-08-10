<template>
  <div class="p-8">
    <!-- Global Actions -->
    <div class="flex flex-wrap justify-between items-center mb-6">
      <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">Permissions</h2>
      <div class="space-x-2">
        <button type="button" @click="selectAll" class="px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700 transition">
          Select All
        </button>
        <button type="button" @click="deselectAll" class="px-3 py-1.5 bg-red-600 text-white rounded hover:bg-red-700 transition">
          Deselect All
        </button>
      </div>
    </div>

    <!-- Permissions Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-start">
      <div v-for="(perms, group) in groupedPermissions" :key="group" class="rounded border overflow-hidden shadow-lg bg-white dark:bg-gray-900">
        <!-- Accordion Header -->
        <div class="flex justify-between items-center w-full px-4 py-3 font-bold capitalize text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800">
          <button type="button" @click="toggle(group)" class="flex-1 text-left focus:outline-none hover:text-blue-500">
            {{ formatGroupName(group) }} <span class="ml-2 text-sm" :class="{
                'flash-green': flashColor[group] === 'green',
                'flash-red': flashColor[group] === 'red'
              }">
              ({{ countSelected(perms) }}/{{ perms.length }})
            </span>
          </button>
          <!-- Group actions -->
          <div class="flex space-x-1">
            <button type="button" v-if="countSelected(perms) < perms.length" @click.stop="selectGroup(perms, group)" class="px-2 py-1 bg-green-500 text-white rounded text-xs hover:bg-green-600" title="Select all in group">
              ✓ All
            </button>
            <button type="button" v-if="countSelected(perms) > 0" @click.stop="deselectGroup(perms, group)" class="px-2 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600" title="Deselect all in group">
              ✗ None
            </button>
          </div>
          <span class="ml-2">{{ isOpen(group) ? '▲' : '▼' }}</span>
        </div>

        <!-- Accordion Body -->
        <transition name="fade">
          <div v-if="isOpen(group)" class="px-4 py-3 bg-white dark:bg-gray-900">
            <div class="flex flex-col gap-2">
              <div v-for="perm in perms" :key="perm.name" class="flex items-center gap-2">
                <input type="checkbox" v-model="value[perm.name]" :disabled="field.readonly" class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-400 accent-blue-500" @change="updateFlash(group, perms)"/>
                <label class="text-sm text-gray-800 dark:text-gray-200 cursor-pointer" @click="value[perm.name] = !value[perm.name]"> {{ formatLabel(perm.label) }} </label>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data() {
    return {
      openGroups: [],
      lastCounts: {},
      flashColor: {}
    }
  },

  computed: {
    groupedPermissions() {
      const groups = {}
      for(const perm of this.field.options) {
        let resource = perm.name.split(" ").slice(-1)[0] || "other"
        if(["system", "audit_trail_system", "audit_trail_login"].includes(resource)) {
          resource = "audit_logs"
        }
        if(!groups[resource]) groups[resource] = []
        groups[resource].push(perm)
      }
      return Object.fromEntries(Object.entries(groups).sort())
    }
  },

  methods: {
    setInitialValue() {
      if(Array.isArray(this.field.value)) {
        this.value = this.field.value.reduce((acc, perm) => {
          acc[perm] = true
          return acc
        }, {})
      } else
        if(this.field.value && typeof this.field.value === 'object') {
          this.value = {...this.field.value}
        } else {
          this.value = {}
        }
    },

    fill(formData) {
      const selected = Object.entries(this.value).filter(([_, checked]) => checked).map(([key]) => key)
      formData.append(this.field.attribute, JSON.stringify(selected))
    },

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
    formatLabel(label) {
      return label.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase())
    },
    formatGroupName(group) {
      if(group === 'audit_logs') return 'Audit Logs'
      return group.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase())
    },

    /** Group action helpers **/
    selectGroup(perms, group) {
      perms.forEach(perm => {
        this.value[perm.name] = true
      })
      this.updateFlash(group, perms)
    },
    deselectGroup(perms, group) {
      perms.forEach(perm => {
        this.value[perm.name] = false
      })
      this.updateFlash(group, perms)
    },
    selectAll() {
      Object.values(this.groupedPermissions).flat().forEach(perm => {
        this.value[perm.name] = true
      })
      // flash for all groups
      Object.keys(this.groupedPermissions).forEach(group => {
        this.updateFlash(group, this.groupedPermissions[group])
      })
    },
    deselectAll() {
      Object.values(this.groupedPermissions).flat().forEach(perm => {
        this.value[perm.name] = false
      })
      Object.keys(this.groupedPermissions).forEach(group => {
        this.updateFlash(group, this.groupedPermissions[group])
      })
    },
    countSelected(perms) {
      return perms.filter(perm => this.value[perm.name]).length
    },

    /** Flash logic **/
    updateFlash(group, perms) {
      const newCount = this.countSelected(perms)
      const oldCount = this.lastCounts[group] ?? 0
      if(newCount > oldCount) {
        this.flashColor[group] = 'green'
      } else
        if(newCount < oldCount) {
          this.flashColor[group] = 'red'
        }
      this.lastCounts[group] = newCount
      setTimeout(() => {
        this.flashColor[group] = null
      }, 1000)
    }
  },

  watch: {
    value: {
      deep: true,
      handler(val) {
        const selected = Object.entries(val).filter(([_, checked]) => checked).map(([key]) => key)
        this.$emit('input', selected)
      }
    }
  }
}
</script>

<style>
.flash-green {
  color: #16a34a; /* green-600 */
}
.flash-red {
  color: #dc2626; /* red-600 */
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
