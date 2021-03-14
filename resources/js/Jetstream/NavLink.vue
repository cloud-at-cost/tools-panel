<template>
  <inertia-link
    v-if="as !== 'button'"
    :href="href"
    :class="classes"
  >
    <slot />
  </inertia-link>
  <button
    v-if="as === 'button'"
    type="submit"
    :class="classes"
  >
    <slot />
  </button>
</template>

<script>
    export default {
        props: ['href', 'active', 'as'],

        computed: {
            classes() {
                return this.active
                            ? `bg-gray-900 text-white group flex items-center px-2 py-2 text-base font-medium rounded-md bg-gray-900 text-white`
                            : `text-gray-300 hover:bg-gray-700 hover:text-white group flex items-center px-2 py-2 text-sm font-medium rounded-md"`;
            }
        }
    }
</script>
