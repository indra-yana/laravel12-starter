import { NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function checkIsActive(href: string, item: NavItem, mainNav = false): boolean {
  const currentUrl = usePage().url;

  return (
    // exact match
    item.href === currentUrl ||

    // untuk main nav: cocokkan segmen pertama
    (mainNav &&
      href.split("/")[1] !== "" &&
      href.split("/")[1] === item?.href?.split("/")[1]) ||

    // kalau current url dimulai dengan href parent (misalnya /settings*)
    (item.href && currentUrl.startsWith(item.href)) ||

    // recursive cek ke child items
    !!item?.items?.some((i) => checkIsActive(href, i, mainNav))
  );
}
