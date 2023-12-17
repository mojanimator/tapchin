<template>

  <Panel>
    <template v-slot:header>
      <title>{{__('panel')}}</title>
    </template>


    <template v-slot:content>
      <!-- Content header -->
      <div
          class="flex items-center justify-between px-4 py-2 text-primary-500 border-b md:py-4">
        <div class="flex">
          <Bars2Icon class="h-7 w-7 mx-3"/>
          <h1 class="text-2xl font-semibold">{{ __('available_orders') }}</h1>
        </div>

      </div>
      <!-- Content -->
      <div class="px-2 flex flex-col   md:px-4">

        <div class="flex-col      overflow-x-auto    ">
          <div
              class="flex flex-wrap my-2 bg-white shadow-lg rounded-lg items-center justify-between   p-4">

            <!--              Dropdown Paginate-->
            <div class="flex items-center">
              <div class="relative mx-1  " data-te-dropdown-ref>
                <button
                    id="dropdownPaginate"
                    data-te-dropdown-toggle-ref
                    aria-expanded="false"
                    data-te-ripple-init
                    data-te-ripple-color="light"
                    class=" inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5">

                  <span class="sr-only">table actions</span>
                  <span>{{ params.paginate }}</span>
                  <ChevronDownIcon class="h-4 w-4 mx-1"/>
                </button>

                <!--     menu -->
                <div ref="userMenu" data-te-dropdown-menu-ref
                     class="min-w-[12rem] absolute z-[1000] start-0 text-gray-500  m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-start text-base shadow-lg [&[data-te-dropdown-show]]:block"
                     tabindex="-1" role="menu" aria-orientation="vertical" aria-label="User menu"

                     aria-labelledby="dropdownPaginate">
                  <div v-for=" num in $page.props.pageItems " class="">
                    <div @click="params.paginate=num;getData()" role="menuitem"
                         class=" cursor-pointer  select-none block  p-2 px-6 text-sm   transition-colors hover:bg-gray-100">
                      {{ num }}
                    </div>
                    <hr class="border-gray-200 ">
                  </div>
                </div>
              </div>

              <!--                Paginate-->
              <Pagination @paginationChanged="paginationChanged" :pagination="pagination"/>
            </div>

            <div class="relative ">
              <label for="table-search" class="sr-only">Search</label>
              <div
                  class="absolute inset-y-0 cursor-pointer text-gray-500 hover:text-gray-700  start-0 flex items-center px-3  ">
                <MagnifyingGlassIcon @click=" getData() " class="w-4 h-4 "/>
              </div>
              <div
                  class="absolute inset-y-0 end-0 text-gray-500 flex items-center px-3 cursor-pointer hover:text-gray-700  "
                  @click="params.search=null; getData() ">
                <XMarkIcon class="w-4 h-4 "/>
              </div>
              <input type="text" id="table-search-users" v-model="params.search" @keydown.enter="getData()"
                     class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                     :placeholder="__('search')">
            </div>
          </div>
          <!--           table-->
          <div class="grid grid-cols-1 xl:grid-cols-2  gap-2">
            <div v-for="(d,idx) in data"
                 class="bg-white shadow-lg text-center rounded-lg hover:bg-gray-50">

              <div
                  class="flex flex-col  justify-between h-full px-4 md:px-6 py-4 text-gray-500  ">

                <div class="flex items-center text-primary-500 border-b mb-2">
                  <component v-bind:is="getIcon(d.item_type)"
                             class="w-10 h-10 lg:w-24 lg:h-24 text-primary-500"></component>

                  <div class="mx-2"> {{ (__(d.item_type) || '') }}</div>
                </div>

                <div class="flex items-center  py-1">
                  <div class="text-gray-500">{{ __('commission') }}:</div>
                  <div v-if="d.price" class="mx-1 text-primary">
                    {{
                      asPrice(d.price)
                    }} {{ __('currency') }}
                  </div>
                </div>
                <div class="flex items-center  py-1">
                  <div class="text-gray-500">{{ __('expire') }}:</div>
                  <div class="mx-1 text-primary">
                    {{
                      toShamsi(d.expires_at, true) || __('unlimited')
                    }}
                  </div>
                </div>

                <div class="flex items-center mb-2  w-full bg-gray-100 rounded  py-2">
                  <div v-if="d.chats"
                       class="flex justify-start mx-1 w-full text-sm text-gray-500 whitespace-pre-line   px-2 rounded">
                    <div v-for="(chat,idx) in JSON.parse(d.chats)">
                      <div v-if="idx==0" v-html="chat.text"></div>
                    </div>
                  </div>
                </div>


                <div class="flex w-full ">
                  <button
                      @click="showDialog('danger',__('get_project_and_commission_after_do?') + `<br>${asPrice(d.price)} ${__('currency')}  `,__('do_project')   , edit,{cmnd:'get-project','id':d.project_id,'project_item':d.id,'idx':idx   })"
                      type="button" :href="d.item_type && d.item_id? route(`panel.${d.item_type}.edit`,d.item_id):''"
                      class=" grow  rounded text-sm bg-green-600 text-white px-6  py-2   font-medium uppercase leading-normal text-white transition duration-150 ease-in-out hover:bg-green-500   focus:outline-none focus:ring-0  "
                      data-te-ripple-init
                      data-te-ripple-color="light">
                    {{ __('do_project') }}
                  </button>
                </div>
              </div>


            </div>
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
import Pagination from "@/Components/Pagination.vue";
import {
  Bars2Icon,
  MagnifyingGlassIcon,
  ChevronDownIcon,
  HomeIcon,
  XMarkIcon,
  ArrowsUpDownIcon,

} from "@heroicons/vue/24/outline";
import {

  PhotoIcon,
  MicrophoneIcon,
  PlayIcon,
  DocumentTextIcon,
} from "@heroicons/vue/24/solid";
import Image from "@/Components/Image.vue";
import Tooltip from "@/Components/Tooltip.vue";

