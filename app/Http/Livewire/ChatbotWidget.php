<?php

namespace App\Http\Livewire;

use App\Contracts\Chatbot;
use Livewire\Component;

class ChatbotWidget extends Component
{
    public $messages = [];

    public $newMessage = '';

    public $loading = false;

    public function mount()
    {
        $this->messages[] = ['sender' => 'bot', 'content' => '¡Hola! Soy tu asistente virtual. ¿En qué puedo ayudarte hoy?'];
    }

    public function sendMessage(Chatbot $chatbot)
    {
        if (empty($this->newMessage)) {
            return;
        }

        $this->loading = true;
        $this->messages[] = ['sender' => 'user', 'content' => $this->newMessage];
        $this->reset('newMessage');

        $reply = $chatbot->getReply(end($this->messages)['content']);

        $this->messages[] = ['sender' => 'bot', 'content' => $reply];

        $this->loading = false;
        $this->dispatchBrowserEvent('scroll-chat-to-bottom');
    }

    public function render()
    {
        return view('livewire.chatbot-widget');
    }
}
