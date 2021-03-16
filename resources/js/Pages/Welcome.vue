<template>
  <div class="bg-white">
    <div class="relative overflow-hidden">
      <header class="relative">
        <div class="bg-gray-900 pt-6">
          <nav
            class="relative max-w-7xl mx-auto flex items-center justify-between px-4 sm:px-6"
            aria-label="Global"
          >
            <div class="flex items-center flex-1">
              <div class="flex items-center justify-between w-full md:w-auto">
                <a href="#">
                  <span class="sr-only">Mining@CloudAtCocks</span>
                  <img
                    class="h-8 w-auto sm:h-10"
                    src="https://cloudatcocks.com/assets/logo-9s7p9weo.png"
                    alt=""
                  >
                </a>
                <div class="-mr-2 flex items-center md:hidden">
                  <button
                    v-if="!isOpen"
                    type="button"
                    class="bg-gray-900 rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:bg-gray-800 focus:outline-none focus:ring-2 focus-ring-inset focus:ring-white"
                    aria-expanded="false"
                    @click="isOpen = !isOpen"
                  >
                    <span class="sr-only">Open main menu</span>
                    <!-- Heroicon name: outline/menu -->
                    <svg
                      class="h-6 w-6"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                      />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="space-x-8 md:flex md:ml-10" />
            </div>
            <div class="hidden md:flex md:items-center md:space-x-6">
              <a
                href="/login"
                class="text-base font-medium text-white hover:text-gray-300"
              >
                Log in
              </a>
              <a
                href="/register"
                class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700"
              >
                Sign me Up
              </a>
            </div>
          </nav>
        </div>

        <!--
                  Mobile menu, show/hide based on menu open state.

                  Entering: "duration-150 ease-out"
                    From: "opacity-0 scale-95"
                    To: "opacity-100 scale-100"
                  Leaving: "duration-100 ease-in"
                    From: "opacity-100 scale-100"
                    To: "opacity-0 scale-95"
                -->
        <transition
          enter-active-class="duration-150 ease-out"
          enter-class="opacity-0 scale-95"
          enter-to-class="opacity-100 scale-100"
          leave-active-class="duration-100 ease-in"
          leave-class="opacity-100 scale-100"
          leave-to-class="opacity-0 scale-95"
        >
          <div
            v-if="isOpen"
            class="absolute top-0 inset-x-0 p-2 transition transform origin-top md:hidden"
          >
            <div class="rounded-lg shadow-md bg-gray-100 ring-1 ring-black ring-opacity-5 overflow-hidden">
              <div class="px-5 pt-4 flex items-center justify-between">
                <div>
                  <img
                    class="h-8 w-auto"
                    src="https://cloudatcocks.com/assets/logo-9s7p9weo.png"
                    alt="Mining@CloudAtCocks.com"
                  >
                </div>
                <div class="-mr-2">
                  <button
                    type="button"
                    class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-cyan-600"
                    @click="isOpen = !isOpen"
                  >
                    <span class="sr-only">Close menu</span>
                    <!-- Heroicon name: outline/x -->
                    <svg
                      class="h-6 w-6"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                      />
                    </svg>
                  </button>
                </div>
              </div>
              <div class="pt-5 pb-6">
                <div class="mt-6 px-5">
                  <a
                    :href="route('register')"
                    class="block text-center w-full py-3 px-4 rounded-md shadow bg-gradient-to-r font-medium hover:from-teal-600 hover:to-cyan-700"
                  >Sign me Up</a>
                </div>
                <div class="mt-6 px-5">
                  <p class="text-center text-base font-medium text-gray-500">
                    <a
                      :href="route('login')"
                      class="text-gray-900 hover:underline"
                    >Login</a>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </header>
      <main>
        <div class="pt-10 bg-gray-900 sm:pt-16 lg:pt-8 lg:pb-14 lg:overflow-hidden">
          <div class="mx-auto max-w-7xl lg:px-8">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8">
              <div class="mx-auto max-w-md px-4 sm:max-w-2xl sm:px-6 sm:text-center lg:px-0 lg:text-left lg:flex lg:items-center">
                <div class="lg:py-24">
                  <a
                    :href="route('register')"
                    class="inline-flex items-center text-white bg-black rounded-full p-1 pr-2 sm:text-base lg:text-sm xl:text-base hover:text-gray-200"
                  >
                    <span class="px-3 py-0.5 text-white text-xs font-semibold leading-5 uppercase tracking-wide bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full">
                      I took the plunge 🤦
                    </span>
                    <span class="ml-4 text-sm">
                      Track your "earnings"
                    </span>
                    <!-- Heroicon name: solid/chevron-right -->
                    <svg
                      class="ml-2 w-5 h-5 text-gray-500"
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
                  </a>
                  <h1 class="mt-4 text-4xl tracking-tight font-extrabold text-white sm:mt-5 sm:text-6xl lg:mt-6 xl:text-6xl">
                    <span class="block">Help others know what's going on ...</span>
                  </h1>
                  <p class="mt-3 text-base text-gray-300 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                    Did you dive deep into the realms of Miners at CloudAtCocks? If you did, track your "earnings" here
                    and we'll share the information with the community to get a better sense of how it functions.
                  </p>
                </div>
              </div>
              <div class="mt-12 -mb-16 sm:-mb-48 lg:m-0 lg:relative">
                <div class="mx-auto max-w-md px-4 sm:max-w-2xl sm:px-6 lg:max-w-none lg:px-0">
                  <!-- Illustration taken from Lucid Illustrations: https://lucid.pixsellz.io/ -->
                  <img
                    class="w-full lg:absolute lg:inset-y-0 lg:left-0 lg:h-full lg:w-auto lg:max-w-none"
                    src="https://tailwindui.com/img/component-images/cloud-illustration-teal-cyan.svg"
                    alt=""
                  >
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Feature section with screenshot -->
        <div class="relative bg-gray-50 pt-16 sm:pt-24 lg:pt-32">
          <div class="mx-auto max-w-md px-4 text-center sm:px-6 sm:max-w-3xl lg:px-8 lg:max-w-7xl">
            <div>
              <h2 class="text-base font-semibold tracking-wider text-cyan-600 uppercase">
                Break it Down!
              </h2>
              <p class="mt-2 text-3xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                Don't believe in their offerings? Can't say I blame you ...
              </p>
              <p class="mt-5 max-w-prose mx-auto text-xl text-gray-500">
                Use this to the growth of your BTC as well as help others make informed decisions.
              </p>
              <ul class="mt-5 max-w-prose mx-auto text-xl text-gray-500">
                <li><strong>Miners Tracked</strong>: {{ stats.miners }}</li>
                <li><strong>Payouts Logged</strong>: {{ stats.payouts }}</li>
              </ul>
            </div>
            <div class="mt-12 -mb-10 sm:-mb-24 lg:-mb-80">
              <div
                id="chart"
                style="height: 300px;"
              />
            </div>
          </div>
        </div>
      </main>
      <footer
        class="bg-gray-50"
        aria-labelledby="footerHeading"
      >
        <h2
          id="footerHeading"
          class="sr-only"
        >
          Footer
        </h2>
        <div class="max-w-md mx-auto pt-12 px-4 sm:max-w-7xl sm:px-6 lg:pt-16 lg:px-8">
          <div class="xl:grid xl:grid-cols-3 xl:gap-8">
            <div class="space-y-8 xl:col-span-1">
              <application-logo />
              <p class="text-gray-500 text-base">
                Don't believe in their offering? Don't worry ... I don't blame you!
              </p>
              <div class="flex space-x-6">
                <!-- No icons -->
              </div>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-8 xl:mt-0 xl:col-span-2">
              <div class="md:grid md:grid-cols-2 md:gap-8">
                <!-- Nothing here -->
              </div>
              <div class="md:grid md:grid-cols-2 md:gap-8">
                <!-- Nothing here -->
              </div>
            </div>
          </div>
          <div class="mt-12 border-t border-gray-200 py-8">
            <p class="text-base text-gray-400 xl:text-center">
              &copy; {{ (new Date()).getFullYear() }} Mining@CloudAtCocks.com
            </p>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script>
