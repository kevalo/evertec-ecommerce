<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap mt-8 justify-center">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                />

                <Button v-else-if="searchTerm"
                        @click="click(link.url+ '&filter=' + searchTerm)"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        :class="{ 'bg-primary text-white': link.active }"
                        v-html="link.label"
                />
                <Button v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                        :class="{ 'bg-primary text-white': link.active }"
                        @click="click(link.url)" v-html="link.label"/>
            </template>
        </div>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    links: Array,
    searchTerm: String,
    click: Function
});

const components = {
    Link,
};

</script>
