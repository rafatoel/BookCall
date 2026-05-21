<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class CallMetaData extends Component
{
    public $durations = [];
    public $selectedDuration;
    public $name, $email, $title, $description;

    public function onChange($propertyName, $value)
    {
        $this->dispatch('inputChange', $propertyName, $value);
    }

    public function mount()
    {
        // Ensure durations are available
        $this->durations = session('durations') ?? [];
        if (!empty($this->durations)) {
            $this->selectedDuration = $this->durations[0];
        }
        // $this->title = $data['title'];
        // $this->selectedDuration = $data['duration'];

    }

    public function durationChange($value)
    {
        // Optionally handle logic here when selectedDuration changes
        $this->selectedDuration = $value;
        $this->dispatch('durationChanged', $value);
        $this->dispatch('inputChange', 'duration', $this->selectedDuration);
    }


    public function render()
    {
        return view('livewire.call-meta-data');
    }
}
