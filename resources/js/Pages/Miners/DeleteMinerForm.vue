<template>
  <span>
    <jet-danger-button @click="confirmMinerDeletion">
      Delete Miner
    </jet-danger-button>

    <!-- Delete Account Confirmation Modal -->
    <jet-dialog-modal
      :show="confirmingMinerDeletion"
      @close="closeModal"
    >
      <template #title>
        Delete Miner
      </template>

      <template #content>
        Are you sure you want to delete your Miner?

        <div class="mt-4">
          <jet-input
            ref="password"
            v-model="form.password"
            type="password"
            class="mt-1 block w-3/4"
            placeholder="Password"
            @keyup.enter="deleteMiner"
          />

          <jet-input-error
            :message="form.errors.password"
            class="mt-2"
          />
        </div>
      </template>

      <template #footer>
        <jet-secondary-button @click="closeModal">
          Cancel
        </jet-secondary-button>

        <jet-danger-button
          class="ml-2"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
          @click="deleteMiner"
        >
          Delete Miner
        </jet-danger-button>
      </template>
    </jet-dialog-modal>
  </span>
</template>

<script>
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'

    export default {
        components: {
            JetDangerButton,
            JetDialogModal,
            JetInput,
            JetInputError,
            JetSecondaryButton,
        },

        props: {
            miner: {
                type: Object,
                required: true,
            }
        },

        data() {
            return {
                confirmingMinerDeletion: false,

                form: this.$inertia.form({
                    password: '',
                })
            }
        },

        methods: {
            confirmMinerDeletion() {
                this.confirmingMinerDeletion = true;

                setTimeout(() => this.$refs.password.focus(), 250)
            },

            deleteMiner() {
                this.form.delete(route('miners.destroy', {
                    miner: this.miner.hash
                }), {
                    preserveScroll: true,
                    onSuccess: () => this.closeModal(),
                    onError: () => this.$refs.password.focus(),
                    onFinish: () => this.form.reset(),
                })
            },

            closeModal() {
                this.confirmingMinerDeletion = false

                this.form.reset()
            },
        },
    }
</script>
