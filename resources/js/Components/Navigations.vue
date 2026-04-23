<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed, ref, onMounted, onUnmounted, nextTick } from 'vue';
import SidebarLink from '@/Components/Sidebar/SidebarLink.vue';
import SidebarAccordion from '@/Components/Sidebar/SidebarAccordion.vue';
import SidebarHead from '@/Components/Sidebar/SidebarHead.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
const adminAccess = computed(() => user.value?.role === 'admin');

const sidebar = ref(null);
const overlay = ref(null);
const isSidebarOpen = ref(true);
const isMobileMenuOpen = ref(false);

const logout = () => {
    router.post(route('logout'))
}

const toggleSidebar = (isCollapsed) => {
    if (!sidebar.value) return;
    
    const toggleSidebarBtn = document.getElementById('toggle-sidebar');
    const openSidebarBtn = document.getElementById('open-sidebar');
    const sideLogo = document.getElementById('side-logo');
    
    if (isCollapsed) {
        sidebar.value.classList.add('w-20');
        sidebar.value.classList.remove('w-64', 'overflow-auto');
        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.sidebar-tooltip').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('.accordion-group > button > .inline-block').forEach(el => el.classList.add('hidden'));
        toggleSidebarBtn.classList.add('hidden');
        openSidebarBtn.classList.remove('hidden');
        sideLogo.classList.add('hidden');
    } else {
        sidebar.value.classList.remove('w-20');
        sidebar.value.classList.add('w-64', 'overflow-auto');
        document.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
        document.querySelectorAll('.sidebar-tooltip').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.accordion-group > button > .inline-block').forEach(el => el.classList.remove('hidden'));
        toggleSidebarBtn.classList.remove('hidden');
        openSidebarBtn.classList.add('hidden');
        sideLogo.classList.remove('hidden');
    }
    
    isSidebarOpen.value = !isCollapsed;
    
    window.dispatchEvent(new CustomEvent('sidebar-toggle'));
}

const toggleSidebarOnNavClick = () => {
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    if (isMobile) {
        closeMobileSidebar();
    } else {
        const sidebar = document.getElementById('sidebar');
        if (sidebar.classList.contains('w-20')) {
            toggleSidebar(false);
        }
    }
};

const toggleMobileSidebar = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    if (sidebar.value) {
        sidebar.value.classList.toggle('open', isMobileMenuOpen.value);
    }
    if (overlay.value) {
        overlay.value.classList.toggle('open', isMobileMenuOpen.value);
    }
}

const closeMobileSidebar = () => {
    isMobileMenuOpen.value = false;
    if (sidebar.value) {
        sidebar.value.classList.remove('open');
    }
    if (overlay.value) {
        overlay.value.classList.remove('open');
    }
}

const handleResize = () => {
    const isMobile = window.matchMedia('(max-width: 768px)').matches;
    if (!isMobile && isMobileMenuOpen.value) {
        closeMobileSidebar();
    }
}

const initRefs = () => {
    sidebar.value = document.getElementById('sidebar');
    overlay.value = document.getElementById('overlay');
    
    const menuToggle = document.getElementById('menu-toggle');
    const mobileToggleSidebar = document.getElementById('mobile-toggle-sidebar');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', toggleMobileSidebar);
    }
    if (mobileToggleSidebar) {
        mobileToggleSidebar.addEventListener('click', closeMobileSidebar);
    }
    if (overlay.value) {
        overlay.value.addEventListener('click', closeMobileSidebar);
    }
}

onMounted(() => {
    window.addEventListener('resize', handleResize);
    initRefs();
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});

window.toggleSidebar = toggleSidebar;
window.toggleMobileSidebar = toggleMobileSidebar;
window.toggleSidebarOnNavClick = toggleSidebarOnNavClick;
</script>

