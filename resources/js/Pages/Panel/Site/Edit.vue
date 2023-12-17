<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('edit_site')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
        <PencilSquareIcon class="h-7 w-7 mx-3"/>

        <h1 class="text-2xl font-semibold">{{ __('edit_site') }}</h1>

      </div>

      <!-- Content -->
      <div v-if="data"
           class="px-2 py-4 my-2 mx-auto md:px-4 bg-white shadow-md overflow-hidden  rounded-lg md:max-w-5xl ">

        <div v-if="isAdmin()" class="border bg-sky-50 border-dashed rounded p-4">
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
            class="lg:grid      lg:grid-cols-3     mt-6 px-2 md:px-4 py-4   ">
          <div class="flex-col self-center  m-2 items-center rounded-lg max-w-xs   mx-auto lg:mx-2   ">
            <ImageUploader mode="edit" v-if="data" :preload="route('storage.sites')+`/${data.id}.jpg`"
                           ref="imageCropper"
                           :for-id="data.id"
                           :label="__('image_jpg')"
                           cropRatio="1.25" id="img"
                           height="10" class="grow"/>
            <InputError class="mt-1" :message="form.errors.img"/>
          </div>
          <div
              class="flex flex-col mx-auto   col-span-2 w-full md:max-w-xl lg:border-s px-2"
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
                <TextInput
                    id="link"
                    type="text"
                    :placeholder="__('link')"
                    classes="  "
                    v-model="form.link"
                    autocomplete="link"
                    :error="form.errors.link"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <LinkIcon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>

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

                <TagInput
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
                      <LinkIcon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>

              </div>

              <div class="    mt-4">

                <PrimaryButton class="w-full  "
                               @click.prevent="isAdmin()? submit():  showDialog('primary',__('will_active_after_review'), __('ok') , submit )"
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
  LinkIcon,
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
import TextEditor from "@/Components/TextEditor.vue";

export default {

  data() {
    return {
      data: null,
      form: useForm({
        id: '',
        img: '',
        lang: null,
        name: null,
        link: null,
        category_id: null,
        tags: '',
        description: '',
        message: '',
        status: this.$page.props.data.status,
      }),
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
    LinkIcon,
    TagInput,
    QuestionMarkCircleIcon,
    Selector,
    Squares2X2Icon,
    PencilSquareIcon,

  },
  created() {
    this.data = this.$page.props.data;

  },
  mounted() {
    // console.log(this.data);
    this.form.name = this.data.name;
    this.form.link = this.data.link;
    this.$refs.categorySelector.selected = this.form.category_id = this.data.category_id;
    this.$refs.tags.set(this.data.tags);
    this.form.description = this.data.description;
    this.form.message = this.data.message;
    this.$refs.langSelector.selected = this.data.lang;
  },
  methods: {
    submit() {
      this.form.id = this.data.id;
      this.form.img = this.$refs.imageCropper.getCroppedData();
      this.form.lang = this.$refs.langSelector.selected;
      this.form.category_id = this.$refs.categorySelector.selected;
      if (this.$refs.editor)
        this.form.message = this.$refs.editor.getData();
      this.form.clearErrors();
      this.form.patch(route('site.update'), {
        preserveScroll: false,
        onFinish: (data) => {
        },
        onSuccess: (data) => {
          this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);
          // this.form.reset();

        },
        onError: () => this.showToast('danger', Object.values(this.form.errors).join("<br/>"))
      });
    }
  },

}
</script>
