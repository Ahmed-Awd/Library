<template>
    <div>
        <jet-banner />

        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <inertia-link :href="route('dashboard')">
                                    <img src="/images/favicon.ico" class="block h-9 w-auto">
                                </inertia-link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                                    {{ __("Dashboard") }}
                                </jet-nav-link>
                                <template v-if="$page.props.user.roles[0].name == 'admin'">
                                    <jet-dropdown align="left" width="48" class="inline-flex items-center report-drop">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                    {{ __("Reports") }}
                                                    <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>
                                        <template #content>
                                            <jet-dropdown-link :href="route('reports.store')">
                                                {{ __("Store") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.all-stores')">
                                                {{ __("All Stores") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.driver')">
                                                {{ __("Driver") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.all-drivers')">
                                                {{ __("All Drivers") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.general')">
                                                {{ __("General") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('top-online')">
                                                {{ __("Online Rank") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('orders-movement')">
                                                {{ __("Orders Movements") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.store-orders')">
                                                {{ __("Store Orders") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.store-charge')">
                                                {{ __("store charge") }}
                                            </jet-dropdown-link>
                                            <jet-dropdown-link :href="route('reports.orders-all')">
                                                {{ __("General") }} {{ __("Orders") }}
                                            </jet-dropdown-link>
                                        </template>
                                    </jet-dropdown>
                                    <jet-nav-link :href="route('stores.index')" :active="route().current('stores.index')">
                                        {{ __("Stores")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('orders.all')" :active="route().current('orders.all')">
                                        {{ __("Orders")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('drivers.index')" :active="route().current('drivers.index')">
                                        {{ __("Drivers")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('towns.index')" :active="route().current('towns.index')">
                                        {{ __("Towns")}}
                                    </jet-nav-link>
<!--                                    <jet-nav-link :href="route('driver-temps.index')" :active="route().current('driver-temps.index')">-->
<!--                                        {{__("Driver Temps")}}-->
<!--                                    </jet-nav-link>-->
                                    <jet-nav-link :href="route('packages.index')" :active="route().current('packages.index')">
                                        {{ __("Packages")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('posts.index')" :active="route().current('posts.index')">
                                        {{ __("Posts")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('settings.index')" :active="route().current('settings.index')">
                                        {{ __("Settings")}}
                                    </jet-nav-link>
                                </template>
                                <template v-if="$page.props.user.roles[0].name == 'owner'">
                                    <jet-nav-link :href="route('orders.index')" :active="route().current('orders.index')">
                                        {{__("Orders")}}
                                    </jet-nav-link>
                                    <jet-nav-link :href="route('stores.settings',$page.props.user.store.id)" :active="route().current('stores.settings')">
                                        {{ __("Settings")}}
                                    </jet-nav-link>
                                </template>
                                <select
                                    class="select_lang form-control d-inline-block"
                                    @change="changeLang($event)"
                                >
                                    <option selected disabled>
                                    {{ __("language")}}
                                    </option>
                                    <option value="en">{{ __("English") }}</option>
                                    <option value="ar">{{ __("Arabic") }}</option>
                                    <option value="tr">{{ __("Turkish") }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="flex items-center px-3 py-2 ml-3 relative text-xs font-medium text-gray-500" v-if="$page.props.user.roles[0].name == 'owner'">
                                <svg class="w-10 h-10 " fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <div>
                                    <span class="flex items-center font-bold text-base ml-3">
                                        {{$page.props.user.balance/100}}
                                    </span>
                                    &nbsp; + {{$page.props.user.reserved_balance/100}} reserved
                                </div>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <jet-dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ $page.props.user.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __("Manage Account")}}
                                        </div>

                                        <jet-dropdown-link :href="route('profile.show')">
                                            {{ __("Profile")}}
                                        </jet-dropdown-link>

                                        <jet-dropdown-link :href="route('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                            {{ __("API Tokens")}}
                                        </jet-dropdown-link>

                                        <div class="border-t border-gray-100"></div>

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <jet-dropdown-link as="button">
                                                {{ __("Log Out")}}
                                            </jet-dropdown-link>
                                        </form>
                                    </template>
                                </jet-dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = ! showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <jet-responsive-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
                            {{ __("Dashboard")}}
                        </jet-responsive-nav-link>
                        <template v-if="$page.props.user.roles[0].name == 'admin'">
                            <jet-responsive-nav-link :href="route('outsources.index')" :active="route().current('outsources.index')">
                                {{ __("OutSources")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('stores.index')" :active="route().current('stores.index')">
                                {{ __("Stores")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('reports.store')" :active="route().current('reports.store')">
                                {{ __("Store Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('reports.all-stores')" :active="route().current('reports.all-stores')">
                                {{ __("All Stores Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('reports.driver')" :active="route().current('reports.driver')">
                                {{ __("Driver Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('reports.all-drivers')" :active="route().current('reports.all-drivers')">
                                {{ __("All Drivers Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('reports.general')" :active="route().current('reports.general')">
                                {{ __("General Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('top-online')" :active="route().current('top-online')">
                                {{ __("Online Rank Report")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('orders.all')" :active="route().current('stores.index')">
                                {{ __("Orders")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('drivers.index')" :active="route().current('drivers.index')">
                                {{ __("Drivers")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('towns.index')" :active="route().current('towns.index')">
                                {{ __("Towns")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('packages.index')" :active="route().current('packages.index')">
                                {{ __("Packages")}}
                            </jet-responsive-nav-link>
                            <jet-responsive-nav-link :href="route('settings.index')" :active="route().current('settings.index')">
                                {{ __("Settings")}}
                            </jet-responsive-nav-link>
                        </template>
                        <template v-if="$page.props.user.roles[0].name == 'owner'">
                            <jet-responsive-nav-link :href="route('orders.index')" :active="route().current('orders.index')">
                                {{ __("Orders")}}
                            </jet-responsive-nav-link>
                            <jet-nav-link :href="route('stores.settings',$page.props.user.store.id)" :active="route().current('stores.settings')">
                                {{ __("Settings")}}
                            </jet-nav-link>
                        </template>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="flex-shrink-0 mr-3" >
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.user.profile_photo_url" :alt="$page.props.user.name" />
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">{{ $page.props.user.name }}</div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.user.email }}</div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <jet-responsive-nav-link :href="route('profile.show')" :active="route().current('profile.show')">
                                {{ __("Profile")}}
                            </jet-responsive-nav-link>

                            <jet-responsive-nav-link :href="route('api-tokens.index')" :active="route().current('api-tokens.index')" v-if="$page.props.jetstream.hasApiFeatures">
                                {{ __("API Tokens")}}
                            </jet-responsive-nav-link>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <jet-responsive-nav-link as="button">
                                    {{ __("Log Out")}}
                                </jet-responsive-nav-link>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200"></div>

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __("Manage Team")}}
                                </div>

                                <!-- Team Settings -->
                                <jet-responsive-nav-link :href="route('teams.show', $page.props.user.current_team)" :active="route().current('teams.show')">
                                    {{ __("Team Settings")}}
                                </jet-responsive-nav-link>

                                <jet-responsive-nav-link :href="route('teams.create')" :active="route().current('teams.create')" v-if="$page.props.jetstream.canCreateTeams">
                                    {{ __("Create New Team")}}
                                </jet-responsive-nav-link>

                                <div class="border-t border-gray-200"></div>

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    {{ __("Switch Teams")}}
                                </div>

                                <template v-for="team in $page.props.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <jet-responsive-nav-link as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </jet-responsive-nav-link>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header"></slot>
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot></slot>
            </main>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" class="hidden">
            <symbol id="icon-store" fill="currentColor"  viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z"/>
            </symbol>
            <symbol id="icon-car" fill="currentColor" viewBox="0 0 64 64">
                <g>
                    <path d="M17.1,30.7c-3.7,0-6.7,3-6.7,6.6c0,3.6,3,6.6,6.7,6.6c3.7,0,6.7-3,6.7-6.6C23.8,33.6,20.8,30.7,17.1,30.7z M17.1,40.3
                        c-1.8,0-3.2-1.4-3.2-3.1s1.4-3.1,3.2-3.1s3.2,1.4,3.2,3.1S18.9,40.3,17.1,40.3z"/>
                    <path d="M46.8,30.5c-3.7,0-6.7,3-6.7,6.6s3,6.6,6.7,6.6s6.7-3,6.7-6.6S50.5,30.5,46.8,30.5z M46.8,40.3c-1.8,0-3.2-1.4-3.2-3.1
                        s1.4-3.1,3.2-3.1c1.8,0,3.2,1.4,3.2,3.1S48.6,40.3,46.8,40.3z"/>
                    <path d="M34.5,35.4h-5c-1,0-1.8,0.8-1.8,1.8s0.8,1.8,1.8,1.8h5c1,0,1.8-0.8,1.8-1.8S35.4,35.4,34.5,35.4z"/>
                    <path d="M58.2,23.4l-1-10.6C56.8,7.3,51.8,3,45.8,3H18.2c-6,0-11,4.3-11.4,9.8l-1,10.6c-2.6,0.6-4.6,2.9-4.6,5.6v16.4
                        c0,3.2,2.6,5.8,5.8,5.8h0.9v5.2c0,2.6,2.1,4.8,4.8,4.8h3.9c2.6,0,4.8-2.1,4.8-4.8v-5.2h21.3v5.2c0,2.6,2.1,4.8,4.8,4.8h3.9
                        c2.6,0,4.8-2.1,4.8-4.8v-5.2H57c3.2,0,5.8-2.6,5.8-5.8V29C62.8,26.2,60.8,23.9,58.2,23.4z M10.2,13.1c0.3-3.7,3.8-6.6,8-6.6h27.6
                        c4.2,0,7.6,2.9,8,6.6l0.9,10.2H9.3L10.2,13.1z M17.9,56.3c0,0.7-0.6,1.3-1.3,1.3h-3.9c-0.7,0-1.3-0.6-1.3-1.3v-5.2h6.4V56.3z
                        M52.6,56.3c0,0.7-0.6,1.3-1.3,1.3h-3.9c-0.7,0-1.3-0.6-1.3-1.3v-5.2h6.4V56.3z M59.3,45.4c0,1.2-1,2.3-2.3,2.3h-0.9H42.6H21.4H7.9
                        H7c-1.2,0-2.3-1-2.3-2.3V29c0-1.2,1-2.3,2.3-2.3h50c1.2,0,2.3,1,2.3,2.3V45.4z"/>
                </g>
            </symbol>
            <symbol id="icon-package" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
            </symbol>
            <symbol id="icon-cash-coin" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z"/>
                <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z"/>
                <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z"/>
                <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z"/>
            </symbol>
            <symbol id="icon-info" fill="currentColor" viewBox="0 0 64 64">
                <circle cx="31.2" cy="6.9" r="3.9"/>
                <path d="M38.7,59.3h-5V20.5c0-2.2-1.8-4-4-4h-4.5c-1,0-1.8,0.8-1.8,1.8s0.8,1.8,1.8,1.8h4.5c0.3,0,0.5,0.2,0.5,0.5v38.7h-5
                    c-1,0-1.8,0.8-1.8,1.8s0.8,1.8,1.8,1.8h13.5c1,0,1.8-0.8,1.8-1.8S39.7,59.3,38.7,59.3z"/>
            </symbol>
            <symbol id="icon-search" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </symbol>
            <symbol id="icon-star" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </symbol>
            <symbol id="icon-translate" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
            </symbol>
            <symbol id="icon-date" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </symbol>
            <symbol id="icon-dropdown" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </symbol>
        </svg>
    </div>
</template>

<script>
    import JetApplicationMark from '@/Jetstream/ApplicationMark'
    import JetBanner from '@/Jetstream/Banner'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetNavLink from '@/Jetstream/NavLink'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink'
    import axios from 'axios';

    export default {
        components: {
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
        },
        data() {
            return {
                showingNavigationDropdown: false,
            }
        },
        created() {
            window.OneSignal = window.OneSignal || [];
            let myCustomUniqueUserId = this.$page.props.user.id.toString();
            OneSignal.push(function() {
                OneSignal.setExternalUserId(myCustomUniqueUserId);
            });
        },
        methods: {
            switchToTeam(team) {
                this.$inertia.put(route('current-team.update'), {
                    'team_id': team.id
                }, {
                    preserveState: false
                })
            },

            logout() {
                this.$inertia.post(route('logout'));
            },
            changeLang(event) {

                this.$inertia.get(this.route('set-lang', event.target.value)).then((result)=>{
                    window.location.reload();
                });

                // console.log( this.$inertia.get(this.route('get-lang')) );

            },
        }
    }
</script>
