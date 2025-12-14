<?php

namespace App\Services;

use App\Contracts\Chatbot;

class RuleBasedChatbot implements Chatbot
{
    protected $rules = [
        // Saludos
        'hola' => '¡Hola! ¿En qué puedo ayudarte hoy?',
        'buenos dias' => '¡Buenos días! ¿Qué necesitas?',
        'buenas tardes' => '¡Buenas tardes! ¿En qué te puedo asistir?',

        // Ayuda y sistema
        'ayuda' => 'Estoy aquí para ayudarte con el uso del sistema SIGE. Puedes preguntarme sobre funcionalidades como "empleados", "asistencia" o "reportes".',
        'info' => 'Estoy aquí para ayudarte con el uso del sistema SIGE. Puedes preguntarme sobre funcionalidades como "empleados", "asistencia" o "reportes".',
        'sistema' => 'Este es un Sistema de Gestión de Empleados (SIGE) que te ayuda a administrar personal, asistencias y reportes.',
        'sige' => 'Este es un Sistema de Gestión de Empleados (SIGE) que te ayuda a administrar personal, asistencias y reportes.',

        // Contraseña
        'contraseña' => 'Para cambiar tu contraseña, ve a tu perfil (haciendo clic en tu nombre arriba a la derecha) y selecciona la opción "Perfil".',
        'password' => 'Para cambiar tu contraseña, ve a tu perfil (haciendo clic en tu nombre arriba a la derecha) y selecciona la opción "Perfil".',
        'clave' => 'Para cambiar tu contraseña, ve a tu perfil (haciendo clic en tu nombre arriba a la derecha) y selecciona la opción "Perfil".',
        'acceso' => 'Para cambiar tu contraseña, ve a tu perfil (haciendo clic en tu nombre arriba a la derecha) y selecciona la opción "Perfil".',

        // Empleados
        'empleados' => 'En la sección de Empleados puedes ver, añadir, editar y eliminar registros de personal.',
        'personal' => 'En la sección de Empleados puedes ver, añadir, editar y eliminar registros de personal.',

        // Asistencia
        'asistencia' => 'En la sección "Mi Asistencia" puedes marcar tu propia entrada y salida. Los administradores pueden gestionar la asistencia de todos en la sección "Asistencias".',
        'entrada' => 'Puedes marcar tu entrada en la página "Mi Asistencia".',
        'salida' => 'Puedes marcar tu salida en la página "Mi Asistencia".',
        'marcar' => 'Puedes marcar tu entrada y salida en la página "Mi Asistencia".',

        // Reportes
        'reportes' => 'En la sección de Reportes puedes generar informes sobre empleados y asistencias, exportándolos a Excel o PDF.',
        'informes' => 'En la sección de Reportes puedes generar informes sobre empleados y asistencias, exportándolos a Excel o PDF.',

        // Despedidas
        'adios' => '¡Hasta luego! Si necesitas algo más, no dudes en preguntar.',
        'gracias' => '¡De nada! Estoy para ayudarte.',
        'salir' => '¡Hasta luego! Si necesitas algo más, no dudes en preguntar.',

        // Default
        'default' => 'Lo siento, no entiendo tu pregunta. Por favor, sé más específico o pregunta sobre temas como "empleados", "asistencia", "reportes" o "contraseña".',
    ];

    /**
     * Get a reply from the chatbot based on a user's message.
     */
    public function getReply(string $message): string
    {
        $lowerMessage = mb_strtolower($message, 'UTF-8');

        // 1. First pass: Direct keyword matching (fast and accurate)
        foreach ($this->rules as $keyword => $reply) {
            if ($keyword === 'default') {
                continue;
            }

            // Use word boundaries `\b` to match whole words only
            if (preg_match("/\b".preg_quote($keyword, '/')."\b/i", $lowerMessage)) {
                return $reply;
            }
        }

        // 2. Second pass: Levenshtein distance for typo correction (slower fallback)
        $inputWords = preg_split('/[\s,;.-¿?¡!]+/', $lowerMessage, -1, PREG_SPLIT_NO_EMPTY);
        if (empty($inputWords)) {
            return $this->rules['default'];
        }

        $bestMatchKeyword = null;
        $minDistance = PHP_INT_MAX;

        foreach (array_keys($this->rules) as $keyword) {
            if ($keyword === 'default') {
                continue;
            }

            foreach ($inputWords as $inputWord) {
                $distance = levenshtein($keyword, $inputWord);

                // Exact match is best
                if ($distance === 0) {
                    return $this->rules[$keyword];
                }

                if ($distance < $minDistance) {
                    $minDistance = $distance;
                    $bestMatchKeyword = $keyword;
                }
            }
        }

        if ($bestMatchKeyword) {
            $threshold = max(1, floor(strlen($bestMatchKeyword) / 4));
            $threshold = min($threshold, 2);

            if ($minDistance <= $threshold) {
                return $this->rules[$bestMatchKeyword];
            }
        }

        return $this->rules['default'];
    }
}
