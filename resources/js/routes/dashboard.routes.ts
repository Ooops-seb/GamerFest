import { NavItem } from '@/types';
import { Clock10Icon, Gamepad2, Globe, LayoutGrid } from 'lucide-vue-next';

// Register new routes
const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        requiredAdmin: true,
        icon: LayoutGrid,
    },
    {
        title: 'Mis Inscripciones',
        href: '/mis_inscripciones',
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
        title: 'Web',
        href: '/',
        icon: Globe,
    },
    {
        title: 'Horarios',
        href: '/pdf/horarios_gamerfest.png',
        icon: Clock10Icon,
    },
];

export { footerNavItems, mainNavItems };
