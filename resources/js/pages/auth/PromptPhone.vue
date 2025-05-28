<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Button } from '@/components/ui/button';
import Input from '@/components/ui/input/Input.vue';
import TextLink from '@/components/TextLink.vue';
import { LoaderCircle } from 'lucide-vue-next';
import Label from '@/components/ui/label/Label.vue';

const form = useForm({
    phone: '',
});

const submit = () => {
    form.post(route('phone.update'));
};
</script>

<template>
    <AuthLayout title="Agrega tu número de teléfono" description="Por favor ingresa tu número de teléfono para continuar.">
        <Head title="Agregar teléfono" />
        <form @submit.prevent="submit" class="space-y-6 w-full mx-auto flex flex-col">
            <div>
                <Label for="phone" class="block mb-2 text-sm font-medium">Número de teléfono</Label>
                <Input id="phone" v-model="form.phone" type="text" name="phone" maxlength="13" class="input input-bordered w-full" required />
                <div v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</div>
            </div>
            <Button :disabled="form.processing" variant="secondary" class="mx-auto cursor-pointer"
                ><LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />Guardar</Button
            >
            <TextLink :href="route('logout')" method="post" as="button" class="cursor-pointer mx-auto block text-sm"> Cerrar sesión </TextLink>
        </form>
    </AuthLayout>
</template>
