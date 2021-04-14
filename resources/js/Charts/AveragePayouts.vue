<template>
  <div>
    <h3 class="text-left p-2">
      Monthly Payouts
    </h3>
    <div
      v-if="!refresh"
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
        },
        startDate: {
            type: String,
            required: true,
        },
        endDate: {
            type: String,
            required: true,
        }
    },

    data:() => ({
        chart: undefined,
        refresh: false,
    }),

    computed: {
        url() {
            return this.route('charts.all_payouts_chart', {
                startDate: this.startDate,
                endDate: this.endDate,
                class: this.minerType
            })
        }
    },

    watch: {
        startDate(){
            this.updateGraph();
        },
        endDate() {
            this.updateGraph();
        }
    },

    mounted() {
         this.chart = new Chartisan({
            el: this.$refs.chart,
            url: this.url,
            hooks: new ChartisanHooks()
                .tooltip(true)
                .legend({ position: 'bottom' })
                .datasets([{ type: 'line', fill: false }]),
        });
    },

    methods: {
        updateGraph() {
            this.refresh = true;

            // This is a ugly hack to get it to refresh without an error
            this.$nextTick(() => {
                this.refresh = false;

                this.$nextTick(() => {
                    this.chart = new Chartisan({
                        el: this.$refs.chart,
                        url: this.url,
                        hooks: new ChartisanHooks()
                            .tooltip(true)
                            .legend({ position: 'bottom' })
                            .datasets([{ type: 'line', fill: false }]),
                    });
                });
            });
        }
    }
}
</script>

<style scoped>

</style>
