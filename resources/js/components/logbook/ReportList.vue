<script setup lang="ts">
import { Download } from "lucide-vue-next";
import { Item, ItemActions, ItemContent, ItemDescription, ItemTitle, } from "@/components/ui/item";
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

interface PdfFile {
    name: string
    url: string
    created_at: string
}

const files = ref<PdfFile[]>([])
const loading = ref(false)

const fetchFiles = async () => {
    loading.value = true
    try {
        const response = await axios.get(route('logbook.racapitulation.reportlist'));
        files.value = response.data;
    } catch (error) {
        console.error('Gagal mengambil file:', error)
    } finally {
        loading.value = false
    }
}

onMounted(() => {
    fetchFiles()

    // Set interval 5 menit
    const interval = setInterval(() => {
        fetchFiles()
    }, 6000 * 10 * 5)

    onUnmounted(() => clearInterval(interval))
})
</script>

<template>
    <div class="flex w-full flex-col gap-1 mt-4" v-if="files.length > 0">
        <span class="font-semibold text-xs" v-if="loading">Memeriksa file terbaru...</span>
        <span class="font-semibold text-xs" v-else>File PDF Terbaru</span>
        <Item variant="muted" size="xs" class="py-1.5 px-2" as-child v-for="file in files" :key="file.name">
            <a :href="file.url" target="_blank">
                <ItemContent>
                    <ItemTitle class="text-xs hover:underline">{{ file.name }}</ItemTitle>
                </ItemContent>
                <ItemActions class="hover:underline">
                    <ItemDescription class=" text-[10px] ">
                        {{ file.created_at }}
                    </ItemDescription>
                    <Download class="size-3.5" />
                </ItemActions>
            </a>
        </Item>
    </div>
</template>
