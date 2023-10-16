<?php

namespace App\Livewire;

use App\Models\Client;
use Livewire\Component;

class ShowPost extends Component
{
    public Client $client;

    public function mount($id)
    {
        $this->client = Client::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.show-post')->layout('layouts.app');
    }
}
