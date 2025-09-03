import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    route?: string | string[];
    icon?: LucideIcon;
    isActive?: boolean;
    items?: NavItem[],
    badge?: number,
}

export interface AppConfig {
    name: string;
    version: string;
    env: string;
    debug: boolean;
    url: string;
    timezone: string;
    locale: string;
    fallback_locale: string;
}

export interface SharedData extends PageProps {
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    app: AppConfig;
    flash: {
        type?: 'success' | 'info' | 'warning' | 'error';
        message?: string;
        response?: any;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at?: string | null;
    created_at?: string;
    updated_at?: string;
    status: 'active' | 'inactive',
    is_active: boolean,
}

export type BreadcrumbItemType = BreadcrumbItem;
