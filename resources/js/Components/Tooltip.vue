<template>
<span class="cursor-pointer"
      data-te-toggle="tooltip"
      data-te-placement="top"
      data-te-ripple-init
      data-te-ripple-color="light"
      :title="content"
>

    <slot></slot>

</span>
</template>
<script>

import {initTE, Tooltip} from "tw-elements";


export default {
    data() {
        return {}
    },
    props: ['title', 'content'],
    components: {},
    mounted() {
        // initTE({Tooltip});
    }
}
</script>
