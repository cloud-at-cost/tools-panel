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
              Update Miner {{ miner.identifier }}
            </div>
          </li>
        </ol>
      </nav>
    </template>

    <jet-form-section @submitted="update">
      <template #title>
        Update Miner {{ miner.identifier }}
      </template>

      <template #description>
        Update details about the Miner.
      </template>

      <template #form>
        <div class="col-span-6 sm:col-span-4">
          <jet-label
            for="type"
            value="Miner Type"
          />
          <select
            id="type"
            ref="type"
            v-model="form.type"
            name="type"
            class="mt-1 block w-full"
          >
            <option
              v-for="type in types"
              :key="type.slug"
              :value="type.slug"
            >
              {{ type.name }}
            </option>
          </select>
          <jet-input-error
            :message="form.errors.type"
            class="mt-2"
          />
        </div>

        <div class="col-span-6 sm:col-span-4">
          <jet-label
            for="identifier"
            value="Identifier (PackageID)"
          />
          <jet-input
            id="identifier"
            ref="identifier"
            v-model="form.identifier"
            type="text"
            class="mt-1 block w-full"
            required
          />
          <jet-input-error
            :message="form.errors.identifier"
            class="mt-2"
          />
        </div>

        <div class="col-span-6 sm:col-span-4">
          <jet-label
            for="amount_paid"
            value="Amount Paid (USD)"
          />
          <jet-input
            id="amount_paid"
            v-model="form.amount_paid"
            type="text"
            required
            class="mt-1 block w-full"
          />
          <jet-input-error
            :message="form.errors.amount_paid"
            class="mt-2"
          />
        </div>
      </template>

      <template #actions>
        <jet-action-message
          :on="form.recentlySuccessful"
          class="mr-3"
        >
          Saved.
        </jet-action-message>

        <jet-button
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing || !canSubmit"
        >
          Save
        </jet-button>
      </template>
    </jet-form-section>
  </app-layout>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import AppLayout from "@/Layouts/AppLayout";

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: {
            types: {
                type: Array,
                required: true
            },
            miner: {
                type: Object,
                required: true
            },
        },

        data() {
            return {
                form: this.$inertia.form({
                    type: this.miner.type.slug,
                    identifier: this.miner.identifier,
                    amount_paid: this.miner.amount_paid,
                }),
            }
        },

        computed: {
            canSubmit() {
                return this.form.identifier && this.form.amount_paid;
            }
        },

        methods: {
            update() {
                if(!this.canSubmit) {
                    return;
                }

                this.form.patch(route('miners.update', {
                    miner: this.miner.hash
                }), {
                    errorBag: 'createMiner',
                    preserveScroll: true,
                    onSuccess: () => this.form.reset(),
                    onError: () => {
                    }
                })
            },
        },
    }
</script>
