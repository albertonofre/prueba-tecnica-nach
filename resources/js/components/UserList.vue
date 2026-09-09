<template>
    <div class="panel flex min-h-0 flex-col overflow-hidden">
        <div class="flex items-center justify-between border-b border-emerald-500/10 px-5 py-4">
            <h2 class="glow-text font-semibold text-emerald-50">Usuarios</h2>
            <button
                @click="showCreateModal = true"
                class="btn-primary !px-3 !py-1.5 text-xs"
            >
                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nuevo
            </button>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto">
            <div v-if="store.loading && !store.users.length" class="p-6 text-center text-sm text-emerald-200/50">
                Cargando...
            </div>

            <div v-else>
                <TransitionGroup tag="div" name="list">
                    <button
                        v-for="user in store.users"
                        :key="user.id"
                        type="button"
                        class="flex w-full items-center gap-3 border-b border-slate-50/5 px-5 py-3.5 text-left transition"
                        :class="store.selectedUserId === user.id
                            ? 'border-l-4 border-l-emerald-400 bg-emerald-400/10'
                            : 'border-l-4 border-l-transparent hover:bg-emerald-400/5'"
                        @click="store.selectUser(user)"
                    >
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-lime-400 text-sm font-semibold text-black">
                            {{ initials(user.name) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-emerald-50">{{ user.name }}</p>
                            <p class="truncate text-xs text-emerald-200/50">{{ user.email }}</p>
                        </div>
                        <span class="shrink-0 rounded-full border border-emerald-500/20 bg-emerald-400/10 px-2.5 py-1 text-xs font-medium text-emerald-300">
                            {{ user.tasks_count ?? 0 }}
                        </span>
                    </button>
                </TransitionGroup>

                <p v-if="!store.users.length" class="p-6 text-center text-sm text-emerald-200/50">
                    No hay usuarios. Crea uno nuevo.
                </p>
            </div>
        </div>

        <Transition name="modal-fade">
            <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm" @click.self="showCreateModal = false">
                <Transition name="modal-scale" appear>
                    <div class="panel w-full max-w-md p-6 !border-emerald-400/20 glow-md">
                        <h3 class="glow-text mb-4 text-lg font-semibold text-emerald-50">Crear usuario</h3>
                        <form @submit.prevent="handleCreateUser" class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Nombre</label>
                                <input v-model.trim="form.name" type="text" required class="input-dark" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Email</label>
                                <input v-model.trim="form.email" type="email" required class="input-dark" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Contraseña</label>
                                <input v-model="form.password" type="password" minlength="8" required class="input-dark" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Confirmar contraseña</label>
                                <input v-model="form.password_confirmation" type="password" minlength="8" required class="input-dark" />
                            </div>
                            <p v-if="store.error" class="rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-300">{{ store.error }}</p>
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" @click="showCreateModal = false" class="btn-ghost">Cancelar</button>
                                <button type="submit" :disabled="store.loading" class="btn-primary">{{ store.loading ? 'Creando...' : 'Crear' }}</button>
                            </div>
                        </form>
                    </div>
                </Transition>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useDashboardStore } from '../stores/dashboard';

const store = useDashboardStore();

const showCreateModal = ref(false);
const form = ref({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const initials = (name) => name.trim().split(/\s+/).slice(0, 2).map((p) => p[0]).join('').toUpperCase();

const handleCreateUser = async () => {
    try {
        await store.createUser(form.value);
        showCreateModal.value = false;
        form.value = { name: '', email: '', password: '', password_confirmation: '' };
        await store.fetchUsers();
    } catch (error) {
        // Error mostrado desde el store.
    }
};
</script>