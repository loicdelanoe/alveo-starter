import { InertiaForm, router, usePage } from '@inertiajs/vue3';

// TODO: Type form
export const deleteItem = (route: string, message: string, form?: InertiaForm<any>) => {
    if (confirm(message)) {
        if (form) {
            form.delete(route, {
                preserveScroll: true,
                preserveState: false,
            });
        } else {
            router.delete(route);
        }
    }
};

export const getRoute = (href: string): string => {
    const basePath = usePage().props.ziggy.url;

    return `${basePath}/${href}`;
};
