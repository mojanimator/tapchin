<template>
  <Draggable v-if="draggable" class="mtl-tree" v-model="treeData" treeLine dir="ltr">
    <template #default="{ node, stat }">

      <OpenIcon
          v-if="stat.children.length"
          :open="stat.open"
          class="mtl-mr"
          @click.native="stat.open = !stat.open"
      />
      <input
          class="mtl-checkbox mtl-mr"
          type="checkbox" @change="node.status=stat.checked?'active':'inactive'"
          v-model="node.checked"
      />
      <span
          @click="params.cmnd='edit'; params.checked=node.checked;params.id=node.id;params.parent_id=node.parent_id;params.name=node.name; modal.show()"
          class="mtl-ml p-1 select-none  cursor-pointer text-gray-500"
          :class="{'font-bold':node.parent_id==null  }">{{ node.name }}</span>
    </template>
  </Draggable>
  <BaseTree v-else class="mtl-tree" v-model="treeData" treeLine dir="ltr">
    <template #default="{ node, stat }">

      <OpenIcon
          v-if="stat.children.length"
          :open="stat.open"
          class="mtl-mr"
          @click.native="stat.open = !stat.open"
      />
      <input
          class="mtl-checkbox mtl-mr"
          type="checkbox" @change="node.status=stat.checked?'active':'inactive'"
          v-model="node.checked"
      />
      <span
          @click="params.cmnd='edit'; params.checked=node.checked;params.id=node.id;params.parent_id=node.parent_id;params.name=node.name; modal.show()"
          class="mtl-ml p-1 select-none cursor-pointer">{{ node.name }}</span>
    </template>
  </BaseTree>
</template>

<script>
import {BaseTree, Draggable, pro, OpenIcon, walkTreeData} from '@he-tree/vue'
import '@he-tree/vue/style/default.css'
import '@he-tree/vue/style/material-design.css'

export default {
  name: "TreeSelector",
  emits: ['update:modelValue'],
  props: ['draggable', 'modelValue'],
  data() {
    return {
      treeData: [],
    }
  }, watch: {
    treeData() {
      this.log(this.treeData);
      this.$emit("update:modelValue", this.treeData);
    },
    modelValue() {
      this.treeData = this.modelValue;
    },

  },
  components: {
    BaseTree,
    Draggable,
    OpenIcon,
  },
  methods: {},
}
</script>

<style scoped>

</style>