<template>
    <button type="button" 
            class="w-full bg-red-100 text-red-600 font-semibold dark:bg-red-600 dark:text-red-100
                py-2 px-4 rounded-md border-2 border-red-600 dark:border-red-500
                hover:bg-red-50 hover:text-red-500 dark:hover:bg-red-500 dark:hover:text-red-100
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 
                transition duration-200"
    >
        <slot />
    </button>
</template>