<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('edit_article')}}</title>
    </template>


    <template v-slot:content>
      <div class="max-w-3xl mx-auto">
        <!-- Content header -->
        <div
            class="flex items-center justify-start px-4 py-2 text-primary-500 border-b md:py-4">
          <PencilSquareIcon class="h-7 w-7 mx-3"/>

          <h1 class="text-2xl font-semibold">{{ __('edit_project') }}</h1>

        </div>


        <div class="px-2  md:px-4">

          <div class="flex flex-col space-y-2    p-4 rounded-lg   bg-white shadow-md">
            <div class="">
              <span class="text-primary-700 text-lg font-bold ">{{ data.title }}</span>
            </div>
            <div>

              <div class="flex items-center justify-between">
                <div class="">
                  <div class="">
                    <span class="text-gray-500">{{ __('id') }}:</span>
                    <span class="text-primary-700 font-bold mx-2">#{{ data.id }}</span>
                  </div>
                  <div class="">
                    <span class="text-gray-500">{{ __('status') }}:</span>
                    <span class=" font-bold mx-2"
                          :class="`text-${getStatus('project_statuses', data.status).color}-700`">
                {{ __(data.status) }}
                </span>
                  </div>
                </div>
                <div v-if="isAdmin()" class="flex items-center">
                  <label dir="ltr" v-if="data.article_id" class="mx-1 relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" class="sr-only peer" v-model="data.rewrite">
                    <div
                        class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                    <span class="mx-1 text-sm select-none font-medium text-gray-900">  {{
                        __('rewrite_article')
                      }}</span>
                  </label>

                  <button
                      @click="data.article_id? $inertia.visit(route('panel.article.edit',data.article_id)): showDialog('primary',__('create_article_for_this_project?'),__('create')     ,edit,{cmnd:'create-article',id:data.id })"
                      class="rounded grow col-span-2   p-2 px-3 text-white cursor-pointer bg-green-600 hover:bg-green-400">
                    {{ data.article_id ? __('article') + ` ${data.article_id} ` : __('create_article') }}
                  </button>
                </div>
              </div>
            </div>
            <div class="">
              <span class="text-gray-500">{{ __('owner') }}:</span>
              <span class="text-primary-700 font-bold mx-2">{{
                  data.owner ? `${data.owner.fullname} | ${data.owner.phone}` : ''
                }}</span>
            </div>
            <div class="">
              <span class="text-gray-500">{{ __('price') }}:</span>
              <span class="text-primary-700 font-bold mx-2">{{ `${asPrice(data.price)} ${__('currency')}` }}</span>
            </div>
            <div class="">
              <span class="text-gray-500">{{ __('created_at') }}:</span>
              <span class="text-primary-700 font-bold mx-2">{{ toShamsi(data.created_at, true) }}</span>
            </div>

            <div v-if=" data.requests " class="flex">
              <span class="text-gray-500">{{ __('requests') }}:</span>
              <span v-for=" itm in data.requests  "
                    class=" text-primary-700   mx-1">{{ __(itm) }}</span>
            </div>
            <div class="">
              <span class="text-gray-500">{{ __('payed_at') }}:</span>
              <span class="text-success font-bold mx-2">{{ toShamsi(data.payed_at, true) }}</span>
            </div>
            <div class="flex flex-col">
              <span class="text-gray-500">{{ __('description') }}:</span>
              <span class="text-primary-700 text-sm whitespace-pre-line  mx-2  ">{{ data.description || '-' }}</span>
            </div>
            <button v-if="!isAdmin()"
                    @click="showDialog('danger',__('pay_and_start_project?') + `<br>${asPrice(data.price)} ${__('currency')}`,__('pay') + ` ${data.price} ${__('currency')} ` ,edit,{cmnd:'pay-project',id:data.id,   })"
                    :disabled="  data.status!='pay'"
                    :class="{'opacity-50':data.payed_at || data.status!='pay'}"
                    class="rounded py-2 text-white cursor-pointer bg-success hover:bg-success-400">
              {{ data.payed_at ? __('payed_at') + ` ${toShamsi(data.payed_at)} ` : __('pay_and_start_project') }}
            </button>
            <div class="grid p-3 gap-2  xs:grid-cols-1 md:grid-cols-2     border rounded   ">
              <div v-if="isAdmin()" class="col-span-2">
                <RadioGroup :beforeSelected="data.status" ref="statusSelector" class="" name="p-status"
                            v-model="data.status"
                            :items="myMap($page.props.project_statuses,(e)=>e.name)"/>
                <InputError class="mt-1" :message="errors.status"/>
              </div>
              <div class="col-span-2">
                <TextInput
                    id="title"
                    type="text"
                    :placeholder="__('article_title')"
                    classes="  col-span-2"
                    v-model="data.title"
                    autocomplete="title"
                    :error="errors.title "
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <Bars2Icon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>
              </div>
              <TextInput v-if="isAdmin()"
                         id="p-price"
                         type="number"
                         :placeholder="__('price') + ` (${__('currency')})`"
                         classes="grow   "
                         v-model="data.price"
                         autocomplete="price"
                         :error="errors.price "
              >
                <template v-slot:prepend>
                  <div class="p-3">
                    <BanknotesIcon class="h-5 w-5"/>
                  </div>
                </template>
              </TextInput>

              <div class="col-span-2">
                <TextInput
                    :multiline="true"
                    id="description"
                    type="text"
                    :placeholder="__('description')"
                    classes="  "
                    v-model="data.description"
                    autocomplete="description"
                    :error="errors.description"
                >
                  <template v-slot:prepend>
                    <div class="p-3">
                      <ChatBubbleBottomCenterTextIcon class="h-5 w-5"/>
                    </div>
                  </template>

                </TextInput>

              </div>

            </div>
          </div>


          <div class="">
            <!--          <InputLabel class="my-2" for="phone" :value="__('operators')"/>-->
            <div class=" rounded-lg bg-white shadow-lg p-2 my-1">
              <div v-if="isAdmin()" class="   flex flex-col">
                <div class="flex w-fit py-2 mb-4  border-b border-primary-300">{{ __('project_items') }}</div>

                <div v-for="(item,idx) in   data.items "
                     class="grid p-2 md:px-4 sm:m-2 grow xs:grid-cols-1 md:grid-cols-1 gap-1   border rounded     "
                     :key="`item-${idx}`">
                  <div class="my-2 flex flex-col space-y-1">
                    <div class="flex w-fit p-2 mb-2 text-primary border-b border-primary-500">{{
                        __('item') + (idx + 1)
                      }}
                    </div>

                    <div class="text-sm">
                      <span class="text-gray-500">{{ __('price') }}:</span>
                      <span class="text-primary-700 font-bold mx-2">{{
                          `${asPrice(item.price)} ${__('currency')}`
                        }}</span>
                    </div>
                    <div class="text-sm">
                      <span class="text-gray-500">{{ __('payed_at') }}:</span>
                      <span class="text-success-700 font-bold mx-2">{{ toShamsi(item.payed_at, true) }}</span>
                    </div>
                  </div>
                  <div class="flex items-center">
                    <label class="relative select-none inline-flex items-center cursor-pointer" dir="ltr">
                      <input :id="`newitem-${idx}`"
                             @change="changeNewItem(item,$event)"
                             role="switch" type="checkbox"
                             class="sr-only peer">
                      <div
                          class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                      <span class="ml-3 text-sm font-medium text-gray-900">{{
                          __('new_item')
                        }}</span>
                    </label>
                    <InputError class="mx-2 " :message="errors[`item.${idx}.type`]"/>

                  </div>
                  <div v-if="item.new" class=" text-xs grow">
                    <RadioGroup :beforeSelected=" item.item_type" :ref="`itemSelector${idx}`" class=""
                                :name="`item-type-${idx}`"
                                v-model="item.item_type"
                                :items=" $page.props.item_types "/>
                    <InputError class="mt-1" :message="errors.item && errors.item.idx? errors.item.idx.type:null"/>
                  </div>
                  <ItemSelector v-else :id="idx" v-model:selected="item.item_id"
                                :item="item.id && item.id.id?item.id: {id:item.item_id,type:item.item_type,name:item.item_id}">
                    <template v-slot:selector="props">
                      <div :class="props.selectedText?'py-2':'py-2'"
                           class=" px-4 mt-2 border rounded hover:bg-gray-100 cursor-pointer flex items-center ">
                        <div class="grow">
                          {{ props.selectedText ?? __('select_item') }}
                        </div>
                        <Link v-if=" props.link " :href="props.link" target="_blank"
                              class="bg-primary mx-1 rounded p-2   cursor-pointer text-white hover:bg-primary-400"
                              @click.stop=""
                        >
                          <EyeIcon class="w-5 h-5"/>
                        </Link>
                        <div v-if="props.selectedText"
                             class="bg-danger rounded p-2   cursor-pointer text-white hover:bg-danger-400"
                             @click.stop="props.clear()">
                          <XMarkIcon class="w-5 h-5"/>
                        </div>
                      </div>
                    </template>

                  </ItemSelector>

                  <UserSelector :key="idx" :id="idx" v-model:selected="item.op" :owner="item.op"
                                v-model:text="item.opText">
                    <template v-slot:selector="props">
                      <div :class="props.selectedText?'py-2':'py-2'"
                           class=" px-4 border rounded hover:bg-gray-100 cursor-pointer flex items-center ">
                        <div class="grow">
                          {{ props.selectedText ?? __('select_operator') }}
                        </div>
                        <div v-if="props.selectedText"
                             class="bg-danger rounded p-2   cursor-pointer text-white hover:bg-danger-400"
                             @click.stop="props.clear()">
                          <XMarkIcon class="w-5 h-5"/>
                        </div>
                      </div>
                    </template>

                  </UserSelector>
                  <InputError class="mx-2 " :message="errors[`item.${idx}.op`]"/>


                  <div class="  items-center text-xs">

                    <RadioGroup :beforeSelected="item.status" ref="statusSelector" class="grow" :name="`status-${idx}`"
                                v-model="item.status"
                                :items="myMap($page.props.project_statuses,(e)=>e.name)"/>
                    <InputError class="m-2  " :message="errors[`item.${idx}.status`]"/>

                  </div>
                  <div class="grid  gap-2  xs:grid-cols-1 sm:grid-cols-2     ">
                    <div>
                      <TextInput
                          :id="`price_${idx}`"
                          type="number"
                          :placeholder="__('price') + ` (${__('currency')})`"
                          classes="grow  "
                          v-model="item.price"
                          autocomplete="price"
                          :error=" errors.items? errors.items.idx.price:null"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <BanknotesIcon class="h-5 w-5"/>
                          </div>
                        </template>
                      </TextInput>
                      <InputError class="mx-2 " :message="errors[`item.${idx}.price`]"/>

                    </div>
                    <div>
                      <TextInput
                          :id="`expires_at_${idx}`"
                          type="number"
                          :placeholder="__('expire_in_hour_zero_unlimited') "
                          classes="grow "
                          v-model="item.remained_hours"
                          autocomplete="expires_at"
                          :error="errors.items?errors.items.idx.remained_hours:null"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <ClockIcon class="h-5 w-5"/>
                          </div>
                        </template>
                      </TextInput>
                      <InputError class="mx-2 " :message="errors[`item.${idx}.expires_at`]"/>

                    </div>
                    <div class="col-span-2">
                      <TextInput
                          :multiline="true"
                          id="description"
                          type="text"
                          :placeholder="__('description')"
                          classes="  "
                          v-model="item.description"
                          autocomplete="description"
                          :error="errors.items?errors.items.idx.description:null"
                      >
                        <template v-slot:prepend>
                          <div class="p-3">
                            <ChatBubbleBottomCenterTextIcon class="h-5 w-5"/>
                          </div>
                        </template>

                      </TextInput>

                    </div>
                    <button
                        @click="showDialog('danger',__('complete_and_add_to_operator_wallet?') + `<br>${asPrice(item.price)} ${__('currency')}<br>${__('user')} ${ item.opText ||'?' } `,__('pay') + ` ${item.price} ${__('currency')} ` ,edit,{cmnd:'pay-operator',id:data.id,item_id:item.id,op:item.op ,price:item.price  })"
                        :disabled="  item.status!='pay'"
                        :class="{'opacity-50':item.payed_at || item.status!='pay'}"
                        class="rounded py-2 text-white cursor-pointer bg-success hover:bg-success-400">
                      {{ item.payed_at ? __('payed_at') + ` ${toShamsi(item.payed_at)} ` : __('pay_and_finish') }}
                    </button>
                  </div>
                  <span v-if="item.item_id==null"
                        @click="data.items.length>1? data.items.splice(idx,1): data.items=[   ]"
                        class="rounded flex w-fit   my-1   cursor-pointer hover:bg-danger-400 bg-danger-500 items-center whitespace-nowrap rounded-e border     px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700"
                  >
               <MinusIcon class="h-5 w-5 text-white font-bold"/>
             </span>
                </div>
                <span @click="data.items.push( {
                                      item_id: null,
                                      price: 0,
                                      item_type: null,
                                      expires_at: null,
                                      status: 'review',
                                      op: {},
                                    })"
                      class="rounded flex w-fit self-end       cursor-pointer hover:bg-green-400 bg-green-500 items-center whitespace-nowrap rounded-e border     px-3 py-[0.25rem] text-center text-base font-normal leading-[1.6] text-neutral-700"
                >
            <PlusIcon class="h-8 w-5 text-white font-bold"/>
        </span>
                <InputError class="mt-1" :message="errors.items"/>
              </div>
              <button
                  @click=" edit(data)"
                  :disabled="!isAdmin() && data.payed_at"
                  :class="{'opacity-50':!isAdmin() && data.payed_at}"
                  class="rounded w-full col-span-2 my-5 py-2 text-white cursor-pointer bg-primary hover:bg-primary-400">
                {{ __('register_info') }}
              </button>
            </div>
          </div>

        </div>
      </div>
    </template>


  </Panel>
