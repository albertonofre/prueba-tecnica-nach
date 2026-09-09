<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'María García',
                'email' => 'maria.garcia@example.com',
                'tasks' => [
                    ['title' => 'Preparar el informe mensual de ventas', 'description' => 'Revisar los datos del último trimestre y generar el resumen ejecutivo.', 'completed' => false],
                    ['title' => 'Comprar suministros para la oficina', 'description' => 'Papel, tinta y carpetas para el área de administración.', 'completed' => false],
                    ['title' => 'Agendar reunión con el equipo de diseño', 'description' => 'Confirmar la sala y enviar las invitaciones por correo.', 'completed' => true],
                    ['title' => 'Actualizar base de datos de clientes', 'description' => 'Depurar los registros duplicados y normalizar los teléfonos.', 'completed' => true],
                ],
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos.lopez@example.com',
                'tasks' => [
                    ['title' => 'Implementar autenticación con tokens', 'description' => 'Usar Sanctum y actualizar la documentación de la API.', 'completed' => false],
                    ['title' => 'Revisar los pull requests pendientes', 'description' => 'Comprobar el código y resolver los comentarios antes del merge.', 'completed' => false],
                    ['title' => 'Escribir documentación técnica del módulo de pagos', 'description' => '', 'completed' => true],
                    ['title' => 'Configurar el entorno de pruebas', 'description' => 'Instalar las dependencias y verificar la suite completa.', 'completed' => true],
                ],
            ],
            [
                'name' => 'Lucía Fernández',
                'email' => 'lucia.fernandez@example.com',
                'tasks' => [
                    ['title' => 'Diseñar la nueva página de inicio', 'description' => 'Preparar maquetas en alta fidelidad para aprobación.', 'completed' => false],
                    ['title' => 'Preparar la propuesta para el cliente Acme', 'description' => 'Incluir cronograma y presupuesto estimado.', 'completed' => false],
                    ['title' => 'Organizar taller de onboarding para nuevos empleados', 'description' => 'Reservar el auditorio y preparar el material de bienvenida.', 'completed' => true],
                    ['title' => 'Revisar accesibilidad de la interfaz', 'description' => 'Verificar contraste y navegación con teclado.', 'completed' => true],
                ],
            ],
        ];

        foreach ($users as $userData) {
            $tasks = $userData['tasks'];
            unset($userData['tasks']);

            $user = User::factory()->create($userData);

            foreach ($tasks as $taskData) {
                Task::factory()->create([
                    'user_id' => $user->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'completed' => $taskData['completed'],
                ]);
            }
        }

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminTasks = [
            ['title' => 'Instalar y configurar el servidor de producción', 'description' => 'Aplicar los parches de seguridad y activar HTTPS.', 'completed' => false],
            ['title' => 'Migrar la base de datos a la nueva versión', 'description' => 'Programar la ventana de mantenimiento para el fin de semana.', 'completed' => false],
            ['title' => 'Programar el respaldo automático semanal', 'description' => 'Verificar que los backups se ejecuten cada domingo a las 3 a.m.', 'completed' => true],
            ['title' => 'Monitorear el rendimiento de la aplicación', 'description' => 'Revisar los logs y optimizar las consultas lentas.', 'completed' => true],
            ['title' => 'Preparar la nota del lanzamiento v1.2', 'description' => 'Resumir las novedades y los cambios importantes para el equipo.', 'completed' => true],
        ];

        foreach ($adminTasks as $taskData) {
            Task::factory()->create([
                'user_id' => $admin->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'completed' => $taskData['completed'],
            ]);
        }
    }
}