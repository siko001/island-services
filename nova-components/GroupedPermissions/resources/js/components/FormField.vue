<template>
  <div class="p-8">
    <div class="mb-6 text-xl">
      Premissions
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-start">
      <div v-for="(perms, group) in groupedPermissions" :key="group" class="rounded border overflow-hidden shadow-lg bg-white dark:bg-gray-900">
        <!-- Accordion Header -->
        <button type="button" @click="toggle(group)" class="flex justify-between items-center w-full px-4 py-3 font-bold capitalize text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition focus:outline-none">
          <span>{{ group.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase()) }}</span> <span>{{ isOpen(group) ? '▲' : '▼' }}</span>
        </button>

        <!-- Accordion Body -->
        <transition name="fade">
          <div v-if="isOpen(group)" class="px-4 py-3 bg-white dark:bg-gray-900">
            <div class="flex flex-col gap-2">
              <div v-for="perm in perms" :key="perm.name" class="flex items-center gap-2">
                <input type="checkbox" v-model="value[perm.name]" :disabled="field.readonly" class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-400 accent-blue-500"/>
                <label class="text-sm text-gray-800 dark:text-gray-200"> {{ formatLabel(perm.label) }} </label>
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
      openGroups: []
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
      let selected = []

      if(Array.isArray(this.value)) {
        selected = this.value
      } else
        if(typeof this.value === 'object' && this.value !== null) {
          selected = Object.entries(this.value).filter(([key, checked]) => checked).map(([key]) => key)
        }

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
    }
  }
}
</script>
