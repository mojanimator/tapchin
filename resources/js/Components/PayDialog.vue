<template>
  <!--Modal -->

  <div v-if="selected" class="relative z-[1050]" aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10  w-screen overflow-y-auto">
      <div @click.self="selected=null;errors={}"
           class="flex min-h-full   justify-center p-4 text-center sm:items-center sm:p-0">
        <div
            class="relative transform overflow-auto rounded-lg bg-white   shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
          <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
            <div class=" flex flex-col items-stretch">
              <div class="flex items-center  gap-2">
                <div
                    class="  flex text-warning  h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-warning-100 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                       fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"></path>
                  </svg>
                </div>
                <h3 class="text-base     text-gray-900" id="modal-title">
                  {{ `${__(selected.title)} ` }}
                </h3>
              </div>
              <div class="m-2  text-start">
                <!--                         modal body-->
                <div class="mt-2">

                  <div
                      class="   text-sm text-gray-500 ">
                        <span class="text-sm py-2 text-danger-500">{{
                            `${selected.cmnd == 'settlement' ? __('max') : __('current_balance')}: ${asPrice(selected.wallet)} ${__('currency')}`
                          }}</span>
                    <div class="flex flex-col  space-y-2 text-start ">

                      <div class="flex flex-col  ">

                        <div class="my-2">
                          <TextInput
                              id="amount"
                              type="number"
                              :placeholder="`${__('amount')} (${__('currency')})`"
                              classes="  "
                              v-model="selected.amount"
                              :autocomplete="selected.amount"
                              :error="  errors.amount">

                            <template v-slot:prepend>
                              <div class="p-3">
                                <CurrencyDollarIcon class="h-5 w-5"/>
                              </div>
                            </template>
                          </TextInput>
                        </div>
                        <button
                            class="bg-success-200 text-success-700 p-2 rounded-lg  hover:bg-success-300 w-full"
                            @click="pay(selected)">
                          {{ `${__(selected.title)} ` }}
                        </button>

                      </div>
                    </div>
                  </div>
                  <button class="bg-gray-200 my-2 text-gray-700 p-2 rounded-lg  hover:bg-gray-300 w-full"
                          @click="selected=null;errors={}">
                    {{ __('cancel') }}
                  </button>

                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

</template>

<script>
import TextInput from "@/Components/TextInput.vue";
import {
  CurrencyDollarIcon,
} from "@heroicons/vue/24/outline";

export default {
  name: "PayDialog",
  components: {
    TextInput,
    CurrencyDollarIcon,
  },
  data() {
    return {
      selected: null,
      errors: {},

    }
  },
  methods: {
    show(params) {

      this.selected = params;
    },
    pay(params) {
      this.isLoading(true);
      window.axios.patch(route(`${this.isAdmin() ? 'admin' : 'user'}.panel.financial.update`), params,
          {})
          .then((response) => {
            if (response.data && response.data.message) {
              this.showToast('success', response.data.message);

            }
            if (response.data.url) {
              window.location = response.data.url;
            }
            this.selected = null;

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
  }
}
</script>

<style scoped>

</style>