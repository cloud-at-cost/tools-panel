<template>
  <app-layout>
    <template #header>
      <div class="grid grid-cols-2">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          My Payouts
        </h2>
        <div class="text-right">
          <inertia-link :href="route('payouts.create')">
            Add Payouts
          </inertia-link>
        </div>
      </div>
    </template>

    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-white overflow-hidden sm:rounded-lg">
      <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
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
                      Miner
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
                      Amount (BTC)<br>
                      1 BTC = {{ Math.round(conversion * 100) / 100 }} USD
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
                  <tr v-if="total !== undefined">
                    <td
                      colspan="3"
                      class="text-right uppercase px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                    >
                      Total
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ total }}<br>
                      Estimated: {{ calculateEstimatedUsd(total) }} USD
                    </td>
                    <td />
                  </tr>
                  <tr
                    v-for="(payout, index) in payoutList"
                    :key="payout.hash"
                    :class="{
                      'bg-white': (index % 2) === 0,
                      'bg-gray-50': (index % 2) === 1,
                    }"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(payout.date) }} {{ formatTime(payout.date) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <a
                        :href="route('miners.show', {
                          miner: payout.miner.hash
                        })"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        {{ payout.miner.identifier }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ payout.miner.type.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ payout.amount }}<br>
                      Estimated: {{ calculateEstimatedUsd(payout.amount) }} USD
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-500">
                      {{ payout.type }}
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr
                    v-if="links.first !== links.last"
                    :class="{
                      'bg-white': payoutList.length % 2 === 0,
                      'bg-gray-50': payoutList.length % 2 === 1
                    }"
                  >
                    <td colspan="6">
                      <nav
                        class="bg-white px-4 py-3 flex items-center justify-between sm:px-6"
                        aria-label="Pagination"
                      >
                        <div class="hidden sm:block">
                          <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium">{{ meta.from }}</span>
                            to
                            <span class="font-medium">{{ meta.to }}</span>
                            of
                            <span class="font-medium">{{ meta.total }}</span>
                            results
                          </p>
                        </div>
                        <div class="flex-1 flex justify-between sm:justify-end">
                          <inertia-link
                            v-if="links.prev"
                            :href="links.prev"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                          >
                            Previous
                          </inertia-link>
                          <inertia-link
                            v-if="links.next"
                            :href="links.next"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                          >
                            Next
                          </inertia-link>
                        </div>
                      </nav>
                    </td>
                  </tr>
                  <tr
                    :class="{
                      'bg-white': (payoutList.length + 1) % 2 === 0,
                      'bg-gray-50': (payoutList.length + 1) % 2 === 1
                    }"
                  >
                    <td
                      colspan="5"
                      class="text-center px-6 py-4 w"
                    >
                      <inertia-link :href="route('payouts.create')">
                        Add Payouts
                      </inertia-link>
                    </td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
export default {

    name: "Index",
    components: {AppLayout},
    props: {
        payouts: {
            type: Object,
            required: true
        },

        total: {
            type: Number,
            required: false,
            default: undefined
        },

        conversion: {
            type: Number,
            required: false,
            default: undefined
        }
    },

    computed: {
        payoutList() {
            return this.payouts[0];
        },

        links() {
            return this.payouts.links;
        },

        meta() {
            return this.payouts.meta;
        },
    },

    methods: {
        formatDate(date) {
            const dateInfo = new Date(date);
            return `${dateInfo.getFullYear()}-${this.pad(dateInfo.getMonth(), 2, '0')}-${this.pad(dateInfo.getDate(), 2, '0')}`
        },

        formatTime(date) {
            const dateInfo = new Date(date);
            return `${this.pad(dateInfo.getHours(), 2, '0')}:${this.pad(dateInfo.getMinutes(), 2, '0')}:${this.pad(dateInfo.getSeconds(), 2, '0')}`;
        },

        pad(n, width, z) {
            z = z || '0';
            n = n + '';
            return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
        },

        calculateEstimatedUsd(amount) {
            return Math.round(amount * this.conversion * 10000) / 10000;
        }
    }
}
</script>

<style scoped>

</style>
