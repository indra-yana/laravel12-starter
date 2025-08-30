<script setup lang="ts">
import { ref, watch } from "vue";
import { Switch } from "@/components/ui/switch";
import { Role } from "./RoleManager.vue";

const props = defineProps<{
    roles: Role[];
    modelValue?: string;
}>();

const emit = defineEmits<{
    (e: "update:modelValue", value: string): void;
}>();

const selectedRole = ref<string>(props.modelValue || "");

watch(() => props.modelValue,(val) => { 
    selectedRole.value = val || "";}
);

function onToggle(role: string) {    
    selectedRole.value = role;
    emit("update:modelValue", role);
}
</script>

<template>
    <div class="space-y-3">
        <div v-for="({role, permissions}) in roles" :key="role" class="flex items-center justify-between border rounded-xl p-4 sm:max-w-xl">
            <div class="flex flex-col">
                <span class="font-medium text-gray-800">{{ role }}</span>
                <span class="text-sm text-gray-500">
                    {{ Object.values(permissions).length }} permissions
                </span>
            </div>
            <Switch :model-value="selectedRole === role" @update:model-value="onToggle(role)" />
        </div>
    </div>
</template>
