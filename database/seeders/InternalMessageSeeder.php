<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InternalMessage;
use App\Models\User;
use Carbon\Carbon;

class InternalMessageSeeder extends Seeder
{
    /**
     * Run the database seeder.
     *
     * @return void
     */
    public function run()
    {
        // Get all users
        $users = User::all();
        
        if ($users->count() < 2) {
            $this->command->info('Need at least 2 users to create internal messages');
            return;
        }

        $messages = [
            [
                'subject' => 'Reunión de Planificación Semanal',
                'message' => 'Estimado equipo,\n\nLes convoco a la reunión de planificación semanal que se realizará el próximo lunes a las 9:00 AM en la sala de conferencias.\n\nTemas a tratar:\n- Revisión de proyectos en curso\n- Asignación de recursos\n- Cronograma de la semana\n\nPor favor confirmen su asistencia.\n\nSaludos,\nGerencia',
                'priority' => 'medium',
                'recipients' => $users->pluck('id')->take(3)->toArray(),
                'created_at' => Carbon::now()->subDays(2),
            ],
            [
                'subject' => 'URGENTE: Problema en Proyecto Villa Hermosa',
                'message' => 'ATENCIÓN INMEDIATA REQUERIDA\n\nSe ha detectado un problema estructural en el proyecto Villa Hermosa que requiere intervención inmediata.\n\nAcciones requeridas:\n1. Suspender trabajos en la zona afectada\n2. Contactar al ingeniero estructural\n3. Evaluar daños y costos\n4. Reportar al cliente\n\nPor favor confirmar recepción y acciones tomadas.\n\nUrgente.',
                'priority' => 'urgent',
                'recipients' => $users->pluck('id')->take(5)->toArray(),
                'created_at' => Carbon::now()->subHours(4),
            ],
            [
                'subject' => 'Entrega de Materiales - Proyecto Residencial Norte',
                'message' => 'Estimados,\n\nLes informo que los materiales para el proyecto Residencial Norte han llegado y están listos para su distribución.\n\nMateriales recibidos:\n- Cemento: 50 sacos\n- Varillas de acero: 200 unidades\n- Blocks: 1000 unidades\n- Arena: 10 m³\n\nPor favor coordinen la recepción con el supervisor de obra.\n\nGracias.',
                'priority' => 'low',
                'recipients' => $users->pluck('id')->take(2)->toArray(),
                'created_at' => Carbon::now()->subDays(1),
            ],
            [
                'subject' => 'Mantenimiento Programado de Equipos',
                'message' => 'Estimado equipo técnico,\n\nSe realizará mantenimiento programado a los siguientes equipos:\n\nFecha: Este sábado 15 de enero\nHora: 8:00 AM - 12:00 PM\n\nEquipos a mantener:\n- Grúa torre #1\n- Mezcladora de concreto\n- Compresores de aire\n- Generadores eléctricos\n\nDurante este período, los equipos no estarán disponibles.\n\nPor favor planifiquen sus actividades en consecuencia.',
                'priority' => 'medium',
                'recipients' => $users->pluck('id')->take(4)->toArray(),
                'created_at' => Carbon::now()->subDays(3),
            ],
            [
                'subject' => 'Felicitaciones - Proyecto Completado',
                'message' => '¡Excelente trabajo equipo!\n\nMe complace informar que el proyecto "Centro Comercial Plaza Norte" ha sido completado exitosamente y entregado al cliente.\n\nLogros destacados:\n- Entregado 2 días antes del plazo\n- Sin accidentes laborales\n- Dentro del presupuesto\n- Cliente altamente satisfecho\n\nGracias a todos por su dedicación y profesionalismo.\n\n¡Celebremos este éxito!',
                'priority' => 'low',
                'recipients' => $users->pluck('id')->toArray(),
                'created_at' => Carbon::now()->subDays(5),
                'is_broadcast' => true,
                'message_type' => 'broadcast',
            ],
            [
                'subject' => 'Capacitación en Seguridad Industrial',
                'message' => 'Estimados trabajadores,\n\nSe realizará una capacitación obligatoria en seguridad industrial:\n\nFecha: Viernes 20 de enero\nHora: 2:00 PM - 4:00 PM\nLugar: Aula de capacitación\n\nTemas:\n- Uso correcto de EPP\n- Procedimientos de emergencia\n- Manejo seguro de herramientas\n- Prevención de accidentes\n\nLa asistencia es OBLIGATORIA para todo el personal.\n\nSeguridad Industrial',
                'priority' => 'high',
                'recipients' => $users->pluck('id')->toArray(),
                'created_at' => Carbon::now()->subHours(6),
                'is_broadcast' => true,
                'message_type' => 'broadcast',
            ],
            [
                'subject' => 'Solicitud de Presupuesto - Cliente Nuevo',
                'message' => 'Estimado equipo comercial,\n\nHemos recibido una solicitud de presupuesto para un nuevo proyecto:\n\nCliente: Constructora Moderna S.A.\nProyecto: Edificio residencial de 8 pisos\nUbicación: Zona 10, Ciudad\nÁrea: 2,500 m²\n\nPor favor preparen el presupuesto detallado incluyendo:\n- Materiales\n- Mano de obra\n- Equipos\n- Cronograma\n\nFecha límite: 25 de enero\n\nGracias.',
                'priority' => 'medium',
                'recipients' => $users->pluck('id')->take(3)->toArray(),
                'created_at' => Carbon::now()->subHours(12),
            ],
            [
                'subject' => 'Actualización de Inventario',
                'message' => 'Estimado equipo de almacén,\n\nSe requiere actualización urgente del inventario de materiales.\n\nMateriales críticos con stock bajo:\n- Cemento: 5 sacos restantes\n- Varillas 3/8": 20 unidades\n- Tubería PVC 4": 15 metros\n\nPor favor:\n1. Verificar stock físico\n2. Actualizar sistema\n3. Generar orden de compra\n4. Coordinar entrega urgente\n\nReportar avances cada 2 horas.\n\nAlmacén Central',
                'priority' => 'high',
                'recipients' => $users->pluck('id')->take(2)->toArray(),
                'created_at' => Carbon::now()->subHours(8),
            ],
        ];

        foreach ($messages as $messageData) {
            // Set default values
            $messageData['sender_id'] = $users->random()->id;
            $messageData['message_type'] = $messageData['message_type'] ?? 'normal';
            $messageData['is_broadcast'] = $messageData['is_broadcast'] ?? false;
            $messageData['updated_at'] = $messageData['created_at'];

            // Simulate some messages being read
            if (rand(1, 100) > 30) { // 70% chance of being read
                $readBy = [];
                $recipients = $messageData['recipients'];
                $readCount = rand(1, count($recipients));
                $readBy = array_slice($recipients, 0, $readCount);
                $messageData['read_by'] = $readBy;
            }

            InternalMessage::create($messageData);
        }

        // Create some reply messages
        $originalMessage = InternalMessage::first();
        if ($originalMessage) {
            InternalMessage::create([
                'sender_id' => $users->where('id', '!=', $originalMessage->sender_id)->first()->id,
                'recipients' => [$originalMessage->sender_id],
                'subject' => 'RE: ' . $originalMessage->subject,
                'message' => 'Gracias por la información. Confirmo mi asistencia a la reunión.\n\nEstaré presente el lunes a las 9:00 AM.\n\nSaludos.',
                'priority' => 'low',
                'parent_message_id' => $originalMessage->id,
                'message_type' => 'reply',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ]);
        }

        $this->command->info('Internal messages seeded successfully!');
    }
}
