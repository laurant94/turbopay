<script setup>
import { trans } from "matice";
import { ref, watch } from "vue";


const props = defineProps({
  modelValue: {
    type: String,
    default: "", // format attendu : "HH:MM"
  },
  label: {
    type: String,
    default: () => trans('widgets.hours.default_label'),
  },
  required: {
    type: Boolean,
    default: false,
  }
});

const emit = defineEmits(["update:modelValue"]);
const timeValue = ref(props.modelValue);

// 🔁 Synchroniser avec le parent quand ça change
watch(timeValue, (val) => {
  emit("update:modelValue", val);
});

// 🔁 Synchroniser quand le parent change
watch(
  () => props.modelValue,
  (val) => {
    timeValue.value = val;
  }
);
</script>

<template>
    <input
      type="time"
      v-model="timeValue"
      :required="required"
      class="border border-gray-300 dark:border-gray-600 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"
    />
</template>

<style scoped>
input[type="time"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
  filter: invert(40%) sepia(80%) saturate(500%) hue-rotate(190deg);
}
</style>
