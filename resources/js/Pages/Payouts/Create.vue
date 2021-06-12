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
              <inertia-link :href="route('payouts.index')">
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

    <div class="mt-5 md:mt-0 md:col-span-2">
      <p>
        Adding of payouts has now been removed in favour of the <a
          href="https://extension.cloudatcocks.com/"
          target="_blank"
        >extension</a>.  Click <a
          href="https://extension.cloudatcocks.com"
          target="_blank"
        >here</a> for more details.
      </p>
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
