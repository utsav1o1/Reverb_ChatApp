<?php

namespace App\Livewire;

use App\Events\MessageSendEvent;
use App\Models\Message;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class ChatComponent extends Component
{
    public $user_id;
    public $user;
    public $sender_id;
    public $receiver_id;
    public $message = '';
    public $messages = [];

    public function mount($user_id)
    {
        $this->receiver_id = $user_id;
        $this->sender_id = auth()->user()->id;
        $this->user = User::findOrFail($user_id);

        $messages = Message::where(function ($query) {
            $query->where('sender_id', $this->sender_id)
                ->where('receiver_id', $this->receiver_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->receiver_id)
                ->where('receiver_id', $this->sender_id);
        })->with('sender:id,name', 'receiver:id,name')->get();

        foreach ($messages as $message) {
            $this->appendChatDetail($message);
        }
    }

    public function sendMessage()
    {
        $chatMessage = new Message();
        $chatMessage->sender_id = $this->sender_id;
        $chatMessage->receiver_id = $this->receiver_id;
        $chatMessage->message = $this->message;
        $chatMessage->save();
        $this->appendChatDetail($chatMessage);
        broadcast(new MessageSendEvent($chatMessage))->toOthers();
        $this->message = '';
    }

    #[On('echo-private:chat-channel.{sender_id},MessageSendEvent')]
    public function listenForMessage($event) {
        $chatMessage = Message::whereId($event['message']['id'])
        ->with('sender:id,name','receiver:id,name')
        ->first();
        $this->appendChatDetail($chatMessage);
    }

    public function appendChatDetail($message)
    {
        $this->messages[] = [
            'id' => $message->id,
            'message' => $message->message,
            'sender' => $message->sender->name,
            'receiver' => $message->receiver->name,
        ];
    }
    public function render()
    {
        return view('livewire.chat-component');
    }
}
