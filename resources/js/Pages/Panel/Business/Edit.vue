<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('edit_business')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
        <PencilSquareIcon class="h-7 w-7 mx-3"/>

        <h1 class="text-2xl font-semibold">{{ __('edit_business') }}</h1>

      </div>

      <!-- Content -->
      <div v-if="$page.props.data" class="px-2  md:px-4">

        <div
            class="lg:grid   gap-2    lg:grid-cols-3  mx-auto md:max-w-5xl   mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">

          <div v-if="isAdmin()" class="border bg-sky-50 border-dashed rounded p-4 col-span-3">
            <p class="border-b text-md w-fit flex mx-auto mb-2 text-primary font-bold ">{{ __('admin_section') }}</p>
            <div class="flex items-center">

              <RadioGroup :beforeSelected="form.status" ref="statusSelector" class="grow" name="status"
                          v-model="form.status"
                          :items="myMap($page.props.statuses,(e)=>e.name)"/>
            </div>
            <div v-show="form.status=='reject'">

              <InputLabel class="text-red-500" for="editor" :value="__('reject_message')"/>
              <TextEditor mode="create"
                          :preload="form.message"
                          :lang="$page.props.locale"
                          :id="`editor`"
                          :ref="`editor`"/>
            </div>
          </div>
          <div v-else-if="!isAdmin() && form.message" v-html="form.message"
               class="border text-sm text-rose-400 bg-rose-50 border-dashed rounded p-4">
          </div>

          <div
              class="lg:flex-col  flex flex-wrap    md:m-2  lg:mx-2 items-stretch rounded-lg    ">
            <InputLabel class="m-2 w-full md:text-start lg:text-center"
                        :value="__('business_images_max_%s_item').replace('%s',$page.props.max_images_limit)"/>

            <div v-for="(data,idx) in $page.props.max_images_limit"
                 class="m-1 md:max-w-[150px] lg:max-w-xs  ">
              <ImageUploader mode="edit"
                             :link="route('business.update')"
                             :preload="$page.props.data.images[idx]" ref="imageCropper"
                             :label="__('image_jpg')" :for-id="$page.props.data.id"
                             cropRatio="1.25" :id="'img-'+idx"
                             class="  "/>
              <InputError class="mt-1 text-xs" :message="form.errors.images ? form.errors.images.idx:null "/>
            </div>

          </div>
          <div
              class="flex flex-col mx-2   col-span-2 w-full   lg:border-s px-2"
          >
            <form @submit.prevent="submit">

              <div class="flex items-center">
                <Tooltip class="p-2 " :content="__('help_lang')">
                  <QuestionMarkCircleIcon class="text-gray-500 hover:bg-gray-50 w-4 h-4"/>
                </Tooltip>
                <RadioGroup ref="langSelector" class="grow" name="lang" :items="$page.props.langs"/>
              </div>
              <div class="my-2">
                <TextInput
                    id="name"
                    type="text"
                    :placeholder="__('title')"
                    classes="  "
                    v-model="form.name"
                    autocomplete="name"
                    :error="form.errors.name"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <Bars2Icon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>

              </div>

              <div class="my-2">
                <PhoneFields
                    v-model:phone="form.phone"
                    v-model:phone-verify="form.phone_verify"
                    :phone-error="form.errors.phone"
                    for="businesses"
                    :phone-verify-error="form.errors.phone_verify"
                />
              </div>
              <div class="my-2">
                <ProvinceCounty
                    ref="provinceCounty"
                    v-model:province-data="form.province_id"
                    v-model:county-data="form.county_id"
                    :provinces-data="$page.props.provinces"
                    :counties-data="$page.props.counties"
                    :provinceError="form.errors.province_id"
                    :countyError="form.errors.county_id"
                />

              </div>

              <div class="my-2">
                <Selector ref="categorySelector" :data="$page.props.categories" :label="__('category')"
                          id="category_id" v-model="form.category_id">
                  <template v-slot:append>
                    <div class="  p-3">
                      <Squares2X2Icon class="h-5 w-5"/>
                    </div>
                  </template>
                </Selector>
              </div>
              <div class="my-2">
                <SocialFields
                    ref="socials"
                    classes="  "
                    v-model="form.socials"
                    :error="form.errors.socials"
                />


              </div>
              <div class="my-2">
                <TagInput
                    id="tags"
                    ref="tags"
                    :placeholder="__('tags')"
                    classes="  "
                    v-model="form.tags"
                    autocomplete="tags"
                    :error="form.errors.tags"
                >
                </TagInput>
              </div>
              <div class="my-2">
                <TextInput
                    :multiline="true"
                    id="description"
                    type="text"
                    :placeholder="__('description')"
                    classes="  "
                    v-model="form.description"
                    autocomplete="description"
                    :error="form.errors.description"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <ChatBubbleBottomCenterTextIcon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>

              </div>
              <div v-if="form.progress" class="shadow w-full bg-grey-light m-2   bg-gray-200 rounded-full">
                <div
                    class=" bg-primary rounded  text-xs leading-none py-[.1rem] text-center text-white duration-300 "
                    :class="{' animate-pulse': form.progress.percentage <100}"
                    :style="`width: ${form.progress.percentage }%`">
                  <span class="animate-bounce">{{ form.progress.percentage }}</span>
                </div>
              </div>
              <div class="    mt-4">

                <PrimaryButton class="w-full  "
                               @click.prevent="isAdmin()? submit():  showDialog('primary',__('will_active_after_review'), __('ok') , submit ) "
                               :class="{ 'opacity-25': form.processing }"
                               :disabled="form.processing">
                  <LoadingIcon class="w-4 h-4 mx-3 " v-if="  form.processing"/>
                  <span class=" text-lg  ">  {{ __('register_info') }}</span>
                </PrimaryButton>

              </div>

            </form>
          </div>


        </div>
      </div>
    </template>


  </Panel>
