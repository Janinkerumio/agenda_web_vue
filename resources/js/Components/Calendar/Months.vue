<script setup>
import { onMounted, ref, nextTick, onUnmounted } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import api from '@/config/api'

const calendar = ref(null)

const today = new Date()
const countryCode = 'PH'

const currentMonth = ref('')
const currentYear = ref('')

const showYearDrop = ref(false)
const showMonthDrop = ref(false)

const months = [
    'January','February','March','April','May','June',
    'July','August','September','October','November','December'
]

const years = Array.from({ length: 21 }, (_, i) => new Date().getFullYear() - 5 + i)

const calendarOptions = ref({
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    initialDate: today,
    height: 'auto',
    selectable: true,
    editable: true,
    events: [],
    headerToolbar: false,

    dateClick(info) {
        console.log("You clicked:", info.dateStr)
    },

    datesSet(info) {
        const currentDate = info.view.currentStart
        currentMonth.value = currentDate.toLocaleString('default', { month: 'long' })
        currentYear.value = currentDate.getFullYear()
        loadEvents(currentYear.value)
    },
})

async function getHolidays(year) {
    const { data } = await api.get(`https://date.nager.at/api/v3/PublicHolidays/${year}/${countryCode}`)

    return data.map(h => ({
        title: h.localName,
        start: h.date,
        color: '#F59E0B',
        textColor: '#fff',
        editable: false
    }))
}

async function loadEvents(year) {
    const holidays = await getHolidays(year)

    const myEvents = [
        { title: 'Meeting with Team', start: '2025-10-03' },
        { title: 'Dentist Appointment', start: '2025-10-08', color: '#10B981' },
        { title: 'Project Deadline', start: '2025-10-15' },
        { title: 'Weekend Trip', start: '2025-10-22', end: '2025-10-24' },
        { title: '23rd Year', start: '2026-01-07', color: '#EF44' },
    ]

    calendarOptions.value.events = [...myEvents, ...holidays]
}

function closeDropdowns(event) {
    const monthDropdown = document.getElementById('month-dropdown')
    const yearDropdown = document.getElementById('year-dropdown')
    const monthButton = document.querySelector('.month-dropdown-btn')
    const yearButton = document.querySelector('.year-dropdown-btn')

    if (monthDropdown && !monthDropdown.contains(event.target) && event.target !== monthButton) {
        showMonthDrop.value = false
    }
    if (yearDropdown && !yearDropdown.contains(event.target) && event.target !== yearButton) {
        showYearDrop.value = false
    }
}

onMounted(() => {
    currentMonth.value = today.toLocaleString('default', { month: 'long' })
    currentYear.value = today.getFullYear()

    window.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
    window.removeEventListener('click', closeDropdowns)
})

function goToday() {
    calendar.value.getApi().today()
}

function prevMonth() {
    calendar.value.getApi().prev()
}

function nextMonth() {
    calendar.value.getApi().next()
}

function selectMonth(index) {
    const date = new Date(calendar.value.getApi().getDate())
    date.setMonth(index)
    calendar.value.getApi().gotoDate(date)
    showMonthDrop.value = false
}

function selectYear(year) {
    const date = new Date(calendar.value.getApi().getDate())
    date.setFullYear(year)
    calendar.value.getApi().gotoDate(date)
    showYearDrop.value = false
}
</script>

<template>
    <div class="grid grid-row-3">
        <div class="flex items-center justify-between px-4 py-3 bg-white shadow">
            <div class="flex flex-col md:flex-row gap-1">
                <div class="relative">
                    <button @click="showMonthDrop = !showMonthDrop; $event.stopPropagation()" class="flex items-center gap-2 text-gray-700 rounded-lg hover:bg-gray-200 p-1 transition-all duration-200 month-dropdown-btn">
                        <h2 class="text-lg md:text-2xl font-bold text-gray-700">{{ currentMonth }}</h2>
                        <i data-feather="chevron-down" class="mt-1" :class="{ 'rotate-180' : showMonthDrop}"></i>
                    </button>
                    <div id="month-dropdown" v-show="showMonthDrop" @click.stopPropagation class="absolute bg-white border border-gray-300 shadow-lg rounded-lg mt-2 w-36 z-50 max-h-80 overflow-y-auto">
                        <button v-for="(month, index) in months" :key="index" @click="selectMonth(index)" class="w-full text-left px-4 py-2 hover:bg-blue-100 text-gray-700">
                            {{ month }}
                        </button>
                    </div>
                </div>
                <div class="relative">
                    <button @click="showYearDrop = !showYearDrop; $event.stopPropagation()" class="flex items-center gap-2 text-gray-700 rounded-lg hover:bg-gray-200 p-1 transition-all duration-200 year-dropdown-btn">
                        <h3 class="text-lg md:text-2xl font-bold text-gray-600">{{ currentYear }}</h3>
                        <i data-feather="chevron-down" class="mt-1" :class="{ 'rotate-180' : showYearDrop}"></i>
                    </button>
                    <div id="year-dropdown" v-show="showYearDrop" @click.stopPropagation class="absolute bg-white border border-gray-300 shadow-lg rounded-lg mt-2 w-28 z-50 max-h-60 overflow-y-auto">
                        <button v-for="year in years" :key="year" @click="selectYear(year)" class="w-full text-left px-4 py-2 hover:bg-blue-100 text-gray-700">
                            {{ year }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button @click="goToday" class="text-base font-semibold text-gray-500 rounded-lg hover:bg-gray-300 p-1">Today</button>
                <button @click="prevMonth" class="p-2 rounded-lg hover:bg-gray-200 transition"><i data-feather="chevron-left"></i></button>
                <button @click="nextMonth" class="p-2 rounded-lg hover:bg-gray-200 transition"><i data-feather="chevron-right"></i></button>
            </div>
        </div>

        <div class="w-full border border-gray-300"></div>
        <div class="flex-1 bg-gray-50">
            <FullCalendar ref="calendar" :options="calendarOptions" />
        </div>
    </div>
</template>
