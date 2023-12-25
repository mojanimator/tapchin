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
      class="  relative group text-primary-500  ">
    <div :class="classes"
         class="absolute bg-white  start-0 top-0 w-full h-full rounded-lg  -skew-x-12 duration-300 group-hover:bg-gray-200   z-[-1]">

    </div>

    <slot/>
  </button>
</template>
