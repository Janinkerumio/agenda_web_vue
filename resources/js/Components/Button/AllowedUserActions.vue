<script setup>
defineProps({
    handleView: Function,
    handleEdit: Function,
    handleDelete: Function
})
</script>

<template>
    <div class="rounded-lg border border-gray-400">
        <button @click="handleView" class="border-r text-slate-500 border-gray-400 px-3 py-2 rounded-l-lg hover:text-slate-400">View</button>
        <button @click="handleEdit" class="border-r text-blue-600 border-gray-400 px-3 py-2 hover:text-blue-500" >Edit</button>
        <button @click="handleDelete" class="px-3 text-red-600 py-2 rounded-r-lg hover:text-red-500" >Delete</button>
    </div>
</template>