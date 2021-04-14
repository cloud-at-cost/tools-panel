<template>
  <app-layout>
    <template #header>
      <!-- This example requires Tailwind CSS v2.0+ -->
      <nav
        class="flex"
        aria-label="Breadcrumb"
      >
        <ol class="flex items-center space-x-4">
          <li>
            <div>
              <inertia-link :href="route('miners.index')">
                My Miners
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
              Viewing Miner {{ miner.identifier }}
            </div>
          </li>
        </ol>
      </nav>
    </template>

    <ul>
      <li><strong>Miner ID</strong>: {{ miner.miner_id }}</li>
      <li><strong>Package ID</strong>: {{ miner.identifier }}</li>
      <li><strong>Amount Paid</strong>: {{ miner.amount_paid }}</li>
      <li><strong>Type</strong>: {{ miner.type.name }}</li>
      <li><strong>Purchase Date</strong>: {{ miner.purchase_date }}</li>
      <li><strong>Estimated Activation Date</strong>: {{ miner.estimated_activation_date }}</li>
      <li><strong>Activation Date</strong>: {{ miner.activation_date }}</li>
    </ul>

    <div class="border-t border-gray-100 mt-10 mb-10" />

    <h3>Recent Payouts</h3>

    <table class="min-w-full divide-y divide-gray-200 mt-10">
      <thead class="bg-gray-50">
        <tr>
          <th
            scope="col"
            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
          >
            Type
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
            Amount (BTC)
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(payout, index) in recentPayouts"
          :key="payout.hash"
          :class="{
            'bg-white': (index % 2) === 0,
            'bg-gray-50': (index % 2) === 1,
          }"
        >
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
            {{ payout.type }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ formatDate(payout.date) }} {{ formatTime(payout.date) }}
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
            {{ payout.amount }}
          </td>
        </tr>
        <tr v-if="recentPayouts.length === 0">
          <td
            colspan="3"
            class="text-center px-6 py-4"
          >
            Nothing Reported Yet
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr
          :class="{
            'bg-white': recentPayouts.length % 2 === 0,
            'bg-gray-50': recentPayouts.length % 2 === 1
          }"
        >
          <td
            colspan="3"
            class="text-center px-6 py-4 w-100"
          >
            <div class="grid grid-cols-2">
              <inertia-link :href="route('payouts.create')">
                Report Earnings
              </inertia-link>

              <inertia-link :href="route('payouts.index')">
                View All Payouts
              </inertia-link>
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </app-layout>
</template>

<script>
    import AppLayout from "../../Layouts/AppLayout";

    export default {
        components: {
            AppLayout,
        },

        props: {
            miner: {
                type: Object,
                required: true
            },
            recentPayouts: {
                type: Array,
                required: true,
            }
        },

        methods: {
            formatDate(date) {
                return date.split(' ')[0];
            },

            formatTime(date) {
                return date.split(' ')[1];
            },

            pad(n, width, z) {
                z = z || '0';
                n = n + '';
                return n.length >= width ? n : new Array(width - n.length + 1).join(z) + n;
            }
        }
    }
</script>
