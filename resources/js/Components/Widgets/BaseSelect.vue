<script setup>
import { ref, computed, onMounted, onBeforeUnmount, 
    nextTick } from "vue";
import { X } from "lucide-vue-next";
import { trans } from "matice";


const props = defineProps({
  modelValue: {
    type: [Array, String, Number, Object, null],
    default: null,
  },
  options: {
    type: Array,
    required: true,
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  placeholder: {
    type: String,
    default: () => trans('widgets.base_select.default_placeholder'),
  },
  displayMode: {
    type: String,
    default: "simple", // simple | chips | count | placeholder-only
  },
  chipsDeletable: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["update:modelValue"]);

const open = ref(false);
const triggerRef = ref(null);
const dropdownRef = ref(null);
const dropdownTop = ref(false);

const selectedItems = computed(() => {
  if (props.multiple) {
    if (!Array.isArray(props.modelValue)) return [];
    return props.options.filter((o) => props.modelValue.includes(o.value));
  }
  const found = props.options.find((o) => o.value === props.modelValue);
  return found ? [found] : [];
});

const selectedLabels = computed(() =>
  selectedItems.value.map((i) => i.label)
);

const toggleOpen = async () => {
  open.value = !open.value;
  if (open.value) {
    await nextTick();
    adjustDropdownPosition();
  }
};

const adjustDropdownPosition = () => {
  const trigger = triggerRef.value;
  if (!trigger) return;

  const rect = trigger.getBoundingClientRect();
  const viewportHeight = window.innerHeight;

  dropdownTop.value = rect.bottom + 300 > viewportHeight;
};

const selectOption = (value) => {
  if (props.multiple) {
    const arr = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    const exists = arr.includes(value);

    if (exists) {
      emit("update:modelValue", arr.filter((v) => v !== value));
    } else {
      emit("update:modelValue", [...arr, value]);
    }
  } else {
    emit("update:modelValue", value);
    open.value = false;
  }
};

const removeChip = (value) => {
  if (props.multiple) {
    const arr = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
    emit("update:modelValue", arr.filter((v) => v !== value));
  } else {
    emit("update:modelValue", null);
  }
};

const handleClickOutside = (e) => {
  if (
    triggerRef.value &&
    !triggerRef.value.contains(e.target) &&
    dropdownRef.value &&
    !dropdownRef.value.contains(e.target)
  ) {
    open.value = false;
  }
};

onMounted(() => document.addEventListener("click", handleClickOutside));
onBeforeUnmount(() =>
  document.removeEventListener("click", handleClickOutside)
);
</script>

<template>
  <div class="relative w-full">
    <!-- Trigger -->
    <div
      ref="triggerRef"
      @click="toggleOpen"
      class="cursor-pointer w-full px-3 py-2 bg-white border border-gray-300 rounded-lg shadow-sm flex items-center justify-between hover:border-gray-400"
    >
      <span
        v-if="selectedItems.length === 0 || displayMode === 'placeholder-only'"
        class="text-gray-400"
      >
        {{ placeholder }}
      </span>

      <div v-else class="flex items-center flex-wrap gap-1">
        <!-- SIMPLE -->
        <template v-if="displayMode === 'simple'">
          <span class="text-gray-700">
            <template v-if="multiple">{{ selectedLabels.join(', ') }}</template>
            <template v-else>{{ selectedLabels[0] }}</template>
          </span>
        </template>

        <!-- CHIPS -->
        <template v-if="displayMode === 'chips'">
          <div
            v-for="item in selectedItems"
            :key="item.value"
            class="flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-md text-sm"
            @click.stop
          >
            <span>{{ item.label }}</span>

            <button
              v-if="chipsDeletable"
              class="text-blue-600 hover:text-red-600 p-1"
              @click.stop="removeChip(item.value)"
            >
              <x size="12" />
            </button>
          </div>
        </template>

        <!-- COUNT -->
        <template v-if="displayMode === 'count'">
          <span class="text-gray-700">
            <template v-if="multiple">
              {{ $t('widgets.base_select.items_selected', selectedItems.length) }}
            </template>
            <template v-else>{{ selectedLabels[0] }}</template>
          </span>
        </template>
      </div>

      <svg
        class="w-4 h-4 ml-2 text-gray-500"
        :class="{ 'rotate-180': open }"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <!-- DROPDOWN -->
    <div
      v-if="open"
      ref="dropdownRef"
      class="absolute left-0 w-full bg-white border border-gray-300 rounded-lg shadow-lg z-50 mt-1"
      :style="dropdownTop ? 'bottom: calc(100% + 4px)' : 'top: calc(100% + 4px)'"
    >
      <ul class="max-h-60 overflow-auto py-2">
        <li
          v-for="opt in options"
          :key="opt.value"
          class="px-3 py-2 hover:bg-gray-100 cursor-pointer flex items-center gap-2"
          @click.stop="selectOption(opt.value)"
        >
          <template v-if="multiple">
            <input
              type="checkbox"
              class="form-checkbox"
              :checked="modelValue?.includes(opt.value)"
            />
          </template>

          <span>{{ opt.label }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>
