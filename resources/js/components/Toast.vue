<script setup lang="ts">
import 'vue-sonner/style.css';
import { computed, onMounted, watch } from 'vue'
import { SharedData } from '@/types';
import { toast } from 'vue-sonner';
import { Toaster } from './ui/sonner';
import { usePage } from '@inertiajs/vue3';

const page = usePage<SharedData>();
const flash = computed(() => page.props.flash);

onMounted(() => {
	showToast()
});

function showToast() {
	const { type, message, } = flash.value;
	if (!message || !type) return;

	switch (type) {
		case 'success':
			toast.success(message);
			break;
		case 'warning':
			toast.warning(message);
			break;
		case 'error':
			toast.error(message);
			break;
		default:
			toast.info(message);
			break;
	}
}

watch(
	() => flash.value,
	() => showToast(),
	{ immediate: true }
)

</script>

<template>
	<Toaster position="top-right" :close-button="true" :rich-colors="true" />
</template>
