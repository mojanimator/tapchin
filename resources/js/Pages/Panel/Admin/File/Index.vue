<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('panel')}}</title>
    </template>


    <template #content>
      <!-- Content header -->
      <div
          class="flex items-center justify-between px-4 py-2 border-b lg:py-4">
        <h1 class="text-2xl font-semibold">{{ __('files') }}</h1>

      </div>

      <!-- Content -->
      <div class="mt-2 min-h-screen mx-2">
        <div v-for="url in $page.props.images" class="  max-w-lg  xs:mx-1  w-full  md:mx-auto py-2   border-b  "
             style=" ">
          <Image classes="rounded object-contain   h-full  "
                 :src="url"></Image>
        </div>
      </div>
    </template>


  </Panel>
</template>

<script>
import Panel from "@/Layouts/Panel.vue";

import {Head, Link} from "@inertiajs/vue3";
import {loadScript} from "vue-plugin-load-script";

import {
  HomeIcon,
  ChevronDownIcon,
  Bars3Icon,
  PlusSmallIcon,
  Bars2Icon,
  NewspaperIcon,
  WindowIcon,
  GlobeAltIcon,
  PencilSquareIcon,
  PhotoIcon,
  FilmIcon,
  MicrophoneIcon,
  MegaphoneIcon,
  LightBulbIcon,
  CurrencyDollarIcon,
  BellAlertIcon,
  Cog6ToothIcon,
  TicketIcon,
  UserIcon,
  BriefcaseIcon,
  IdentificationIcon,
  FolderIcon,
} from "@heroicons/vue/24/outline";
import {inject, watchEffect} from "vue";
import Chart from "@/Components/Chart.vue";
import TomanIcon from "@/Components/TomanIcon.vue";
import PayDialog from "@/Components/PayDialog.vue";
import Image from "@/Components/Image.vue";

export default {
  setup(props) {
    // const weatherData = inject('showToast')
    watchEffect(() => {
      // console.log('new weatherData', weatherData.value)
    })
  },
  data() {
    return {
      open: false,
      isDark: false,
      loading: false,
      isMobileMainMenuOpen: false,
      isMobileSubMenuOpen: false,
      isOn: false,
      user: this.$page.props.auth.user,
      agency: this.$page.props.agency,
      tickets: this.$page.props.tickets,
      messages: this.$page.props.messages,
      projectItems: this.$page.props.projectItems,
      projectItemsTable: this.$page.props.projectItemsTable,
      users: this.$page.props.users,
      items: this.$page.props.items,
      itemTypes: this.$page.props.itemTypes,
      notifications: this.$page.props.notifications,
      queue: this.$page.props.queue,
      adminBalance: this.$page.props.adminBalance,
      cardShadow: 'shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)]',

    }
  },
  // emits: ['showToast'],
  components: {
    PayDialog,
    TomanIcon,
    Chart,
    Panel,
    CurrencyDollarIcon,
    TicketIcon,
    Head,
    Link,
    Cog6ToothIcon,
    UserIcon,
    BriefcaseIcon,
    IdentificationIcon,
    FolderIcon,
    Image,
  },
  mounted() {
    // console.log(this.$emit('showToast'))

    // this.showToast('warning', 'hii');


  },
  methods: {},

}
</script>

