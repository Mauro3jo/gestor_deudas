<template>
  <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white w-full max-w-md rounded shadow-md p-5" @click.stop>
      <div class="flex justify-between items-center mb-3">
        <h2 class="text-lg font-semibold text-gray-800">{{ title }}</h2>
        <button @click="$emit('close')" class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
      </div>
      <div>
        <slot />
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: String
})
</script>
