<template>
  <PanelScaffold>
    <template #header>
      <slot name="header"></slot>
    </template>

    <!--         Sidenav -->
    <template #sidenav>
      <nav id="sidenav-1"
           class="fixed start-0 top-0 z-[1035] h-screen w-60 -translate-x-full overflow-hidden bg-white shadow-[0_4px_12px_0_rgba(0,0,0,0.07),_0_2px_4px_rgba(0,0,0,0.05)] md:data-[te-sidenav-hidden='false']:translate-x-0"
           data-te-sidenav-init
           data-te-sidenav-mode-breakpoint-over="0"
           data-te-sidenav-mode-breakpoint-side="md"
           data-te-sidenav-hidden="false"
           data-te-sidenav-color="dark"
           data-te-sidenav-mode="side"
           data-te-sidenav-right="true"
           data-te-sidenav-content="#toggler"
           data-te-sidenav-scroll-container="#scrollContainer">


        <ul id="scrollContainer" class="relative m-0 list-none    text-primary-500"
            data-te-sidenav-menu-ref>
          <li class="relative">
            <Link v-if="isAdmin()" :href="route('panel.admin.index')"
                  class="pt-2 pb-2 flex  px-3 outline-none transition duration-300 ease-linear hover:bg-slate-200 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                  :class="{' bg-primary-100 text-primary':menuIsActive ( 'panel.admin.index' )}"
            >
              <span class="w-full text-gray-500"> {{ __('admin_dashboard') }}</span>
            </Link>
            <Link v-if="false" :href="route('panel.index')"
                  class="pt-2 pb-2 flex  px-3 outline-none transition duration-300 ease-linear hover:bg-slate-200 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                  :class="{'bg-primary-100  text-primary-500':menuIsActive ( 'panel.index' )}"
            >
              <span class="w-full  "> {{ __('dashboard') }}</span>
            </Link>
            <hr class="border-gray-200 py-2 mx-4">

            <div v-if="false"
                 class="flex text-primary mx-2 justify-center items-center text-sm text-gray-500">

              <Tooltip v-if="!hasWallet()" class="p-2 " :content="__('help_activate_wallet')">
                <QuestionMarkCircleIcon class="text-gray-500 hover:bg-gray-50 w-4 h-4"/>
              </Tooltip>
              <span class="text-gray-700">{{ __('wallet') + ' :' }}</span>

              <div v-if="hasWallet()" class="flex items-center ">
                <strong class="mx-2 text-primary">{{ asPrice(user.wallet) }} </strong>
                <span class="text-xs text-gray-500"> {{ __('currency') }}</span>
                <span @click="showWalletChargeDialog"
                      class="mx-2   text-center  bg-success-200 text-success-700 hover:bg-success-100 cursor-pointer px-2 py-[.1rem] rounded-lg transition-all duration-300">
                   {{ __('charge') }}
                  </span>
              </div>
              <div v-else class="flex">

                <Link :href="route('panel.profile.edit')"
                      class="text-danger-700 bg-danger-200 hover:bg-danger-100 rounded-lg px-2 py-1 cursor-pointer">
                  {{ __('inactive') }}
                </Link>
              </div>

            </div>
            <hr v-if="false && hasWallet()" class="border-gray-200 my-2 mx-4">
            <div v-if="false" class="flex text-primary mx-2 justify-center items-center text-sm text-gray-500">
              <Tooltip class="p-2 " :content="__('help_meta')">
                <QuestionMarkCircleIcon class="text-gray-500 hover:bg-gray-50 w-4 h-4"/>
              </Tooltip>
              <span class="text-gray-700">{{ __('meta') + ' :' }}</span>
              <strong class="mx-2">{{ asPrice(user.meta) }} </strong>
              <span
                  class="mx-2   text-center  bg-success-200 text-success-700 hover:bg-success-100 cursor-pointer px-2 py-[.1rem] rounded-lg transition-all duration-300"> {{
                  __('charge')
                }}</span>
            </div>
            <hr v-if="false" class="border-primary-200 m-2">

          </li>
          <!-- Admin links -->
          <li v-if="isAdmin()" class="relative ">
            <a
                :class="{'bg-primary-50 text-primary-500':menuIsActive ( 'panel.admin.*' ) ||menuIsActive ( 'panel.ticket.*' ) }"
                class="flex   cursor-pointer items-center truncate   px-3 py-4 text-[0.875rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-primary-100 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
                data-te-sidenav-link-ref>
              <WrenchScrewdriverIcon class="w-5 h-5  "/>
              <span class="mx-2 text-sm "> {{ __('admin') }} </span>
              <span
                  class="  right-0 ml-auto mr-[0.8rem] transition-transform duration-300 ease-linear motion-reduce:transition-none [&>svg]:text-gray-600"
                  data-te-sidenav-rotate-icon-ref>
                <ChevronDownIcon class="h-5 w-5"/>
              </span>
            </a>
            <ul
                class="  !visible relative m-0 hidden list-none   data-[te-collapse-show]:block "
                v-bind="{ 'data-te-collapse-show':menuIsActive ( 'panel.admin.*' )?true:null }"
                data-te-collapse-item
                data-te-sidenav-collapse-ref>
              <li class="relative ps-7 ">

                <Link :href="route('panel.admin.setting.index')" role="menuitem"
                      :class="{'bg-primary-50  text-primary-500 border-primary-500':menuIsActive ( 'panel.admin.setting.*' )}"
                      class="flex text-gray-500  border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <Cog6ToothIcon class="w-5 h-5 mx-1"/>
                  {{ __('settings') }}
                </Link>

                <Link :href="route('panel.admin.slider.index')" role="menuitem"
                      :class="{'bg-primary-50 text-primary-500 border-primary-500':menuIsActive ( 'panel.admin.slider.*' )}"
                      class="flex text-gray-500  border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <RectangleStackIcon class="w-5 h-5 mx-1"/>
                  {{ __('slider') }}
                </Link>
                <Link :href="route('panel.admin.message.index')" role="menuitem"
                      :class="{'bg-primary-50 text-primary-500 border-primary-500':menuIsActive ( 'panel.admin.message.*' )}"
                      class="flex  text-gray-500 border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <MegaphoneIcon class="w-5 h-5 mx-1"/>
                  {{ __('messages') }}
                </Link>
                <Link :href="route('panel.admin.article.index')" role="menuitem"
                      :class="{'bg-primary-50 text-primary-500 border-primary-500':menuIsActive ( 'panel.admin.article.*' )}"
                      class="flex  text-gray-500 border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <NewspaperIcon class="w-5 h-5 mx-1"/>
                  {{ __('articles') }}
                </Link>


              </li>

            </ul>
          </li>

          <!-- Article links -->
          <li v-if="false && hasAccess('a')" class="relative ">
            <a :class="{'bg-primary-50 text-primary-500':menuIsActive ( 'panel.article.*' )}"
               class="flex   cursor-pointer items-center truncate   px-3 py-4 text-[0.875rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-primary-100 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
               data-te-sidenav-link-ref>
              <NewspaperIcon class="w-5 h-5  "/>
              <span class="mx-2 text-sm "> {{ __('articles') }} </span>
              <span
                  class="  right-0 ml-auto mr-[0.8rem] transition-transform duration-300 ease-linear motion-reduce:transition-none [&>svg]:text-gray-600"
                  data-te-sidenav-rotate-icon-ref>
                                             <ChevronDownIcon class="h-5 w-5"/>
                                             </span>
            </a>
            <ul
                v-bind="{ 'data-te-collapse-show':menuIsActive ( 'panel.article.*' )?true:null }"
                class="  !visible relative m-0 hidden list-none    data-[te-collapse-show]:block "
                data-te-collapse-item data-te-sidenav-collapse-ref>
              <li class="relative ps-7">

                <Link :href="route('panel.article.index')" role="menuitem"
                      :class="subMenuIsActive( 'panel.article.index' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <Bars2Icon class="w-5 h-5 mx-1"/>
                  {{ __('list') }}
                </Link>
                <Link :href="route('panel.article.create')" role="menuitem"
                      :class="subMenuIsActive ( 'panel.article.create' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <PlusSmallIcon class="w-5 h-5 mx-1"/>
                  {{ __('new') }}
                </Link>
              </li>

            </ul>
          </li>

          <!-- Support links -->
          <li v-if="!isAdmin()" class="relative  ">
            <a :class="{'bg-primary-50 text-primary-500':menuIsActive ( 'panel.ticket.*' )}"
               class="flex   cursor-pointer items-center truncate   px-3 py-4 text-[0.875rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-primary-100 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
               data-te-sidenav-link-ref>
              <LightBulbIcon class="w-5 h-5  "/>
              <span class="mx-2 text-sm "> {{ __('support') }} </span>
              <span
                  class="  right-0 ml-auto mr-[0.8rem] transition-transform duration-300 ease-linear motion-reduce:transition-none [&>svg]:text-gray-600"
                  data-te-sidenav-rotate-icon-ref>
                                             <ChevronDownIcon class="h-5 w-5"/>
                                             </span>
            </a>
            <ul
                v-bind="{ 'data-te-collapse-show':menuIsActive ( 'panel.ticket.*' )?true:null }"
                class="  !visible relative m-0 hidden list-none    data-[te-collapse-show]:block "
                data-te-collapse-item data-te-sidenav-collapse-ref>
              <li class="relative ps-7">

                <Link :href="route('panel.notification.index')" role="menuitem"
                      :class="subMenuIsActive( 'panel.notification.index' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <Bars2Icon class="w-5 h-5 mx-1"/>
                  {{ __('notifications') }}
                </Link>
                <Link :href="route('panel.ticket.index')" role="menuitem"
                      :class="subMenuIsActive( 'panel.ticket.index' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <Bars2Icon class="w-5 h-5 mx-1"/>
                  {{ __('tickets') }}
                </Link>
                <Link :href="route('panel.ticket.create')" role="menuitem"
                      :class="subMenuIsActive ( 'panel.ticket.create' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <PlusSmallIcon class="w-5 h-5 mx-1"/>
                  {{ __('new_ticket') }}
                </Link>
              </li>

            </ul>
          </li>

          <!-- Financial links -->
          <li v-if="false" class="relative  ">
            <a :class="{'bg-primary-50 text-primary-500':menuIsActive ( 'panel.financial.*' )}"
               class="flex   cursor-pointer items-center truncate rounded-[5px] px-3 py-4 text-[0.875rem] text-gray-600 outline-none transition duration-300 ease-linear hover:bg-primary-100 hover:text-inherit hover:outline-none focus:bg-slate-50 focus:text-inherit focus:outline-none active:bg-slate-50 active:text-inherit active:outline-none data-[te-sidenav-state-active]:text-inherit data-[te-sidenav-state-focus]:outline-none motion-reduce:transition-none"
               data-te-sidenav-link-ref>
              <CurrencyDollarIcon class="w-5 h-5  "/>
              <span class="mx-2 text-sm "> {{ __('financial') }} </span>
              <span
                  class="  right-0 ml-auto mr-[0.8rem] transition-transform duration-300 ease-linear motion-reduce:transition-none [&>svg]:text-gray-600"
                  data-te-sidenav-rotate-icon-ref>
                                             <ChevronDownIcon class="h-5 w-5"/>
                                             </span>
            </a>
            <ul
                v-bind="{ 'data-te-collapse-show':menuIsActive ( 'panel.financial.*' )?true:null }"
                class="  !visible relative m-0 hidden list-none    data-[te-collapse-show]:block "
                data-te-collapse-item data-te-sidenav-collapse-ref>
              <li class="relative ps-7">

                <Link :href="route('panel.financial.transaction.index')" role="menuitem"
                      :class="subMenuIsActive( 'panel.financial.transaction.index' )"
                      class="flex   border-s-2 hover:border-primary-500  items-center p-2   text-sm  transition-all duration-200   hover:text-primary-700 hover:bg-primary-50">
                  <Bars2Icon class="w-5 h-5 mx-1"/>
                  {{ __('transactions') }}
                </Link>

              </li>

            </ul>
          </li>

          <li>
            <div class="py-4">

            </div>
          </li>
        </ul>
      </nav>
    </template>

    <template #content>
      <slot name="content"></slot>
    </template>
  </PanelScaffold>