</template>

<script>
import Scaffold from "@/Layouts/Scaffold.vue";
import Panel from "@/Layouts/Panel.vue";
import {Head, Link, useForm} from "@inertiajs/vue3";
import {
  ChevronDownIcon,
  HomeIcon,
  UserIcon,
  EyeIcon,
  FolderPlusIcon,
  Bars2Icon,
  ChatBubbleBottomCenterTextIcon,
  Squares2X2Icon,
  PencilSquareIcon,

} from "@heroicons/vue/24/outline";
import {QuestionMarkCircleIcon,} from "@heroicons/vue/24/solid";
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import RadioGroup from '@/Components/RadioGroup.vue'
import LoadingIcon from "@/Components/LoadingIcon.vue";
import Popover from "@/Components/Popover.vue";
import Tooltip from "@/Components/Tooltip.vue";
import TagInput from "@/Components/TagInput.vue";
import ImageUploader from "@/Components/ImageUploader.vue";
import Selector from "@/Components/Selector.vue";
import ProvinceCounty from "@/Components/ProvinceCounty.vue";
import PhoneFields from "@/Components/PhoneFields.vue";
import SocialFields from "@/Components/SocialFields.vue";
import TextEditor from "@/Components/TextEditor.vue";

export default {

  data() {
    return {
      data: null,
      form: useForm({
        id: this.$page.props.data.id,
        phone: null,
        phone_verify: null,
        lang: null,
        name: null,
        link: null,
        category_id: null,
        province_id: this.$page.props.data.province_id,
        socials: null,
        county_id: this.$page.props.data.county_id,
        tags: null,
        description: '',
        message: '',
        status: this.$page.props.data.status,
      }),
      images: [],
    }
  },
  components: {
    TextEditor,
    ImageUploader,
    LoadingIcon,
    Head,
    Link,
    HomeIcon,
    ChevronDownIcon,
    Panel,
    InputLabel,
    TextInput,
    InputError,
    PrimaryButton,
    RadioGroup,
    UserIcon,
    EyeIcon,
    Checkbox,
    Popover,
    Tooltip,
    FolderPlusIcon,
    Bars2Icon,
    ChatBubbleBottomCenterTextIcon,
    TagInput,
    QuestionMarkCircleIcon,
    Selector,
    Squares2X2Icon,
    ProvinceCounty,
    PhoneFields,
    SocialFields,
    PencilSquareIcon,

  },
  created() {

  },
  mounted() {
    this.data = this.$page.props.data;
    // console.log(this.data);

    this.form.name = this.data.name;
    this.form.phone = this.data.phone;
    this.form.link = this.data.link;
    this.form.category_id = this.data.category_id;
    this.form.province_id = this.data.province_id;
    this.form.county_id = this.data.county_id;
    this.$refs.socials.set(this.data.socials);
    this.$refs.tags.set(this.data.tags);
    this.form.description = this.data.description;
    this.form.message = this.data.message;

    this.$refs.langSelector.selected = this.data.lang;
  },
  methods: {
    submit() {

      if (this.$refs.editor)
        this.form.message = this.$refs.editor.getData();

      this.form.socials = this.$refs.socials.get();
      this.form.uploading = false;

      this.form.lang = this.$refs.langSelector.selected;
      // this.form.category_id = this.$refs.categorySelector.selected;
      this.form.clearErrors();

      // this.isLoading(true, this.form.progress ? this.form.progress.percentage : null);
      // this.images = [];
      // for (let i = 0; i < this.$page.props.max_images_limit; i++) {
      //   let tmp = this.$refs.imageCropper[i].getCroppedData();
      //   if (tmp) this.images.push(tmp);
      // }
      this.form.patch(route('business.update'), {
        preserveScroll: false,

        onSuccess: (data) => {
          if (this.$page.props.flash.status)
            this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);

        },
        onError: () => {
          this.showToast('danger', Object.values(this.form.errors).join("<br/>"));
        },
        onFinish: (data) => {
          // this.isLoading(false,);
          if (this.$page.props.flash.status)
            this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);
        },
      });
    }
  },

}
</script>
