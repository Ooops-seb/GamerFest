<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

// Importar componentes de shadcn/ui
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

// Importar iconos de Lucide
import { Users as UsersIcon, Package as PackageIcon, CreditCard as CreditCardIcon, Activity as ActivityIcon } from 'lucide-vue-next';

// Importar los nuevos componentes de gráfica
import InscritosPorJuegoBarChart from '@/components/dashboard/InscritosPorJuegoBarChart.vue';

// Tipado para los datos de inscripciones del dashboard
interface InscripcionesDashboardData {
    ingresos_totales: number;
    numero_inscripciones_grupales: number;
    numero_inscripciones_individuales: number;
    numero_inscripciones_totales: number;
}

// Obtener datos del backend (ingresos, inscripciones, etc) usando Inertia
const page = usePage<{
    inscripciones_data?: InscripcionesDashboardData;
    total_sponsors?: number;
    total_usuarios?: number;
}>();

const inscripcionesData = computed(
    () =>
        page.props.inscripciones_data ?? {
            ingresos_totales: 0,
            numero_inscripciones_grupales: 0,
            numero_inscripciones_individuales: 0,
            numero_inscripciones_totales: 0,
        },
);

const totalSponsors = computed(() => page.props.total_sponsors ?? 0);
const totalUsuarios = computed(() => page.props.total_usuarios ?? 0);

const stats = computed(() => [
    {
        title: 'Ingresos Aproximados',
        value: `$${Number(inscripcionesData.value.ingresos_totales).toLocaleString('es-ES', { minimumFractionDigits: 2 })}`,
        change: 0,
        icon: CreditCardIcon,
    },
    {
        title: 'Inscripciones Grupales',
        value: inscripcionesData.value.numero_inscripciones_grupales,
        change: 0,
        icon: UsersIcon,
    },
    {
        title: 'Inscripciones Individuales',
        value: inscripcionesData.value.numero_inscripciones_individuales,
        change: 0,
        icon: PackageIcon,
    },
    {
        title: 'Total Inscripciones',
        value: inscripcionesData.value.numero_inscripciones_totales,
        change: 0,
        icon: ActivityIcon,
    },
]);

const statsExtra = computed(() => [
    {
        title: 'Sponsors',
        value: totalSponsors.value,
        change: 0,
        icon: ActivityIcon,
    },
    {
        title: 'Usuarios Registrados',
        value: totalUsuarios.value,
        change: 0,
        icon: UsersIcon,
    },
]);

// Datos de transacciones
const transactions = ref([
    {
        id: 1,
        client: 'Ana Martínez',
        product: 'Producto Premium',
        date: '2024-01-15',
        status: 'Completado',
        amount: 1299,
    },
    {
        id: 2,
        client: 'Carlos López',
        product: 'Servicio Básico',
        date: '2024-01-14',
        status: 'Pendiente',
        amount: 599,
    },
    {
        id: 3,
        client: 'María González',
        product: 'Producto Estándar',
        date: '2024-01-14',
        status: 'Completado',
        amount: 899,
    },
    {
        id: 4,
        client: 'José Rodríguez',
        product: 'Servicio Premium',
        date: '2024-01-13',
        status: 'Cancelado',
        amount: 1599,
    },
    {
        id: 5,
        client: 'Laura Fernández',
        product: 'Producto Básico',
        date: '2024-01-13',
        status: 'Completado',
        amount: 399,
    },
]);

// Tipar parámetro status en getStatusVariant
const getStatusVariant = (status: string) => {
    switch (status) {
        case 'Completado':
            return 'default';
        case 'Pendiente':
            return 'secondary';
        case 'Cancelado':
            return 'destructive';
        default:
            return 'outline';
    }
};

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <main class="font-cinzel p-6 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                <Card v-for="stat in stats" :key="stat.title" class="hover:shadow-lg transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            {{ stat.title }}
                        </CardTitle>
                        <component :is="stat.icon" class="h-6 w-6 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">
                            {{ stat.value }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Extra Stats: Sponsors y Usuarios -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-2 mt-6">
                <Card v-for="stat in statsExtra" :key="stat.title" class="hover:shadow-lg transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium text-muted-foreground">
                            {{ stat.title }}
                        </CardTitle>
                        <component :is="stat.icon" class="h-6 w-6 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">
                            {{ stat.value }}
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Gráfica de inscritos por juego (Barra) -->
            <div class="my-8">
                <InscritosPorJuegoBarChart />
            </div>

            <!-- Recent Transactions -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Transacciones Recientes</CardTitle>
                        <Button variant="outline" size="sm">Ver todas</Button>
                    </div>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Cliente</TableHead>
                                <TableHead>Producto</TableHead>
                                <TableHead>Fecha</TableHead>
                                <TableHead>Estado</TableHead>
                                <TableHead class="text-right">Monto</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="transaction in transactions" :key="transaction.id">
                                <TableCell>
                                    <div class="flex items-center space-x-3">
                                        <Avatar class="h-8 w-8">
                                            <AvatarFallback>{{ transaction.client.charAt(0) }}</AvatarFallback>
                                        </Avatar>
                                        <span class="font-medium">{{ transaction.client }}</span>
                                    </div>
                                </TableCell>
                                <TableCell>{{ transaction.product }}</TableCell>
                                <TableCell>{{ transaction.date }}</TableCell>
                                <TableCell>
                                    <Badge :variant="getStatusVariant(transaction.status)">
                                        {{ transaction.status }}
                                    </Badge>
                                </TableCell>
                                <TableCell class="text-right font-semibold"> ${{ transaction.amount.toLocaleString() }} </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </main>
    </AppLayout>
</template>
