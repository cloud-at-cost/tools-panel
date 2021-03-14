<template>
  <app-layout>
    <template #header>
      <nav
        class="flex"
        aria-label="Breadcrumb"
      >
        <ol class="flex items-center space-x-4">
          <li>
            <div>
              <inertia-link :href="route('miners.index')">
                Payouts
              </inertia-link>
            </div>
          </li>
          <li>
            <div class="flex items-center">
              <!-- Heroicon name: solid/chevron-right -->
              <svg
                class="flex-shrink-0 h-5 w-5 text-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
              >
                <path
                  fill-rule="evenodd"
                  d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                  clip-rule="evenodd"
                />
              </svg>
              Bulk Add Payouts
            </div>
          </li>
        </ol>
      </nav>
    </template>

    <div>
      <jet-label
        for="details"
        value="Payout Details"
      />
      <p>
        It's simple ... all you need to do is copy and paste the entire table from
        <a
          href="https://panel.cloudatcost.com/wallet"
          target="_blank"
        >here</a> and then the information will
        show up down below!
      </p>

      <textarea
        id="details"
        ref="details"
        v-model="details"
        name="details"
        rows="10"
        class="mt-1 mb-10 block w-full"
      />
    </div>

    <div class="mt-5 md:mt-0 md:col-span-2">
      <jet-section-title>Payout Breakdown</jet-section-title>

      <table class="min-w-full divide-y divide-gray-200 mt-10">
        <thead class="bg-gray-50">
          <tr>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Miner ID
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Package ID
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Miner Type
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Amount
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Date
            </th>
            <th
              scope="col"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Type
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(payout, index) in derived"
            :key="`payout-${index}`"
            :class="{
              'bg-white': (index % 2) === 0,
              'bg-gray-50': (index % 2) === 1,
            }"
          >
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
              {{ payout.minerID }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payout.packageID }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payout.minerType }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payout.amount }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payout.date }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ payout.type }}
            </td>
          </tr>
          <tr v-if="derived.length === 0">
            <td
              colspan="6"
              class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-center"
            >
              Nothing imported yet
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr
            :class="{
              'bg-white': derived.length % 2 === 0,
              'bg-gray-50': derived.length % 2 === 1
            }"
          >
            <td
              colspan="6"
              class="text-center px-6 py-4 w"
            >
              <jet-action-message
                :on="form.recentlySuccessful"
                class="mr-3"
              >
                Saved.
              </jet-action-message>

              <jet-button
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing || !canSubmit"
                @click="create"
              >
                Save Earnings
              </jet-button>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </app-layout>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetLabel from '@/Jetstream/Label'
    import AppLayout from "@/Layouts/AppLayout"
    import JetSectionTitle from '@/Jetstream/SectionTitle'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetLabel,
            JetSectionTitle
        },

        data() {
            return {
                details: '',

                form: this.$inertia.form({
                   payouts: []
                }),
            }
        },

        computed: {
            canSubmit() {
                return this.derived.length > 0;
            },

            derived() {
                return this.details
                    .split('\n')
                    .filter(r => !r.startsWith('MinerID') && r !== '')
                    .map(r => r.split('\t'))
                    .map(r => ({
                        minerID: r[0],
                        packageID: r[1],
                        minerType: r[2],
                        amount: r[3],
                        date: r[4],
                        type: r[5]
                    }));
            }
        },


        methods: {
            create() {
                if(!this.canSubmit) {
                    return;
                }

                this.form.payouts = this.derived;

                this.form.post(route('payouts.store'), {
                    errorBag: 'createPayouts',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                    }
                })
            },
        },
    }
</script>
