<?php

namespace App\Services;

use App\Contracts\Chatbot;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiChatbot implements Chatbot
{
    protected string $apiKey;

    protected string $endpoint;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->endpoint = "https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$this->apiKey}";
    }

    /**
     * Get a reply from the chatbot based on a user's message.
     */
    public function getReply(string $message): string
    {
        if (empty($this->apiKey) || $this->apiKey === 'AQUÍ_VA_TU_NUEVA_CLAVE') {
            return 'La clave de API para el servicio de chatbot no está configurada correctamente.';
        }

        try {
            $response = Http::withoutVerifying() // Solución para el error de SSL en entorno local
                ->timeout(45)
                ->post($this->endpoint, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $this->buildPrompt($message)],
                            ],
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Chatbot Service Error: '.$response->body());

                return 'Lo siento, hubo un error al contactar al servicio de IA.';
            }

            return $response->json('candidates.0.content.parts.0.text') ?? 'No pude generar una respuesta en este momento.';

        } catch (\Exception $e) {
            Log::error('Chatbot Service Exception: '.$e->getMessage());
            // Devuelve el mensaje de la excepción si es un error de cURL para más detalles
            if (str_contains($e->getMessage(), 'cURL error')) {
                return 'Error de conexión: '.$e->getMessage();
            }

            return 'Lo siento, hubo una excepción al procesar tu solicitud.';
        }
    }

    protected function buildPrompt(string $userInput): string
    {
        // Un prompt simple para darle contexto al modelo
        return "Eres un asistente de un sistema de gestión de empleados llamado SIGE. Responde de forma breve, amigable y útil a la siguiente pregunta del usuario: '{$userInput}'";
    }
}
