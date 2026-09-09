<template>
    <div class="min-h-screen bg-neutral-950">
        <header class="border-b border-emerald-500/10 bg-black/60 backdrop-blur">
            <div class="flex h-16 items-center justify-between px-6">
                <div class="flex items-center gap-3">
                    <div class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-400 shadow-[0_0_20px_rgba(52,211,153,0.6)]">
                        <svg class="h-5 w-5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs leading-tight text-emerald-200/50">Panel de administración</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-emerald-100">{{ auth.userName }}</p>
                        <p class="text-xs text-emerald-200/50">{{ auth.userEmail }}</p>
                    </div>
                    <button
                        @click="handleLogout"
                        class="btn-ghost"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Cerrar sesión
                    </button>
                </div>
            </div>
        </header>

        <main class="px-6 py-6">
            <div v-if="dashboard.error" class="mb-6 rounded-xl border border-red-500/30 bg-red-500/10 px-4 py-3 text-sm text-red-300">
                {{ dashboard.error }}
            </div>

            <div class="grid h-[calc(100vh-7rem)] grid-cols-1 gap-6 lg:grid-cols-[340px_1fr]">
                <UserList />
                <TaskList />
            </div>
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useDashboardStore } from '../stores/dashboard';
import UserList from '../components/UserList.vue';
import TaskList from '../components/TaskList.vue';

const router = useRouter();
const auth = useAuthStore();
const dashboard = useDashboardStore();

onMounted(async () => {
    await dashboard.fetchUsers();
    if (dashboard.users.length) {
        await dashboard.selectUser(dashboard.users[0]);
    }
});

const handleLogout = async () => {
    await auth.logout();
    router.push({ name: 'login' });
};
</script>