<template>
    <div class="sidebar bg-white border-r border-gray-200 flex-shrink-0 transition-all duration-300 ease-in-out overflow-auto" id="sidebar" :class="{ 'open': isMobileMenuOpen }">
        <SidebarHead />
        <nav class="p-4">
            <div class="space-y-2">
                <SidebarLink :href="route('dashboard')" routeName="dashboard" label="Dashboard" icon="home">
                    <i data-feather="home"></i>
                </SidebarLink>
                <SidebarLink :href="route('calendar')" routeName="calendar" label="Calendar">
                    <i data-feather="calendar"></i>
                </SidebarLink>

                <div v-if="adminAccess">
                    <SidebarAccordion routePrefix="agenda" label="Agenda" icon="book-open">
                        <SidebarLink :href="route('agenda.create')" routeName="agenda.create" label="Create Agenda">
                            <i class="fa-regular fa-calendar-plus text-2xl"></i>
                        </SidebarLink>
                        <SidebarLink :href="route('agenda.view-all')" routeName="agenda.view-all" label="All Agendas">
                            <i data-feather="layout"></i>
                        </SidebarLink>
                    </SidebarAccordion>
                </div>
                <SidebarLink v-else :href="route('agenda.view-all')" routeName="agenda.view-all" label="Agendas">
                    <i data-feather="layout"></i>
                </SidebarLink>
                
                <div v-if="['admin', 'member'].includes(user?.role)">
                    <SidebarAccordion routePrefix="concerns" label="Concerns" icon="inbox">
                        <SidebarLink :href="route('concerns.all-concerns')" routeName="concerns.all-concerns" label="All Concerns">
                            <i data-feather="trello"></i>
                        </SidebarLink>
                        <SidebarLink :href="route('concerns.my-concerns')" routeName="concerns.my-concerns" label="My Concerns">
                            <i data-feather="user-minus"></i>
                        </SidebarLink>
                    </SidebarAccordion>
                </div>
                <SidebarLink v-else :href="route('concerns.all-concerns')" routeName="concerns.all-concerns" label="Concerns">
                    <i data-feather="trello"></i>
                </SidebarLink>
                
                <div v-if="adminAccess">
                    <SidebarAccordion routePrefix="archives" label="Archives" icon="archive">
                        <SidebarLink :href="route('archives.reports')" routeName="archives.reports" label="Reports">
                            <i data-feather="file-text"></i>
                        </SidebarLink>
                        <SidebarLink :href="route('archives.history')" routeName="archives.history" label="History">
                            <i data-feather="clock"></i>
                        </SidebarLink>
                    </SidebarAccordion>
                </div>

                <div v-if="adminAccess" class="border-t border-gray-200 pt-2 mt-2">
                    <p class="text-xs text-gray-500 px-2 mb-1 sidebar-text">MANAGEMENT</p>
                    <SidebarLink :href="route('people')" routeName="people" label="People">
                        <i data-feather="users"></i>
                    </SidebarLink>
                </div>

                <div v-if="adminAccess">
                    <SidebarAccordion routePrefix="trash" label="Trash" icon="trash">
                        <SidebarLink :href="route('trash.agendas')" routeName="trash.agendas" label="Agendas">
                            <i data-feather="book-open"></i>
                        </SidebarLink>
                        <SidebarLink :href="route('trash.concerns')" routeName="trash.concerns" label="Concerns">
                            <i data-feather="trello"></i>
                        </SidebarLink>
                    </SidebarAccordion>
                </div>

                <SidebarLink v-if="['member'].includes(user?.role)" :href="route('trash.concerns')" routeName="trash.concerns" label="Trash Bin">
                    <i data-feather="trello"></i>
                </SidebarLink>

                <SidebarAccordion routePrefix="settings" label="Settings" icon="settings">
                    <SidebarLink :href="route('settings.profile')" routeName="settings.profile" label="Profile">
                        <i data-feather="user"></i>
                    </SidebarLink>
                </SidebarAccordion>

                <button @click="logout" class="flex items-center space-x-3 p-2 rounded-lg text-gray-700 hover:bg-red-200 hover:text-red-600 group relative mt-4 transition-all duration-300 ease-in-out">
                    <i data-feather="log-out"></i>
                    <span class="sidebar-text">Logout</span>
                    <span class="sidebar-tooltip hidden absolute left-full ml-2 px-2 py-1 bg-gray-800 text-white text-xs rounded whitespace-nowrap">Logout</span>
                </button>
            </div>
        </nav>
    </div>
</template>

<style>
    .sidebar {
        transition: all 0.3s ease;
        width: 16rem;
        position: sticky;
    }
    .sidebar.w-20 {
        width: 5rem;
    }
    .sidebar.w-20 .sidebar-text {
        display: none;
    }
    .sidebar.w-20 .sidebar-tooltip {
        display: block !important;
    }
    .sidebar a, .sidebar button {
        position: relative;
    }
    .sidebar-tooltip {
        z-index: 20;
        opacity: 0;
        transition: opacity 0.3s;
    }
    .sidebar a:hover .sidebar-tooltip,
    .sidebar button:hover .sidebar-tooltip {
        opacity: 1;
    }
    .accordion-content {
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            position: absolute;
            z-index: 50;
            width: 320px;
            height: 80%;
        }
        .sidebar.open {
            transform: translateX(0);
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 40;
        }
        .overlay.open {
            display: block;
        }
    }
</style>