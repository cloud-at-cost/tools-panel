<template>
  <div>
    <h3 class="text-left p-2">
      Miner Prices
    </h3>
    <div
      ref="chart"
      style="height: 300px;"
    />
  </div>
</template>

<script>
export default {
    name: "MinerPrices",

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
            url: this.route('charts.price_history_chart', {
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
