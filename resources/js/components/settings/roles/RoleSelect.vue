<script setup lang="ts">
import { ref, watch } from "vue";
import { Role } from "./RoleManager.vue";
import { Switch } from "@/components/ui/switch";

const props = defineProps<{
    roles: Role[];
    modelValue?: string;
}>();

const emit = defineEmits<{
    (e: "update:modelValue", value: string): void;
}>();

const selectedRole = ref<string>(props.modelValue || "");

watch(() => props.modelValue, (val) => {
    selectedRole.value = val || "";
}
);

function onToggle(role: string) {
    selectedRole.value = role;
    emit("update:modelValue", role);
}
</script>

<template>
    <div class="space-y-3">
        <div v-for="({ role, permissions }) in roles" :key="role" class="flex items-center justify-between border rounded-xl p-4 sm:max-w-xl">
            <div class="flex flex-col">
                <span class="font-medium">{{ role }}</span>
                <span class="text-sm text-muted-foreground">
                    {{ Object.values(permissions).length }} {{ trans('label.permissions') }}
                </span>
            </div>
            <Switch :model-value="selectedRole === role" @update:model-value="onToggle(role)" />
        </div>
    </div>
</template>
