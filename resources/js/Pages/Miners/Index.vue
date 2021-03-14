<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        My Miners
      </h2>
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
                      Type
                    </th>
                    <th
                      scope="col"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Amount Paid
                    </th>
                    <th
                      scope="col"
                      class="relative px-6 py-3"
                      colspan="2"
                    >
                      <span class="sr-only">Edit</span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(miner, index) in miners"
                    :key="miner.hash"
                    :class="{
                      'bg-white': (index % 2) === 0,
                      'bg-gray-50': (index % 2) === 1,
                    }"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <a
                        :href="route('miners.show', {
                          miner: miner.hash
                        })"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        {{ miner.miner_id }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      <a
                        :href="route('miners.show', {
                          miner: miner.hash
                        })"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        {{ miner.identifier }}
                      </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ miner.type.name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ miner.amount_paid }}
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                      <inertia-link
                        :href="route('miners.edit', {
                          miner: miner.hash
                        })"
                        class="text-indigo-600 hover:text-indigo-900"
                      >
                        Edit
                      </inertia-link>
                    </td>
                    <td class="px-6 py-4 text-right text-sm font-medium">
                      <delete-miner-form :miner="miner" />
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr
                    :class="{
                      'bg-white': miners.length % 2 === 0,
                      'bg-gray-50': miners.length % 2 === 1
                    }"
                  >
                    <td
                      colspan="6"
                      class="text-center px-6 py-4 w"
                    >
                      <inertia-link :href="route('miners.create')">
                        Add Miner
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
import DeleteMinerForm from "./DeleteMinerForm";
export default {

    name: "Index",
    components: {DeleteMinerForm, AppLayout},
    props: {
        miners: {
            type: Object,
            required: true
        }
    },
}
</script>

<style scoped>

</style>
