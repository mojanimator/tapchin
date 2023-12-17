<template>
  <header :dir="dir()">
    <Navbar :theme="navbarTheme"/>
  </header>
  <main :dir="dir()" class="min-h-screen ">
    <Head>
      <meta name="author" :content="__('app_name')">
      <link rel="shortcut icon" type="image/x-icon" :href="favicon"/>

      <slot name="header"/>
    </head>
    <Alert ref="alert"/>
    <Dialog ref="modal"/>
    <Toast ref="toast"/>
    <!-- Loading screen -->
    <div v-if="loading" class="  fixed w-screen h-screen backdrop-blur-sm     z-[999999]">

      <div class="flex items-center justify-center  w-full h-full  ">
        <div class="flex justify-center items-center space-x-1 text-sm text-gray-700">
          <LoadingIcon type="line-dot" class=" fill-primary"/>

        </div>
      </div>
    </div>
    <slot/>
  </main>
  <!--wave-->
  <svg class="wave-top" viewBox="0 0 1439 147" version="1.1" xmlns="http://www.w3.org/2000/svg"
       xmlns:xlink="http://www.w3.org/1999/xlink">
    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
      <g transform="translate(-1.000000, -14.000000)" fill-rule="nonzero">
        <g class="wave " fill="#fff">
          <path
              d="M1440,84 C1383.555,64.3 1342.555,51.3 1317,45 C1259.5,30.824 1206.707,25.526 1169,22 C1129.711,18.326 1044.426,18.475 980,22 C954.25,23.409 922.25,26.742 884,32 C845.122,37.787 818.455,42.121 804,45 C776.833,50.41 728.136,61.77 713,65 C660.023,76.309 621.544,87.729 584,94 C517.525,105.104 484.525,106.438 429,108 C379.49,106.484 342.823,104.484 319,102 C278.571,97.783 231.737,88.736 205,84 C154.629,75.076 86.296,57.743 0,32 L0,0 L1440,0 L1440,84 Z"></path>
        </g>
        <g transform="translate(1.000000, 15.000000)" fill=""
           class="fill-primary  ">
          <g class=""
             transform="translate(719.500000, 68.500000) rotate(-180.000000) translate(-719.500000, -68.500000) ">
            <path class="fill-primary-500"
                  d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496"
                  opacity="0.100000001"></path>
            <path class="fill-primary-700"
                  d="M100,104.708498 C277.413333,72.2345949 426.147877,52.5246657 546.203633,45.5787101 C666.259389,38.6327546 810.524845,41.7979068 979,55.0741668 C931.069965,56.122511 810.303266,74.8455141 616.699903,111.243176 C423.096539,147.640838 250.863238,145.462612 100,104.708498 Z"
                  opacity="0.100000001"></path>
            <path class="fill-primary-700"
                  d="M1046,51.6521276 C1130.83045,29.328812 1279.08318,17.607883 1439,40.1656806 L1439,120 C1271.17211,77.9435312 1140.17211,55.1609071 1046,51.6521276 Z"
                  opacity="0.200000003"></path>

          </g>

        </g>
      </g>
      <g transform="translate(0.000000, 50.000000)" class="fill-primary-100" fill="" fill-rule="nonzero">
        <path
            d="M 0.457 34.035 C 326 14 442 -46 712 -14 C 953 7 1065 80 1442 12 L 1441.191 104.352 L 1.121 104.031 L 0.457 34.035 Z"></path>
      </g>
    </g>
  </svg>

  <Footer/>

  <!--Footer-->
</template>

<script>
import {Head, Link} from '@inertiajs/vue3';
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import Toast from "@/Components/Toast.vue";
import Dialog from "@/Components/Dialog.vue";
import Alert from "@/Components/Alert.vue";
import LoadingIcon from "@/Components/LoadingIcon.vue";
import mitt from 'mitt'
import favicon from "@/../images/logo.png";

export const emitter = mitt()
export default {
  data() {
    return {
      loading: false,
      favicon: favicon,
    }
  },
  props: ['navbarTheme'],
  components: {
    Head, Link, Navbar, Footer, Alert, Dialog, Toast, LoadingIcon,
  },
  mounted() {
    window.tailwindElements();
    this.emitter.on('showToast', (e) => {
      if (this.$refs.toast)
        this.$refs.toast.show(e.type, e.message);
    });

    this.emitter.on('showAlert', (e) => {
      if (this.$refs.alert)
        this.$refs.alert.show(e.type, e.message);
    });

    this.emitter.on('showDialog', (e) => {
      this.log(e);
      if (this.$refs.modal)
        this.$refs.modal.show(e.type, e.message, e.button, e.action, e.items);
    });

    this.emitter.on('loading', (e) => {
      this.loading = e;
    });
  }
}
</script>