</template>

<script>
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
  ArrowRightOnRectangleIcon,
  WrenchScrewdriverIcon,
  ArrowsRightLeftIcon,
  BriefcaseIcon,
  RectangleStackIcon,
} from "@heroicons/vue/24/outline";
import {
  QuestionMarkCircleIcon
} from "@heroicons/vue/24/solid";
import Image from '@/Components/Image.vue';
import Toast from '@/Components/Toast.vue';
import Tooltip from '@/Components/Tooltip.vue';
import {useRemember} from '@inertiajs/vue3';
import {initTE, Dropdown, Sidenav} from "tw-elements";
import PanelScaffold from "@/Layouts/PanelScaffold.vue";
import {provide, ref} from 'vue';


export default {
  setup() {

    // const weatherData = ref('hi');
    // provide('showToast', weatherData);


  },

  data() {
    return {
      open: false,

      isMobileMainMenuOpen: false,
      isMobileSubMenuOpen: false,
      isOn: false,
      activeTabe: false,
      isNotificationsPanelOpen: false,
      isOpen: {'business': false, 'article': false,},
      user: this.$page.props.auth.user,

    }
  },
  props: [],
  created() {
  },
  mounted() {

    // this.$nextTick(function () {
    //     console.log(this.$parent.toast);
    // });
    // console.log(this.$emit('showToast'))
    // this.$refs.toast.success('hi');
    // loadScript("https://cdn.jsdelivr.net/gh/alpine-collective/alpine-magic-helpers@0.5.x/dist/component.min.js")
    // loadScript("https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.3/dist/alpine.min.js")
  },
  watch: {
    // isOpen: {
    //     handler(val) {
    //         localStorage.setItem("menuStatus", JSON.stringify(val));
    //     },
    //     deep: true,
    // }
  },
  components: {
    PanelScaffold,
    Toast,
    Head,
    Link,
    HomeIcon,
    ChevronDownIcon,
    Bars3Icon,
    Image,
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
    ArrowRightOnRectangleIcon,
    Tooltip,
    QuestionMarkCircleIcon,
    WrenchScrewdriverIcon,
    ArrowsRightLeftIcon,
    BriefcaseIcon,
    RectangleStackIcon,
  },
  methods: {
    delay(time) {
      return new Promise(resolve => setTimeout(resolve, time));
    },

    menuIsActive(link) {
      return this.route().current(`${link}`);
    },
    subMenuIsActive(link) {
      return this.route().current(`${link}`) ? "text-primary-500 border-s border-primary-500  " : "text-gray-500   ";
    },

  },
}
</script>
