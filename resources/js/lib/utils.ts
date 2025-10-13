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

export function activeInclude(item: NavItem) {
	const current = route().current() || '';
	if (Array.isArray(item.isActive)) {
		return item.isActive.includes(current);
	}

	return current === item.route;
}

export const units = [
	// Aktivitas dan Kegiatan
	'Kegiatan',
	'Aktivitas',
	'Tugas',
	'Program',
	'Proyek',

	// Dokumen dan Arsip
	'Dokumen',
	'File',
	'Arsip',
	'Berkas',
	'Surat',

	// Output Kerja
	'Laporan',
	'Rekap',

	// Pengguna & Entitas
	'Pengguna',
	'Siswa',
	'Pegawai',
	'Admin',
	'Tim',

	// Data dan Sistem
	'Data',
	'Entry',
	'Record',
	'Transaksi',
	'Akses',

	// Visual dan Interaksi
	'Viewer',
	'Halaman',
	'Formulir',

	// Umum / Lainnya
	'Unit',
	'Item',
	'Lainnya',
];

export const monthNames = [
	"Januari", "Februari", "Maret", "April", "Mei", "Juni",
	"Juli", "Agustus", "September", "Oktober", "November", "Desember"
]