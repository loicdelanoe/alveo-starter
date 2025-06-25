import { LaravelCollection } from './collection';
import { Page } from './page';

export interface Menu extends LaravelCollection {
    name: string;
    slug: string;
    pages: Page[];
    actions: Action[];
    active: boolean;
}

type Action = { id: number; label: string; value?: string };
