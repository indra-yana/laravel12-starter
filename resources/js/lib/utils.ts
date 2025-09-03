import { NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function checkIsActive2(href: string, item: NavItem, mainNav = false) {
    return (
        href === item.href || // /endpint?search=param
        href.split('?')[0] === item.href || // endpoint
        !!item?.items?.filter((i) => i.href === href).length || // if child nav is active
        (mainNav &&
            href.split('/')[1] !== '' &&
            href.split('/')[1] === item?.href?.split('/')[1])
    )
}

export function checkIsActive(href: string, item: NavItem, mainNav = false) {
    return item.href === usePage().url || (mainNav && href.split('/')[1] !== '' && href.split('/')[1] === item?.href?.split('/')[1])
}