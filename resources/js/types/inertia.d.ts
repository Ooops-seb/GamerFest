// resources/js/types/inertia.d.ts
import { Page } from '@inertiajs/core';

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $page: Page<any>;
    }
}

export {};
