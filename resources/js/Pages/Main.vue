<template>
  <Scaffold>
    <template v-slot:header>
      <title>{{__('home')}}</title>

    </template>

    <div
        class="relative  bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-500 to-lime-600">
      <!--Hero-->
      <div
          class="opacity-70   bg-no-repeat  bg-cover  blur-sm   z-0 absolute  bottom-2     w-full   h-full      "
          :style="`background-image: url(${heroImage})`"></div>


      <div class="py-24 md:py-32 xl:py-32   z-10    mx-auto    ">
        <div class="justify-center  px-3  sm:px-1  flex   flex-col md:flex-row items-center">
          <!--                    Right Col-->
          <div v-if="false" class=" z-10 md:w-2/5 py-6 text-center">

            <!--            <h1 class="my-4 text-white text-5xl font-bold    ">-->
            <!--              {{ __('app_name') }}-->
            <!--            </h1>-->
          </div>
          <!--Left Col-->
          <div
              class="flex flex-col max-w-lg text-white w-full    justify-center   text-center  ">
            <p class="leading-normal text-2xl mb-8 z-10 animate-pulse delay-300">
              {{ heroText }}
            </p>
            <!--                        search-->
            <div v-if="false" class="w-full mx-auto mt-2  ">
              <div class="relative  px-6 mx-auto  ">
                <div
                    class="absolute top-0 bottom-0 start-0 flex items-center opacity-60  ps-10  ">
                  <svg
                      class="w-4 h-4 text-gray-600 fill-current   "
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20">
                    <path
                        d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                  </svg>
                  <span class="absolute border-gray-300  border-s top-0 bottom-0 my-2 ms-6"></span>
                </div>
                <input id="search-toggle" type="search" :placeholder="__('hero_search_placeholder')"
                       class="placeholder-gray-400 border-transparent block w-full py-3 ps-12 pe-4 font-bold text-gray-700 bg-gray-100 rounded-lg shadow-lg focus:outline-none focus:bg-white"
                       onkeyup="updateSearchResults(this.value);">

              </div>


            </div>
            <div class=" z-10 p-3    flex  items-center">

              <SecondaryButton @click="params.type='order';recaptchaExpired() "
                               data-te-toggle="modal"
                               data-te-target="#messageModal"
                               data-te-ripple-init class="mx-2 p-2 grow">{{
                  __('reg_order')
                }}
              </SecondaryButton>
              <PrimaryButton @click="params.type='referral';recaptchaExpired() "
                             data-te-toggle="modal"
                             data-te-target="#messageModal"
                             data-te-ripple-init class="mx-2 p-2 grow">{{
                  __('referral')
                }}
              </PrimaryButton>
            </div>
          </div>


        </div>
      </div>

      <!--wave-->
      <div class="absolute  bottom-0 start-0 end-0">
        <svg viewBox="0 0 1428 174" version="1.1" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink">
          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
            <g transform="translate(-2.000000, 44.000000)" fill="#FFFFFF" fill-rule="nonzero">
              <path
                  d="M0,0 C90.7283404,0.927527913 147.912752,27.187927 291.910178,59.9119003 C387.908462,81.7278826 543.605069,89.334785 759,82.7326078 C469.336065,156.254352 216.336065,153.6679 0,74.9732496"
                  opacity="0.100000001"></path>
              <path
                  d="M100,104.708498 C277.413333,72.2345949 426.147877,52.5246657 546.203633,45.5787101 C666.259389,38.6327546 810.524845,41.7979068 979,55.0741668 C931.069965,56.122511 810.303266,74.8455141 616.699903,111.243176 C423.096539,147.640838 250.863238,145.462612 100,104.708498 Z"
                  opacity="0.100000001"
              ></path>
              <path
                  d="M1046,51.6521276 C1130.83045,29.328812 1279.08318,17.607883 1439,40.1656806 L1439,120 C1271.17211,77.9435312 1140.17211,55.1609071 1046,51.6521276 Z"
                  id="Path-4" opacity="0.200000003"></path>
            </g>
            <g transform="translate(-4.000000, 76.000000)" fill="#FFFFFF" fill-rule="nonzero">
              <path
                  d="M0.457,34.035 C57.086,53.198 98.208,65.809 123.822,71.865 C181.454,85.495 234.295,90.29 272.033,93.459 C311.355,96.759 396.635,95.801 461.025,91.663 C486.76,90.01 518.727,86.372 556.926,80.752 C595.747,74.596 622.372,70.008 636.799,66.991 C663.913,61.324 712.501,49.503 727.605,46.128 C780.47,34.317 818.839,22.532 856.324,15.904 C922.689,4.169 955.676,2.522 1011.185,0.432 C1060.705,1.477 1097.39,3.129 1121.236,5.387 C1161.703,9.219 1208.621,17.821 1235.4,22.304 C1285.855,30.748 1354.351,47.432 1440.886,72.354 L1441.191,104.352 L1.121,104.031 L0.457,34.035 Z"
              ></path>
            </g>
          </g>
        </svg>
      </div>


    </div>
    <div class="container  mx-auto space-y-4 relative">
      <div v-if="$page.props.slides.length>0"
           class="   shadow-md bg-white rounded-lg  overflow-hidden h-72 lg:h-72 xl:h-[24rem] w-full md:w-[90%] lg:w-[80%] md:mx-auto  start-0 end-0 md:-mt-32 -mt-16 ">
        <Slider :items="$page.props.slides" :delay="8000"></Slider>
      </div>

      <Transition name="fade" mode="out-in">
        <section class="flex flex-col   justify-center   text-center ">
          <h1 class="slide-in-bottom-h1 my-4 text-primary-500 mx-auto w-fit text-3xl font-bold border-primary       ">
            {{ $page.props.section1Header }}
            <div
                class="h-1 mx-auto bg-gradient-to-l from-primary-100 via-primary-500 to-primary-600 w-64 opacity-25 my-0 py-0 rounded-t"></div>

          </h1>
          <div v-if="false" class="  text-primary-500  slide-in-bottom-subtitle ">{{ __('how_help_you_revenue') }}</div>

          <div class="m-4 mt-8 gap-4 grid md:grid-cols-2 lg:grid-cols-4 bounce-top-icons">

            <div v-for="(item,idx) in section1"
                 class="relative flex gap-4 bg-gradient-to-l   shadow-md rounded-lg p-4 py-8 items-stretch">
              <component v-bind:is="item.icon" class="w-12 h-12 lg:w-24 lg:h-24 text-primary-500"></component>
              <div class="flex flex-col justify-start py-4 space-y-2 items-start  ">
                <div class="text-sm  text-primary-900 font-bold">{{ __(item.header) }}</div>
                <p class="text-sm text-start text-primary-900">{{ __(item.sub) }}</p>
              </div>
              <!--wave-->
              <svg class="absolute z-[-1] opacity-30 bottom-0 w-full start-0 wave-top" viewBox="0 0 1439 147"
                   version="1.1"
                   xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                  <g transform="translate(0.000000, 50.000000)" class="fill-primary-300" fill="" fill-rule="nonzero">
                    <path
                        d="M 0.457 34.035 C 326 14 442 -46 712 -14 C 953 7 1065 80 1442 12 L 1441.191 104.352 L 1.121 104.031 L 0.457 34.035 Z"></path>
                  </g>
                </g>
              </svg>

            </div>


          </div>
        </section>
      </Transition>
      <Transition name="fade" mode="out-in">
        <section class="flex flex-col   justify-center   text-center ">
          <h1 class="slide-in-bottom-h1 my-4 text-primary-500 mx-auto w-fit text-3xl font-bold border-primary       ">
            {{ $page.props.section2Header }}
            <div
                class="h-1 mx-auto bg-gradient-to-l from-primary-100 via-primary-500 to-primary-600 w-64 opacity-25 my-0 py-0 rounded-t"></div>

          </h1>
          <div v-if="false" class="  text-primary-500  slide-in-bottom-subtitle ">{{ __('how_help_you_revenue') }}</div>

          <div class="m-4 mt-8 gap-4 grid md:grid-cols-2 lg:grid-cols-4 bounce-top-icons">

            <div v-for="(item,idx) in section2"
                 class="relative flex gap-4 bg-gradient-to-l   shadow-md rounded-lg p-4 py-8 items-stretch">
              <!--              <component v-bind:is="item.icon" class="w-12 h-12 lg:w-24 lg:h-24 text-primary-500"></component>-->
              <div class="flex flex-col py-4  justify-start space-y-2    ">
                <div class="text-sm  text-primary-900 font-bold">{{ __(item.header) }}</div>
                <p class="text-sm text-start text-primary-900">{{ __(item.sub) }}</p>
              </div>
              <!--wave-->
              <svg class="absolute z-[-1] opacity-30 bottom-0 w-full start-0 wave-top" viewBox="0 0 1439 147"
                   version="1.1"
                   xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">

                  <g transform="translate(0.000000, 50.000000)" class="fill-primary-300" fill="" fill-rule="nonzero">
                    <path
                        d="M 0.457 34.035 C 326 14 442 -46 712 -14 C 953 7 1065 80 1442 12 L 1441.191 104.352 L 1.121 104.031 L 0.457 34.035 Z"></path>
                  </g>
                </g>
              </svg>

            </div>


          </div>
        </section>
      </Transition>

      <div
          class=" flex flex-col justify-center   rounded-lg  overflow-hidden h-72 lg:h-72    w-fit mx-auto     ">
        <Link :href="route('article.index')" class="text-lg text-primary-500 font-bold p-2 hover:text-primary-400">
          {{ __('articles') }}
        </Link>
        <ArticleSlider :items="$page.props.articles" :delay="8000"></ArticleSlider>
      </div>
    </div>
    <!-- Modal -->
    <div
        data-te-modal-init
        class="fixed left-0 top-0 backdrop-blur z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
        id="messageModal"
        tabindex="-1"
        aria-labelledby="messageModalLabel"
        aria-hidden="true">
      <div
          data-te-modal-dialog-ref
          class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 px-2 sm:px-4 md:px8 min-[576px]:max-w-5xl">
        <div
            class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none">
          <div
              class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4">
            <!--Modal title-->
            <h5
                class="text-xl font-medium leading-normal text-neutral-800"
                id="messageModalLabel">

            </h5>
            <!--Close button-->
            <button
                :class="`text-danger`"
                type="button"
                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                data-te-modal-dismiss
                aria-label="Close">
              <svg
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                  stroke-width="1.5"
                  stroke="currentColor"
                  class="h-6 w-6">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!--Modal body-->
          <div class="relative flex-auto p-4" data-te-modal-body-ref>
            <div
                class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
              <FolderPlusIcon class="h-7 w-7 mx-3"/>

              <h1 class="text-2xl font-semibold">{{ __(params.type) }}</h1>

            </div>


            <div class="px-2  md:px-4">

              <div
                  class="    mx-auto md:max-w-3xl   mt-6 px-2 md:px-4 py-4   overflow-hidden  rounded-lg  ">


                <div
                    class="flex flex-col mx-2   col-span-2 w-full     px-2"
                >

                  <form @submit.prevent="addRefOrder">
                    <input type="hidden" name="_token" :value="csrf()">


                    <div class="my-2">
                      <TextInput
                          id="fullname"
                          type="text"
                          :placeholder="__('fullname')"
                          classes="  "
                          v-model="params.fullname"
                          autocomplete="key"
                          :error="params.errors.fullname"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <Bars2Icon class="h-5 w-5"/>
                          </div>
                        </template>

                      </TextInput>
                    </div>
                    <div class="my-2">
                      <TextInput
                          id="phone"
                          type="text"
                          :placeholder="__('phone')"
                          classes="  "
                          v-model="params.phone"
                          autocomplete="key"
                          :error="params.errors.phone"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <PhoneIcon class="h-5 w-5"/>
                          </div>
                        </template>

                      </TextInput>
                    </div>
                    <div class="my-2">
                      <TextInput
                          :multiline="true"
                          id="description"
                          type="text"
                          :placeholder="__('description')"
                          classes="  "
                          v-model="params.description"
                          autocomplete="description"
                          :error="params.errors.description"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <ChatBubbleBottomCenterTextIcon class="h-5 w-5"/>
                          </div>
                        </template>

                      </TextInput>

                    </div>

                    <div class="my2">

                      <vue-recaptcha v-show="showRecaptcha" :sitekey="siteKey"
                                     size="normal" class="w-full"
                                     theme="light"
                                     hl="fa"
                                     :loading-timeout="loadingTimeout"
                                     @verify="recaptchaVerified"
                                     @expire="recaptchaExpired"
                                     @fail="recaptchaFailed"
                                     @error="recaptchaError"
                                     ref="vueRecaptcha">
                      </vue-recaptcha>
                      <InputError class="mt-1" :message="params.errors.recaptcha"/>
                    </div>
                    <div v-if="loading" class="shadow w-full bg-grey-light m-2   bg-gray-200 rounded-full">
                      <div
                          class=" bg-primary rounded  text-xs leading-none py-[.1rem] text-center text-white duration-300 "
                          :class="{' animate-pulse': loading}"
                          :style="`width: 100%`">
                      </div>
                    </div>

                    <div class="    mt-4">

                      <PrimaryButton class="w-full  "
                                     :class="{ 'opacity-25': loading}"
                                     :disabled="loading">
                        <LoadingIcon class="w-4 h-4 mx-3 " v-if="  loading"/>
                        <span class=" text-lg  ">  {{ __('register_info') }}</span>
                      </PrimaryButton>

                    </div>

                  </form>
                </div>


              </div>
            </div>
          </div>


        </div>
      </div>
    </div>
  </Scaffold>

