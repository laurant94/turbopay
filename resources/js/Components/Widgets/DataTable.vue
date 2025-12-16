<script setup>
// Props: headers (array of objects [{ key, label, sortable }]), items (array), links (pagination)
const props = defineProps({
  headers: { type: Array, required: true },
  items: { type: Array, required: true },
  links: { type: Array, required: false, default: () => [] }
});
</script>

<template>
  <!-- Desktop Table (md and above) -->
  <div class="hidden md:block overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-gray-50 dark:bg-gray-700/50">
        <tr>
          <th v-for="h in headers" :key="h.key" scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            {{ h.label }}
          </th>
        </tr>
      </thead>

      <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
        <tr v-for="item in items" :key="item.id">
            <td v-for="h in headers" :key="h.key" class="px-6 py-4 whitespace-nowrap text-sm">

                <slot :name="'item.' + h.key" :item="item" :value="item[h.key]">
                    {{ item[h.key] ?? '' }}
                </slot>

            </td>
        </tr>

        <tr v-if="items.length === 0">
          <td :colspan="headers.length" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
            No data found.
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Mobile DataGrid (below md) -->
  <div class="md:hidden space-y-4">
    <div v-for="item in items" :key="item.id"
         class="border dark:border-gray-700 rounded-lg p-4 bg-white dark:bg-gray-800 shadow-sm">

      <div v-for="h in headers" :key="h.key" class="flex justify-between py-1">
        <span class="text-gray-500 dark:text-gray-400 text-sm font-medium">{{ h.label }}</span>
        <span class="text-gray-900 dark:text-gray-100 text-sm font-semibold">
            <slot :name="'item.' + h.key" :item="item" :value="item[h.key]">
                {{ item[h.key] ?? '' }}
            </slot>
        </span>
      </div>
    </div>

    <div v-if="items.length === 0" class="text-center text-gray-500 dark:text-gray-400 py-4">
      No data found.
    </div>
  </div>

  <!-- Pagination -->
  <Pagination v-if="links.length > 0" :links="links" class="mt-6" />
</template>
