import { Menu } from '@/types/models/menu';
import { usePage } from '@inertiajs/vue3';
import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export const getMenu = (slug: string): Menu | null => {
    const menus = usePage().props.menus as Menu[];

    const menu = menus.find((menu: { slug: string }) => menu.slug === slug);

    if (!menu) {
        console.error(`Menu with slug "${slug}" not found.`);
        return null;
    }

    return menu;
};