export default {
  data() {
    return {
      params: {
        page: 1,
        search: null,
        paginate: this.$page.props.pageItems[0],
        order_by: null,
        dir: 'DESC',
        cmnd: 'available-orders',
      },
      data: [],
      pagination: {},
      toggleSelect: false,
      loading: false,
      error: null,
    }
  },
  components: {
    Head,
    Link,
    HomeIcon,
    ChevronDownIcon,
    Panel,
    Bars2Icon,
    Image,
    MagnifyingGlassIcon,
    XMarkIcon,
    Pagination,
    ArrowsUpDownIcon,
    Tooltip,
    PhotoIcon,
    MicrophoneIcon,
    PlayIcon,
    DocumentTextIcon,
  },
  mounted() {
    // let uri = window.location.href.split('?');
    // if (uri.length > 1 && uri[1].indexOf('cmnd=') !== -1)
    //   this.params.cmnd = uri[1].split('=')[1];
    //
    this.getData();
    // this.showDialog('danger', 'message',()=>{});
    // this.isLoading(false);
  },
  methods: {
    getIcon(type) {
      if (type == 'banner')
        return 'PhotoIcon';
      if (type == 'podcast')
        return 'MicrophoneIcon';
      if (type == 'video')
        return 'PlayIcon';
      if (type == 'text')
        return 'DocumentTextIcon';
    },
    getData() {

      this.loading = true;
      this.data = [];
      window.axios.get(route('panel.project_item.search'), {
        params: this.params
      }, {
        onUploadProgress: function (axiosProgressEvent) {
          console.log(axiosProgressEvent);
          /*{
            loaded: number;
            total?: number;
            progress?: number; // in range [0..1]
            bytes: number; // how many bytes have been transferred since the last trigger (delta)
            estimated?: number; // estimated time in seconds
            rate?: number; // upload speed in bytes
            upload: true; // upload sign
          }*/
        },

        onDownloadProgress: function (axiosProgressEvent) {
          console.log(axiosProgressEvent);

          /*{
            loaded: number;
            total?: number;
            progress?: number;
            bytes: number;
            estimated?: number;
            rate?: number; // download speed in bytes
            download: true; // download sign
          }*/
        }
      })
          .then((response) => {
            this.data = response.data.data;
            this.data.forEach(el => {
              el.selected = false;
            });
            delete response.data.data;
            this.pagination = response.data;

          })

          .catch((error) => {
            if (error.response) {
              // The request was made and the server responded with a status code
              // that falls out of the range of 2xx
              console.log(error.response.data);
              console.log(error.response.status);
              console.log(error.response.headers);
              this.error = error.response.data;

            } else if (error.request) {
              // The request was made but no response was received
              // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
              // http.ClientRequest in node.js
              console.log(error.request);
              this.error = error.request;
            } else {
              // Something happened in setting up the request that triggered an Error
              console.log('Error', error.message);
              this.error = error.message;
            }
            console.log(error.config);
            this.showToast('danger', error)
          })
          .finally(() => {
            // always executed
            this.loading = false;
          });
    },
    toggleAll() {

      this.toggleSelect = !this.toggleSelect;
      this.data.forEach(e => {
        e.selected = this.toggleSelect;
      });
    },
    edit(params) {
      this.isLoading(true);
      window.axios.patch(route('project.update'), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data.owned) {
              this.data.splice(params.idx, 1);

            }


          })

          .catch((error) => {
            this.error = this.getErrors(error);
            if (error.response && error.response.data) {
              if (error.response.data.charge) {
                this.data[params.idx].charge = error.response.data.charge;
              }

            }
            this.showToast('danger', this.error);
          })
          .finally(() => {
            // always executed
            this.isLoading(false);
          });
    },
    paginationChanged(data) {

      this.params.page = data.page;
      this.getData();
    },
    bulkAction(cmnd) {
      if (this.data.filter(e => e.selected).length == 0) {
        this.showToast('danger', this.__('nothing_selected'));
        return;
      }
      this.isLoading(true);
      const params = {
        cmnd: cmnd, data: this.data.reduce((result, el) => {
          if (el.selected) result.push(el.id);
          return result;
        }, [])
      };

      window.axios.patch(route('business.update'), params,
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
            if (response.data && response.data.results) {
              const res = response.data.results;
              for (let i in this.data)
                for (let j in res)
                  if (res[j].id == this.data[i].id) {
                    this.data[i].status = res[j].status;
                    break;
                  }
            }

          })

          .catch((error) => {
            this.error = this.getErrors(error);

            this.showToast('danger', this.error);
          })
          .finally(() => {
            this.isLoading(false);
          });
    }
  },

}
</script>
