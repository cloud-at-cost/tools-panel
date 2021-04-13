<template>
  <div>
    <h3 class="text-left p-2">
      Monthly Payouts
    </h3>
    <div
      ref="chart"
      style="height: 300px;"
    />
  </div>
</template>

<script>
export default {
    name: "AveragePayouts",

    props: {
       minerType: {
           type: String,
           required: true,
       }
    },

    data:() => ({
        chart: undefined
    }),

    mounted() {
         this.chart = new Chartisan({
            el: this.$refs.chart,
            url: this.route('charts.all_payouts_chart', {
                start: '2021-03-01',
                class: this.minerType
            }),
            hooks: new ChartisanHooks()
                .tooltip(true)
                .legend({ position: 'bottom' })
                .datasets([{ type: 'line', fill: false }]),
        });
    }
}
</script>

<style scoped>

</style>