</template>

<script>
import LoadingIcon from "@/Components/LoadingIcon.vue";
import TextInput from "@/Components/TextInput.vue";
import Scaffold from "@/Layouts/Scaffold.vue";
import {Head, Link} from '@inertiajs/vue3';
import heroImage from '@/../images/hero.jpg';
import {loadScript} from "vue-plugin-load-script";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import Slider from "@/Components/Slider.vue";
import ArticleSlider from "@/Components/ArticleSlider.vue";
import vueRecaptcha from 'vue3-recaptcha2';
import {
  HomeModernIcon,
  RocketLaunchIcon,
  UsersIcon,
  WrenchScrewdriverIcon,
  PencilIcon,
  GlobeAltIcon,
  MicrophoneIcon,
  PlayIcon,
  PhotoIcon,
  FolderPlusIcon,
  Bars2Icon,
  StarIcon,


} from "@heroicons/vue/24/solid";
import {

  PhoneIcon,
  ChatBubbleBottomCenterTextIcon,

} from "@heroicons/vue/24/outline";
import {Modal} from "tw-elements";

export default {
  data() {
    return {
      siteKey: import.meta.env.VITE_RECAPTCHA_SITE_KEY,
      showRecaptcha: true,
      loadingTimeout: 30000, // 30 seconds
      params: {errors: {}, fullname: null, phone: null, description: null, recaptcha: null},
      loading: false,
      heroImage,
      section1: this.$page.props.section1,
      section2: this.$page.props.section2,

      counts: this.$page.props.counts,
    }
  },
  props: ['heroText'],
  components: {
    SecondaryButton, PrimaryButton, Scaffold, Head,
    HomeModernIcon,
    RocketLaunchIcon,
    UsersIcon,
    WrenchScrewdriverIcon,
    PencilIcon,
    GlobeAltIcon,
    MicrophoneIcon,
    PlayIcon,
    PhotoIcon,
    Slider,
    FolderPlusIcon,
    Bars2Icon,
    TextInput,
    LoadingIcon,
    vueRecaptcha,
    PhoneIcon,
    ChatBubbleBottomCenterTextIcon,
    InputError,
    StarIcon,
    ArticleSlider,
    Link,
  },
  // mixins: [Mixin],
  setup(props) {

  },
  mounted() {
    // loadScript("https://d1w019qw3bn26k.cloudfront.net/assets/application-b7a4037d87fe8e15e2a75db9f136291a1fd5cea2fa810311c7c1bb44ecb6c069.js");
    // loadScript("https://www.googletagmanager.com/gtag/js?id=G-P9M5S0890F");
    // loadScript("https://unpkg.com/@alpinejs/ui@3.10.5-beta.7/dist/cdn.min.js");
    // loadScript("https://unpkg.com/@alpinejs/focus@3.10.5/dist/cdn.min.js");
    // loadScript("https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js");
    // loadScript("https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js");
    // loadScript("https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js");

    const countupEls = document.querySelectorAll('.countup');
    countupEls.forEach(this.animateCountUp);

    const modalEl = document.getElementById('messageModal');
    this.modal = new Modal(modalEl);
  },
  methods: {
    addRefOrder() {
      this.loading = true;
      this.params.phone = f2e(this.params.phone);
      window.axios.post(route('message.create'), this.params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.modal.hide();
              this.showToast('success', response.data.message);
              this.params = {errors: {}, fullname: null, phone: null, description: null, recaptcha: null};
              this.recaptchaExpired();

            }


          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {
              // this.log(error.response)
              this.params.errors = error.response.data.errors;

            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.loading = false;
          });
    },
    recaptchaVerified(response) {
      this.params.recaptcha = response;
    },
    recaptchaExpired() {
      this.$refs.vueRecaptcha.reset();
    },
    recaptchaFailed() {
    },
    recaptchaError(reason) {
    },
    animateCountUp(el) {
      // How long you want the animation to take, in ms
      const animationDuration = 1000 + Math.random(3000);
// Calculate how long each ‘frame’ should last if we want to update the animation 60 times per second
      const frameDuration = 1000 / 60;
// Use that to calculate how many frames we need to complete the animation
      const totalFrames = Math.round(animationDuration / frameDuration);
// An ease-out function that slows the count as it progresses
      const easeOutQuad = t => t * (2 - t);


      let frame = 0;
      const countTo = parseInt(el.innerHTML, 10);
      // Start the animation running 60 times per second
      const counter = setInterval(() => {
        frame++;
        // Calculate our progress as a value between 0 and 1
        // Pass that value to our easing function to get our
        // progress on a curve
        const progress = easeOutQuad(frame / totalFrames);
        // Use the progress value to calculate the current count
        const currentCount = Math.round(countTo * progress);

        // If the current count has changed, update the element
        if (parseInt(el.innerHTML, 10) !== currentCount) {
          el.innerHTML = currentCount;
        }

        // If we’ve reached our last frame, stop the animation
        if (frame === totalFrames) {
          clearInterval(counter);
        }
      }, frameDuration);
    },

  },

}
</script>
<style>
/* ----------------------------------------------
* Generated by Animista
* w: http://animista.net, t: @cssanimista
* ---------------------------------------------- */

