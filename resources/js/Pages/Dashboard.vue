<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden sm:rounded-lg">
          <div
            id="my-payouts"
            style="height: 300px;"
          />
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout';

    export default {
        components: {
            AppLayout,
        },

        mounted() {
            new Chartisan({
                el: '#my-payouts',
                url: this.route('charts.my_payouts_chart'),
                hooks: new ChartisanHooks()
                    .title('Your Payouts')
                    .tooltip(true)
                    .legend({ position: 'bottom' })
                    .datasets([{ type: 'line', fill: false }, 'bar']),
            });
        }
    }
</script>
