<template>
    <div class="flex min-h-screen bg-neutral-950">
        <div class="relative hidden lg:flex lg:w-1/2 overflow-hidden bg-neutral-950">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_80%_70%_at_20%_30%,rgba(16,185,129,0.25),transparent)]"></div>
            <div class="pointer-events-none absolute -top-24 -right-24 h-96 w-96 rounded-full bg-emerald-400/10 blur-3xl"></div>
            <div class="pointer-events-none absolute bottom-0 -left-24 h-80 w-80 rounded-full bg-lime-400/10 blur-3xl"></div>

            <div class="relative m-auto max-w-lg px-12 text-emerald-50">
                <div class="mb-6 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-400 shadow-[0_0_30px_rgba(52,211,153,0.6)]">
                    <svg class="h-7 w-7 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>

                <h2 class="glow-text text-3xl font-bold leading-tight tracking-tight">
                    Laravel + OOP + ECMAScript + MySQL
                </h2>

                <ul class="mt-8 space-y-4 text-sm text-emerald-100/70">
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Laravel (migraciones, modelos, controladores, rutas, Eloquent, servicios, Blade/Vue).
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Programación Orientada a Objetos (OOP).
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        JavaScript moderno (ES6+): async/await, módulos, imports/exports.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Consumo de APIs internas.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        Buenas prácticas de arquitectura y organización del código.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-0.5 inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-emerald-400/10 text-emerald-300">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                        </span>
                        MySQL como base de datos.
                    </li>
                </ul>
            </div>
        </div>

        <div class="flex flex-1 items-center justify-center px-6 py-12">
            <div class="w-full max-w-md">
                <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                    <div class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400 shadow-[0_0_30px_rgba(52,211,153,0.6)]">
                        <svg class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h1 class="glow-text text-xl font-bold text-emerald-50">Gestor de Tareas</h1>
                </div>

                <div class="panel p-8">
                    <div class="mb-6">
                        <h1 class="glow-text text-2xl font-bold tracking-tight text-emerald-50">Inicia sesión</h1>
                        <p class="mt-1 text-sm text-emerald-200/50">Accede para ver y gestionar las tareas de los usuarios</p>
                    </div>

                    <form @submit.prevent="handleLogin" class="space-y-5">
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-emerald-100/80">Email</label>
                            <input
                                id="email"
                                v-model.trim="form.email"
                                type="email"
                                required
                                autocomplete="email"
                                placeholder="tucorreo@ejemplo.com"
                                class="input-dark"
                            />
                        </div>

                        <div>
                            <label for="password" class="mb-1.5 block text-sm font-medium text-emerald-100/80">Contraseña</label>
                            <input
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="input-dark"
                            />
                        </div>

                        <p v-if="auth.error" class="rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-300">
                            {{ auth.error }}
                        </p>

                        <button
                            type="submit"
                            :disabled="auth.loading"
                            class="btn-primary w-full"
                        >
                            {{ auth.loading ? 'Ingresando...' : 'Iniciar sesión' }}
                        </button>
                    </form>

                    <p class="mt-6 text-center text-xs text-emerald-100/30">
                        Demo: <code class="rounded bg-emerald-400/10 px-1.5 py-0.5 text-emerald-300">admin@example.com</code> /
                        <code class="rounded bg-emerald-400/10 px-1.5 py-0.5 text-emerald-300">password</code>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router = useRouter();
const auth = useAuthStore();

const form = ref({
    email: '',
    password: '',
});

const handleLogin = async () => {
    try {
        await auth.login(form.value);
        router.push({ name: 'dashboard' });
    } catch (error) {
        // Error mostrado desde el store.
    }
};
</script>