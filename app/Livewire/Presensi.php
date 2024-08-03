<?php

namespace App\Livewire;

use Auth;
use Livewire\Component;
use App\Models\Schedule;

class Presensi extends Component
{
    public $latitude;
    public $longitude;
    public $insideRadius = false;

    public function render()
    {
        $insideRadius = $this->insideRadius;
        $schedule = Schedule::where('user_id', Auth::user()->id)->first();
        return view('livewire.presensi', compact('schedule', 'insideRadius'));
    }
}
