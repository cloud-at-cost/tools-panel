<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Miner
      </h2>
    </template>

    <jet-form-section @submitted="create">
      <template #title>
        Create Miner
      </template>

      <template #description>
        Did you make the mistake of buying a miner? If you did, throw it in here so you can track your "return on investment".
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
            value="Identifier"
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
            value="Amount Paid"
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
    import AppLayout from "../../Layouts/AppLayout";

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
                type: Object,
                required: true
            }
        },

        data() {
            return {
                form: this.$inertia.form({
                    type: undefined,
                    identifier: '',
                    amount_paid: 0,
                }),
            }
        },

        computed: {
            canSubmit() {
                return this.form.identifier && this.form.amount_paid;
            }
        },

        created() {
            this.form.type = this.types[0].slug;
        },

        methods: {
            create() {
                if(!this.canSubmit) {
                    return;
                }

                this.form.post(route('miners.store'), {
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
