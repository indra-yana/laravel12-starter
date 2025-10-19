import { MonthlyPeriodProps } from '@/components/logbook/LkbCard.vue';
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

export type NavGroup = {
    title: string
    href: string
    items: NavItem[]
}

export interface NavItem {
    title: string;
    href: string;
    route?: string | string[];
    icon?: LucideIcon | string;
    isActive?: boolean;
    items?: NavItem[],
    badge?: number,
    enable_permission?: boolean;
}

export interface AppConfig {
    name: string;
    tagline: string;
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
    ziggy: Config & { 
        location: string; 
        [key: string]: any;
    };
    sidebarOpen: boolean;
    translations: Record<string, any>;
    menus: Array;
    app: AppConfig;
    flash: {
        type?: 'success' | 'info' | 'warning' | 'error';
        message?: string;
        response?: any;
    };
}

export interface ColumnConfig {
    key: string;
    label: string;
    mapper?: Record<string, string>;
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

export interface LogbookRecap {
    id: number;
    name: string;
    nip: string;
    employment_status?: 'cpns' | 'pns' | 'pppk';
    grade_level?: string | null;          // Golongan/Ruang  "III/a"
    grade_title?: string | null;     // Pangkat   "Penata Muda"
    organizational_unit?: string; 
    work_unit?: string; 
    month?: string;
    status: 'done' | 'pending',
    monthlyperiods?: MonthlyPeriodProps[] | null;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface PaginationResponse<T> {
	data: T[]
	first_page_url: string;
	last_page_url: string;
	prev_page_url: string;
	next_page_url: string;
	total: number;
	per_page: number;
	current_page: number;
	last_page: number;
	links: PaginationLink[];
}

export interface PaginationLink {
	url: string | null;
	separator: string | boolean | null | true;
	label: string;
	active: boolean;
}

export interface PaginationType<T> {
	data: T[];
	firstPageUrl: string;
	lastPageUrl: string;
	prevPageUrl: string;
	nextPageUrl: string;
	totalPage: number;
	pageSize: number;
	pageIndex: number;
	lastPage: number;
	links: PaginationLink[];
}
