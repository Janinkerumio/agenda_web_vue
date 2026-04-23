<template>
    <button type="submit" 
            class="w-full bg-blue-100 text-blue-600 font-semibold dark:bg-blue-600 dark:text-blue-100 
                   border-2 border-blue-600 py-2 px-4 rounded-md dark:border-blue-500
                   hover:bg-blue-50 hover:text-blue-500 dark:hover:bg-blue-500 dark:hover:text-blue-100
                   focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                   disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-blue-400
                   transition duration-200"
    >
        <slot /> 
    </button>
</template>