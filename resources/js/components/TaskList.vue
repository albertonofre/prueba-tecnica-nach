<template>
    <div class="panel flex min-h-0 flex-col overflow-hidden">
        <div class="border-b border-emerald-500/10 px-5 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="glow-text font-semibold text-emerald-50">
                        {{ store.selectedUser ? `Tareas de ${store.selectedUser.name}` : 'Tareas' }}
                    </h2>
                    <p class="mt-0.5 text-xs text-emerald-200/50">{{ store.tasks.length }} {{ store.tasks.length === 1 ? 'tarea' : 'tareas' }}</p>
                </div>
                <button
                    @click="openCreateModal"
                    :disabled="!store.selectedUser"
                    class="btn-primary !px-3 !py-1.5 text-xs"
                >
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Nueva tarea
                </button>
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-2">
                <button
                    @click="setCompleted('all')"
                    class="chip"
                    :class="store.filters.completed === null ? 'bg-emerald-400 text-black shadow-[0_0_16px_rgba(52,211,153,0.5)]' : 'border border-emerald-500/20 bg-emerald-400/5 text-emerald-200 hover:bg-emerald-400/10'"
                >
                    Todas
                </button>
                <button
                    @click="setCompleted(false)"
                    class="chip"
                    :class="store.filters.completed === false ? 'bg-amber-400 text-black shadow-[0_0_16px_rgba(251,191,36,0.4)]' : 'border border-emerald-500/20 bg-emerald-400/5 text-emerald-200 hover:bg-emerald-400/10'"
                >
                    Pendientes
                </button>
                <button
                    @click="setCompleted(true)"
                    class="chip"
                    :class="store.filters.completed === true ? 'bg-emerald-300 text-black shadow-[0_0_16px_rgba(52,211,153,0.5)]' : 'border border-emerald-500/20 bg-emerald-400/5 text-emerald-200 hover:bg-emerald-400/10'"
                >
                    Completadas
                </button>

                <span class="mx-1 hidden h-5 w-px bg-emerald-500/15 sm:block"></span>

                <span class="text-xs text-emerald-200/50">Ordenar:</span>
                <button
                    @click="setOrder('title')"
                    class="chip"
                    :class="store.filters.orderBy === 'title' ? 'bg-emerald-400 text-black shadow-[0_0_16px_rgba(52,211,153,0.5)]' : 'border border-emerald-500/20 bg-emerald-400/5 text-emerald-200 hover:bg-emerald-400/10'"
                >
                    Por título
                    <span class="text-[10px] leading-none">{{ directionLabel('title') }}</span>
                </button>
                <button
                    @click="setOrder('created_at')"
                    class="chip"
                    :class="store.filters.orderBy === 'created_at' ? 'bg-emerald-400 text-black shadow-[0_0_16px_rgba(52,211,153,0.5)]' : 'border border-emerald-500/20 bg-emerald-400/5 text-emerald-200 hover:bg-emerald-400/10'"
                >
                    Por fecha
                    <span class="text-[10px] leading-none">{{ directionLabel('created_at') }}</span>
                </button>
            </div>
        </div>

        <div class="min-h-0 flex-1 overflow-y-auto">
            <div v-if="store.loading && !store.tasks.length" class="p-6 text-center text-sm text-emerald-200/50">Cargando...</div>

            <TransitionGroup v-else-if="store.tasks.length" tag="div" name="list" class="divide-y divide-emerald-500/10">
                <div v-for="task in store.tasks" :key="task.id" class="group flex items-start gap-3 px-5 py-4">
                    <button
                        @click="toggleComplete(task)"
                        class="mt-0.5 flex h-6 w-6 shrink-0 items-center justify-center rounded-full border-2 transition"
                        :class="task.completed ? 'border-emerald-400 bg-emerald-400 shadow-[0_0_14px_rgba(52,211,153,0.6)]' : 'border-emerald-500/30 hover:border-emerald-400'"
                        :aria-label="task.completed ? 'Marcar como pendiente' : 'Marcar como completada'"
                    >
                        <svg v-if="task.completed" class="h-3.5 w-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold" :class="task.completed ? 'text-emerald-200/40 line-through' : 'text-emerald-50'">{{ task.title }}</p>
                        <p v-if="task.description" class="mt-0.5 text-sm" :class="task.completed ? 'text-emerald-200/30 line-through' : 'text-emerald-200/60'">{{ task.description }}</p>
                        <div class="mt-2 flex items-center gap-3 text-xs text-emerald-200/40">
                            <span>Creada: {{ formatDate(task.created_at) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 opacity-0 transition group-hover:opacity-100">
                        <button
                            @click="openEditModal(task)"
                            class="rounded-lg p-2 text-emerald-200/40 transition hover:bg-emerald-400/10 hover:text-emerald-300"
                            title="Editar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="handleDelete(task)"
                            class="rounded-lg p-2 text-emerald-200/40 transition hover:bg-red-500/10 hover:text-red-400"
                            title="Eliminar"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </TransitionGroup>

            <div v-else class="p-10 text-center">
                <svg class="mx-auto mb-3 h-12 w-12 text-emerald-200/20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-sm text-emerald-200/50">Sin tareas para este usuario.</p>
            </div>
        </div>

        <Transition name="modal-fade">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4 backdrop-blur-sm" @click.self="showModal = false">
                <Transition name="modal-scale" appear>
                    <div class="panel w-full max-w-md p-6 !border-emerald-400/20 glow-md">
                        <h3 class="glow-text mb-4 text-lg font-semibold text-emerald-50">{{ editingTask ? 'Editar tarea' : 'Nueva tarea' }}</h3>
                        <form @submit.prevent="handleSaveTask" class="space-y-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Título</label>
                                <input v-model.trim="form.title" type="text" required class="input-dark" />
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-emerald-100/80">Descripción</label>
                                <textarea v-model.trim="form.description" rows="3" class="input-dark resize-none"></textarea>
                            </div>
                            <p v-if="store.error" class="rounded-lg border border-red-500/30 bg-red-500/10 px-3 py-2 text-sm text-red-300">{{ store.error }}</p>
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" @click="showModal = false" class="btn-ghost">Cancelar</button>
                                <button type="submit" :disabled="store.loading" class="btn-primary">{{ store.loading ? 'Guardando...' : 'Guardar' }}</button>
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

const showModal = ref(false);
const editingTask = ref(null);
const form = ref({
    title: '',
    description: '',
});

const openCreateModal = () => {
    editingTask.value = null;
    form.value = { title: '', description: '' };
    showModal.value = true;
};

const openEditModal = (task) => {
    editingTask.value = task;
    form.value = { title: task.title, description: task.description ?? '' };
    showModal.value = true;
};

const handleSaveTask = async () => {
    try {
        if (editingTask.value) {
            await store.updateTask(editingTask.value.id, form.value);
        } else {
            await store.createTask(form.value);
        }
        showModal.value = false;
    } catch (error) {
        // Error mostrado desde el store.
    }
};

const toggleComplete = async (task) => {
    try {
        if (task.completed) {
            await store.updateTask(task.id, { completed: false });
        } else {
            await store.completeTask(task.id);
        }
    } catch (error) {
        // Error mostrado desde el store.
    }
};

const handleDelete = async (task) => {
    if (!window.confirm(`¿Eliminar la tarea "${task.title}"?`)) return;
    try {
        await store.deleteTask(task.id);
    } catch (error) {
        // Error mostrado desde el store.
    }
};

const setCompleted = (value) => {
    store.setFilter('completed', value);
};

const setOrder = (field) => {
    const { orderBy, orderDirection } = store.filters;
    store.filters.orderBy = field;
    store.filters.orderDirection = orderBy === field && orderDirection === 'asc' ? 'desc' : 'asc';
    store.fetchTasks();
};

const directionLabel = (field) => {
    if (store.filters.orderBy !== field) return '';
    return store.filters.orderDirection === 'asc' ? '↑' : '↓';
};

const formatDate = (dateString) => new Date(dateString).toLocaleDateString('es-ES', { day: 'numeric', month: 'short', year: 'numeric' });
</script>