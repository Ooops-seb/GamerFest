<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';

import { Users as UsersIcon, Package as PackageIcon, CreditCard as CreditCardIcon, Activity as ActivityIcon } from 'lucide-vue-next';

import InscritosPorJuegoBarChart from '@/components/dashboard/InscritosPorJuegoBarChart.vue';

interface InscripcionesDashboardData {
    ingresos_totales: number;
    numero_inscripciones_grupales: number;
    numero_inscripciones_individuales: number;
    numero_inscripciones_totales: number;
}

const page = usePage<{
    inscripciones_data?: InscripcionesDashboardData;
    total_sponsors?: number;
    total_usuarios?: number;
    ultimas_inscripciones?: Array<{
        tipo: string;
        nombre: string;
        telefono: string;
        juego: string;
        equipo?: string;
        fecha: string;
        estado_pago: string;
    }>;
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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const ultimasInscripciones = computed(() => page.props.ultimas_inscripciones ?? []);
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
            <div class="my-12">
                <InscritosPorJuegoBarChart />
            </div>

            <!-- Recent Transactions -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <CardTitle>Inscripciones Recientes</CardTitle>
                    </div>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nombre</TableHead>
                                <TableHead>Juego</TableHead>
                                <TableHead>Modalidad</TableHead>
                                <TableHead>Equipo</TableHead>
                                <TableHead>Fecha</TableHead>
                                <TableHead>Estado Pago</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="(insc, i) in ultimasInscripciones" :key="i">
                                <TableCell>{{ insc.nombre }}</TableCell>
                                <TableCell>{{ insc.juego }}</TableCell>
                                <TableCell>{{ insc.tipo }}</TableCell>
                                <TableCell>{{ insc.tipo === 'Grupal' ? insc.equipo : '-' }}</TableCell>
                                <TableCell>{{ insc.fecha }}</TableCell>
                                <TableCell>
                                    <Badge
                                        :variant="
                                            insc.estado_pago === 'verificado'
                                                ? 'default'
                                                : insc.estado_pago === 'pendiente'
                                                  ? 'secondary'
                                                  : 'destructive'
                                        "
                                    >
                                        {{ insc.estado_pago }}
                                    </Badge>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </main>
    </AppLayout>
</template>
