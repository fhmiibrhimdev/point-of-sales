<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use LivewireAlert;
    use WithPagination;

    public function render()
    {
        if( Auth::user()->hasRole('admin') )
        {
            return view('livewire.dashboard.dashboard-admin')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        } else if ( Auth::user()->hasRole('user') )
        {
            return view('livewire.dashboard.dashboard-user')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        } else if ( Auth::user()->hasRole('developer') )
        {
            return view('livewire.dashboard.dashboard-developer')
            ->extends('layouts.apps', ['title' => 'Dashboard']);
        }

    }
}
