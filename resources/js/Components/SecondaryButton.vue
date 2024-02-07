<script setup>
defineProps({
  type: {
    type: String,
    default: 'button',
  }, classes: {
    type: String,
    default: 'button',
  },
});
</script>

<template>
  <button
      :type="type"
      class="    group text-primary-500  z-10">
    <div :class="classes"
         class=" bg-white start-0 top-0 w-full h-full rounded-lg  p-2  duration-300 group-hover:bg-gray-200   ">


      <slot/>
    </div>

  </button>
</template>