import ApplicationLogo from "../Jetstream/ApplicationLogo";
export default {
    components: {ApplicationLogo},
    props: {
        canLogin: Boolean,
        canRegister: Boolean,
        stats: {
            type: Object,
            required: true
        }
    },

    data: () => ({
        isOpen: false,
    }),

    mounted() {
        const chart = new Chartisan({
            el: '#chart',
            url: this.route('charts.all_payouts_chart'),
            hooks: new ChartisanHooks()
                .title('Average Payouts')
                .legend({ position: 'bottom' })
                .datasets([{ type: 'line', fill: false }]),
        });
    }
}
</script>

<style scoped>
    .bg-gray-100 {
        background-color: #f7fafc;
        background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
    }

    .border-gray-200 {
        border-color: #edf2f7;
        border-color: rgba(237, 242, 247, var(--tw-border-opacity));
    }

    .text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }

    .text-gray-500 {
        color: #a0aec0;
        color: rgba(160, 174, 192, var(--tw-text-opacity));
    }

    .text-gray-600 {
        color: #718096;
        color: rgba(113, 128, 150, var(--tw-text-opacity));
    }

    .text-gray-700 {
        color: #4a5568;
        color: rgba(74, 85, 104, var(--tw-text-opacity));
    }

    .text-gray-900 {
        color: #1a202c;
        color: rgba(26, 32, 44, var(--tw-text-opacity));
    }

    @media (prefers-color-scheme: dark) {
        .dark\:bg-gray-800 {
            background-color: #2d3748;
            background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
        }

        .dark\:bg-gray-900 {
            background-color: #1a202c;
            background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
        }

        .dark\:border-gray-700 {
            border-color: #4a5568;
            border-color: rgba(74, 85, 104, var(--tw-border-opacity));
        }

        .dark\:text-white {
            color: #fff;
            color: rgba(255, 255, 255, var(--tw-text-opacity));
        }

        .dark\:text-gray-400 {
            color: #cbd5e0;
            color: rgba(203, 213, 224, var(--tw-text-opacity));
        }
    }
</style>