</template>

<script>
import Scaffold from "@/Layouts/Scaffold.vue";
import {shallowRef} from 'vue';
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
  SignalIcon,
  ChatBubbleBottomCenterTextIcon,
  PencilIcon,
  MinusIcon,
  PlusIcon,
  BanknotesIcon,
  ClockIcon,
  XMarkIcon,
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
import Article from "@/Components/Article.vue";
import TextEditor from "@/Components/TextEditor.vue";
import UserSelector from "@/Components/UserSelector.vue";
import ItemSelector from "@/Components/ItemSelector.vue";


export default {

  data() {
    return {
      data: this.$page.props.data || {},
      // operators: this.$page.props.data.operators || [{id: null,}],
      errors: {},
      nullItem: {
        item_id: null,
        price: 0,
        item_type: null,
        expires_at: null,
        status: 'review',
        op: {},
      },

    }
  },
  components: {
    UserSelector,
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
    ProvinceCounty,
    PhoneFields,
    SocialFields,
    PencilSquareIcon,
    Article,
    SignalIcon,
    ChatBubbleBottomCenterTextIcon,
    PencilIcon,
    MinusIcon,
    PlusIcon,
    BanknotesIcon,
    ClockIcon,
    XMarkIcon,
    ItemSelector,
  },
  created() {
    if (this.data) {
      this.data.items = this.data.items || [{
        item_id: null,
        price: 0,
        item_type: null,
        expires_at: null,
        status: 'review',
        op: {},
      }];

      this.data.rewrite = false;
    }
  },
  mounted() {

    // console.log(this.data);


  },
  methods: {
    changeNewItem(item, $event,) {
      if ($event.target.checked) {
        item.item_type = this.$page.props.item_types[0];
      } else item.item_type = null;
      item.item_id = null;
      item.new = $event.target.checked;
      // this.log(item);
    },
    edit(params) {
      // params._method = 'patch';
      this.isLoading(true);
      window.axios.patch(route('project.update'), params,
      )
          .then((response) => {
            // this.log(response)
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data.article_id) {
              this.data.article_id = response.data.article_id;
            }
            if (response.data.items) {
              this.data.items = response.data.items;
            }

            if (response.data) {
              location.reload();
            }


          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {
              this.errors = error.response.data.errors || error.response.data;
              if (error.response.data.charge) {
                this.data[params.idx].charge = error.response.data.charge;
              }
              if (error.response.data.view_fee) {
                this.data[params.idx].view_fee = error.response.data.view_fee;
              }
              if (error.response.data.meta) {
                this.data[params.idx].meta = error.response.data.meta;
              }
            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.isLoading(false);
          });
    },
  },

}
</script>
