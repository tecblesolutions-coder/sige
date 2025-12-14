<?php

namespace App\Contracts;

interface Chatbot
{
    /**
     * Get a reply from the chatbot based on a user's message.
     */
    public function getReply(string $message): string;
}
