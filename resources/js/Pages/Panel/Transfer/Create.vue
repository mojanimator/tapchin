<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('new_transfer')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
        <FolderPlusIcon class="h-7 w-7 mx-3"/>

        <h1 class="text-2xl font-semibold">{{ __('new_transfer') }}</h1>

      </div>
      <div class="flex flex-col p-4 pb-0 max-w-3xl mx-auto">
        <div class="flex items-center pb-2">
          <QuestionMarkCircleIcon class="text-gray-500 hover:bg-gray-50 w-4 h-4"/>
          <h1 class="mx-2  text-lg text-gray-900"> {{ __('transfer_methods') }}</h1>
        </div>
        <div class="flex space-x-2  text-sm">
          <span class="text-gray-900 mx-2"> {{ __('regular') }}:</span>
          <span>{{ __('transfer_regular_help') }}</span>
        </div>
        <div class="flex space-x-2     text-sm">
          <span class="text-gray-900 mx-2"> {{ __('private') }}:</span>
          <span
              class="text-sm">{{ __('transfer_private_help') }}</span>
        </div>
        <div class="flex space-x-2     text-sm">
          <span class="text-gray-900 mx-2"> {{ __('auction') }}:</span>
          <span
              class="text-sm">{{ __('transfer_auction_help') }}</span>
        </div>
      </div>


      <!-- Content -->
      <div class="px-2  md:px-4 max-w-3xl mx-auto">

        <div
            class="      mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">

          <div
              class="flex flex-col mx-2   col-span-2 w-full     px-2"
          >
            <form @submit.prevent="submit">

              <div class="flex-col items-center">

                <InputLabel for="typeSelector" :value="__('transfer_type')"/>

                <RadioGroup v-model="type" ref="typeSelector" class="grow" name="type"
                            :items="$page.props.types.map(e=>__(e))"/>
              </div>
              <div class="my-4">
                <InputLabel class="my-1" for="itemSelector" :value="__('item_for_transfer')"/>

                <ItemSelector v-model:selected="selectedItem">
                  <template v-slot:selector="props">
                    <div :class="props.selectedText?'py-2':'py-5'"
                         class=" px-4 border rounded hover:bg-gray-100 cursor-pointer flex items-center ">
                      <div class="grow">
                        {{ props.selectedText ?? __('click_for_select_item') }}
                      </div>
                      <div v-if="props.selectedText"
                           class="bg-danger rounded p-2   cursor-pointer text-white hover:bg-danger-400"
                           @click.stop="props.clear()">
                        <XMarkIcon class="w-5 h-5"/>
                      </div>
                    </div>
                  </template>

                </ItemSelector>

              </div>

              <div class="my-2">
                <TextInput
                    id="price"
                    type="number"
                    :placeholder="type==__('auction')?__('base_price'):__('sell_price')"
                    classes="  "
                    v-model="form.price"
                    autocomplete="name"
                    :error="form.errors.price"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <BanknotesIcon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>
              </div>
              <!--              <InputLabel class="mt-4" for="itemSelector" :value="__('expire')+ ' ('+__('hour')+')'"/>-->
              <div class="my-4">
                <!--                <RadioGroup v-model="form.timestamp" ref="timestampSelector" class="grow  " name="timestamp"-->
                <!--                            :items="[__('unlimited'),__('hour'),__('day'), ]"/>-->
                <TextInput
                    id="expires_at"
                    type="number"
                    :placeholder="__('expire_in_hour_zero_unlimited') "
                    classes=" "
                    v-model="form.expires_at"
                    autocomplete="expires_at"
                    :error="form.errors.expires_at"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <ClockIcon class="h-5 w-5"/>
                    </div>
                  </template>
                </TextInput>

              </div>
              <div class="my-4">
                <TextInput v-if="type==__('private')"
                           id="password"
                           type="text"
                           :placeholder="`${__('transfer_password')} ${__('transfer_password_label')}` "
                           classes=" "
                           v-model="form.password"
                           autocomplete="expires_at"
                           :error="form.errors.password"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <KeyIcon class="h-5 w-5"/>
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
  PaintBrushIcon,
  BanknotesIcon,
  ClockIcon,
  KeyIcon,

} from "@heroicons/vue/24/outline";
import {
  QuestionMarkCircleIcon,
  XMarkIcon,
} from "@heroicons/vue/24/solid";
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
import ItemSelector from "@/Components/ItemSelector.vue";


export default {

  data() {
    return {

      form: useForm({
        type: null,
        owner_id: null,
        item_id: null,
        item_type: null,
        price: null,
        expires_at: 0,
        password: null,

      }),
      selectedItem: null,
      type: this.__(this.$page.props.types[0]),

    }
  },
  components: {
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
    ItemSelector,
    PaintBrushIcon,
    XMarkIcon,
    BanknotesIcon,
    ClockIcon,
    KeyIcon,

  },
  mounted() {
    // this.log(this.$page.props)
  },
  methods: {
    submit() {
      if (this.selectedItem) {
        this.form.item_id = this.selectedItem.id;
        this.form.item_type = this.selectedItem.type;
      }
      this.form.type = this.$page.props.types.filter(e => {
        return this.type == this.__(e);
      })[0];
      // this.log(this.form.type);
      // return;
      // this.form.category_id = this.$refs.categorySelector.selected;
      this.form.clearErrors();

      // this.isLoading(true, this.form.progress ? this.form.progress.percentage : null);


      this.form.post(route('transfer.create'), {
        preserveScroll: false,
        onSuccess: (data) => {

          // else {
          this.showAlert(this.$page.props.flash.status, this.$page.props.flash.message);
          //   this.form.reset();
          // }
        },
        onError: () => {

          this.showToast('danger', Object.values(this.form.errors).join("<br/>"));
        },
        onFinish: (data) => {
          // this.isLoading(false,);
        },
      });

    }
  },
  watch: {},
}
</script>
