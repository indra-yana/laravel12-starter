<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem, type SharedData, type User } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import SettingsLayout from '@/layouts/SettingLayout.vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: trans('label.settings'),
        href: '/settings/profile',
    },
    {
        title: trans('label.profile'),
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Profile Settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-12 w-full">
                <div class="flex flex-col space-y-6 max-w-xl">
                    <HeadingSmall :title="trans('label.profile_information')" :description="trans('label.profile_information_description')" />

                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="grid gap-2">
                            <Label for="name">{{ trans('label.name') }}</Label>
                            <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" :placeholder="trans('placeholder.full_name')" />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="grid gap-2">
                            <Label for="email">{{ trans('label.email_address') }}</Label>
                            <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="username" :placeholder="trans('placeholder.email')" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div v-if="mustVerifyEmail && !user.email_verified_at">
                            <p class="-mt-4 text-sm text-muted-foreground">
                                {{ trans('label.email_address_is_unverified') }}
                                <Link :href="route('verification.send')" method="post" as="button" class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500">
                                {{ trans('label.click_here_to_resend_verification_email') }}
                                </Link>
                            </p>

                            <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                                {{ trans('label.an_new_verification_link') }}
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing">{{ trans('button.save') }}</Button>

                            <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0" leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">{{ trans('label.saved') }}</p>
                            </Transition>
                        </div>
                    </form>
                </div>

                <DeleteUser />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
