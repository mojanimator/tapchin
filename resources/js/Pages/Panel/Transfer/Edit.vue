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

        <h1 class="text-2xl font-semibold">{{ __('edit_transfer') }}</h1>

      </div>
      <div v-if="$page.props.data" class="flex flex-col space-y-2 p-4 pb-0 max-w-3xl mx-auto">
        <h1 class="mx-2  text-lg text-gray-900 pb-2"> {{ $page.props.data.item_name }}</h1>
        <div class="flex space-x-2  text-sm items-center">
          <span class="text-gray-900 mx-2"> {{ __('status') }}:</span>
          <button
              id="dropdownStatusSetting"
              data-te-dropdown-toggle-ref
              aria-expanded="false"
              data-te-ripple-init
              data-te-ripple-color="light"
              class="  min-w-[5rem]  px-1 cursor-pointer items-center text-center rounded-md py-[.2rem]"
              :class="`bg-${getStatus('transfer',  status).color}-100 hover:bg-${getStatus('transfer',  status).color}-200 text-${getStatus('transfer', status).color}-500`">
            {{ getStatus('transfer', status).name }}
          </button>
          <ul ref="statusMenu" data-te-dropdown-menu-ref
              class="  absolute z-[1000]   m-0 hidden   list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-center text-base shadow-lg [&[data-te-dropdown-show]]:block"
              tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"
              aria-labelledby="dropdownStatusSetting">

            <li v-if="status=='active'  " role="menuitem"
                @click="edit({  'cmnd':'inactive'})"
                class="   cursor-pointer   text-sm   transition-colors hover:bg-gray-100">
              <div class="flex items-center text-danger  px-6 py-2 justify-between ">
                <span class="bg-danger mx-1  animate-pulse px-1 py-1 rounded "></span>
                {{ __('inactive') }}
              </div>
              <hr class="border-gray-200 ">
            </li>

            <li v-if="status=='inactive'  " role="menuitem"
                @click="edit({ 'cmnd':'active'})"
                class="   cursor-pointer   text-sm text-primary-700 transition-colors hover:bg-gray-100">
              <div class="flex items-center  px-6 py-2 justify-between ">
                {{ __('activate') }}
              </div>
              <hr class="border-gray-200 ">
            </li>
          </ul>
        </div>
        <div v-if="$page.props.data.status!='done'" class="flex space-x-2  text-sm">
          <span class="text-gray-900 mx-2"> {{ __('expire') }}:</span>
          <span>{{
              $page.props.data.expires_at == null ? __('unlimited') : toShamsi($page.props.data.expires_at, true)
            }}</span>
        </div>
        <div v-if="$page.props.data.status=='done'" class="flex space-x-2  text-sm">
          <span class="text-gray-900 mx-2"> {{ __('transfer_date') }}:</span>
          <span>{{
              toShamsi($page.props.data.done_at, true)
            }}</span>
        </div>
        <div v-if="$page.props.data.status=='done'" class="flex space-x-2  text-sm">
          <span class="text-gray-900 mx-2"> {{ __('sell_price') }}:</span>
          <span>{{
              asPrice($page.props.data.price)
            }} {{ __('currency') }}</span>
        </div>
        <div v-if="$page.props.data.status=='done'" class="flex space-x-2  text-sm">
          <span class="text-gray-900 mx-2"> {{ __('buyer') }}:</span>
          <span>{{
              $page.props.data.buyer
            }}  </span>
        </div>

      </div>


      <!-- Content -->
      <div v-if="$page.props.data.status!='done'" class="px-2  md:px-4 max-w-3xl mx-auto">

        <!--        auctions-->
        <div v-if="auction"
             class="mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">

          <div v-if="auction.length==0"> __('no_auction_suggestion_yet')</div>
          <div v-else>
            <div class="text-lg pb-3">{{ __('suggestions') }}</div>
            <div v-for="(sug,idx) in auction" class="flex flex-col py-2" :class="{'border-b':idx+1<auction.length}">
              <div class="flex items-center justify-around   ">
                <div v-if="sug.owner" class="flex pb-2">
                  <span class="bg-gray-50 rounded-s p-1 px-2 text-gray-500"> {{ sug.owner.fullname }}</span>
                  <span class="bg-gray-100 rounded-e p-1 px-2 text-gray-500">  {{ sug.owner.phone }}</span></div>

                <div class="text-sm text-gray-400">{{ toShamsi(sug.created_at, true) }}</div>
              </div>

              <div class="flex justify-center">
                <span class="bg-green-200 rounded-s p-2 text-green-700">  {{ asPrice(sug.price) }}  {{
                    __('currency')
                  }}</span>
                <span
                    @click="showDialog('danger',__('sure_to_transfer?'),__('yes_transfer'),transfer,{suggestion_id:sug.id,})"
                    class="bg-green-500 rounded-e p-2 px-6 text-white hover:bg-green-400 cursor-pointer">  {{
                    __('sell')
                  }}</span>


              </div>
            </div>
          </div>

        </div>
        <div
            class="      mt-6 px-2 md:px-4 py-4 bg-white shadow-md overflow-hidden  rounded-lg  ">

          <div
              class="flex flex-col mx-2   col-span-2 w-full     px-2"
          >
            <form
                @submit.prevent="$page.props.data.type =='auction' && type != __('auction') && $page.props.data.auction.length>0 ? showDialog('danger',__('auction_suggestions_will_delete'),__('remove'),submit):submit()">

              <div class="flex-col items-center">

                <InputLabel for="typeSelector" :value="__('transfer_type')"/>

                <RadioGroup v-model="type" :beforeSelected="type" ref="typeSelector" class="grow" name="type"
                            :items="$page.props.types.map(e=>__(e))"/>
              </div>
              <div class="my-4">
                <InputLabel class="my-1" for="itemSelector" :value="__('item_for_transfer')"/>

                <ItemSelector v-model:selected="selectedItem" :item="selectedItem">
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
                <InputError class="mt-1" :message="form.errors.item"/>
              </div>

              <div class="my-2">
                <TextInput
                    id="price"
                    type="number"
                    :placeholder="type==__('auction')?__('base_price'):__('sell_price')"
                    classes="  "
                    v-model="form.price"
                    autocomplete="price"
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
                      <ClockIcon class="h-5 w-5"/>
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