.slide-in-bottom {
  -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) both;
  animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) both
}

.slide-in-bottom-h1 {
  -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .5s both;
  animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .5s both
}

.slide-in-bottom-subtitle {
  -webkit-animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .75s both;
  animation: slide-in-bottom .5s cubic-bezier(.25, .46, .45, .94) .75s both
}

.fade-in {
  -webkit-animation: fade-in 1.2s cubic-bezier(.39, .575, .565, 1.000) 1s both;
  animation: fade-in 1.2s cubic-bezier(.39, .575, .565, 1.000) 1s both
}

.bounce-top-icons {
  -webkit-animation: bounce-top .9s 1s both;
  animation: bounce-top .9s 1s both
}

@-webkit-keyframes slide-in-bottom {
  0% {
    -webkit-transform: translateY(1000px);
    transform: translateY(1000px);
    opacity: 0
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    opacity: 1
  }
}

@keyframes slide-in-bottom {
  0% {
    -webkit-transform: translateY(1000px);
    transform: translateY(1000px);
    opacity: 0
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    opacity: 1
  }
}

@-webkit-keyframes bounce-top {
  0% {
    -webkit-transform: translateY(-45px);
    transform: translateY(-45px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
    opacity: 1
  }
  24% {
    opacity: 1
  }
  40% {
    -webkit-transform: translateY(-24px);
    transform: translateY(-24px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  65% {
    -webkit-transform: translateY(-12px);
    transform: translateY(-12px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  82% {
    -webkit-transform: translateY(-6px);
    transform: translateY(-6px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  93% {
    -webkit-transform: translateY(-4px);
    transform: translateY(-4px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  25%, 55%, 75%, 87% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
    opacity: 1
  }
}

@keyframes bounce-top {
  0% {
    -webkit-transform: translateY(-45px);
    transform: translateY(-45px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in;
    opacity: 1
  }
  24% {
    opacity: 1
  }
  40% {
    -webkit-transform: translateY(-24px);
    transform: translateY(-24px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  65% {
    -webkit-transform: translateY(-12px);
    transform: translateY(-12px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  82% {
    -webkit-transform: translateY(-6px);
    transform: translateY(-6px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  93% {
    -webkit-transform: translateY(-4px);
    transform: translateY(-4px);
    -webkit-animation-timing-function: ease-in;
    animation-timing-function: ease-in
  }
  25%, 55%, 75%, 87% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out
  }
  100% {
    -webkit-transform: translateY(0);
    transform: translateY(0);
    -webkit-animation-timing-function: ease-out;
    animation-timing-function: ease-out;
    opacity: 1
  }
}

@-webkit-keyframes fade-in {
  0% {
    opacity: 0
  }
  100% {
    opacity: 1
  }
}

@keyframes fade-in {
  0% {
    opacity: 0
  }
  100% {
    opacity: 1
  }
}

</style>

