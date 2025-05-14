import { NavItem } from '@/types';
import { BookOpen, Clock10Icon, Folder, Gamepad2, LayoutGrid } from 'lucide-vue-next';

// Register new routes
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Mis Inscripciones',
        href: '/get_mis_inscripciones',
        icon: Gamepad2,
    },
    {
        title: 'Mi equipo',
        href: '/mis_equipos',
        icon: Gamepad2,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
    {
        title: 'Horarios',
        href: '/pdf/horarios_gamerfest.png',
        icon: Clock10Icon,
    },
];

export { footerNavItems, mainNavItems };