let data;

export default {

  data() {
    return {

      form: useForm({
        id: null,
        type: null,
        owner_id: null,
        item_id: null,
        item_type: null,
        price: null,
        expires_at: 0,
        password: null,
        auction: null,
        _method: 'patch',

      }),
      selectedItem: null,
      type: this.__(this.$page.props.types[0]),
      status: null,

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

  },
  created() {
    // this.log(this.$page.props.data);
    data = this.$page.props.data;
    if (data) {
      this.form.id = data.id;
      this.form.price = data.price;
      this.form.expires_at = data.remained_hours;
      this.form.owner_id = data.owner_id;
      this.selectedItem = {owner_id: data.owner_id, id: data.item_id, type: data.item_type, name: data.item_name};
      this.type = this.__(data.type);
      this.auction = data.auction;
      this.status = data.status;

    }
  },
  mounted() {
    // this.log(this.__(data.type))
    // this.$nextTick((e) => {
    // });
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


      this.form.post(route('transfer.update'), {
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

    },
    transfer(params) {
      this.isLoading(true);
      window.axios.post(route('transfer.transfer'), params,
          {
            onUploadProgress: function (axiosProgressEvent) {
            },
            onDownloadProgress: function (axiosProgressEvent) {
            }
          })
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);
              location.reload();
              // this.$inertia.visit('panel.transfer.index');
            }
          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {

            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.isLoading(false);
          });
    },
    edit(params) {
      this.isLoading(true);
      params.id = data.id;
      params.item_type = data.item_type;
      params.item_id = data.item_id;
      window.axios.patch(route('transfer.update'), params,
          {
            onUploadProgress: function (axiosProgressEvent) {

            },

            onDownloadProgress: function (axiosProgressEvent) {

            }
          })
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }

            if (response.data.status) {
              this.status = response.data.status;
            }


          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {


            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.isLoading(false);
          });
    },
  },
  watch: {},
}
</script>
