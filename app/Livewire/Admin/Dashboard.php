<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Artwork;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public $activeTab = 'home'; // Default tab
    public $selectedArtwork = null;
    public $showArtworkModal = false;
    public $search = '';

    public function mount()
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            abort(403);
        }

        $routeName = request()->route()->getName();
        if ($routeName === 'admin.users') {
            $this->activeTab = 'buyers';
        } elseif ($routeName === 'admin.artworks') {
            $this->activeTab = 'artworks';
        } elseif ($routeName === 'admin.orders') {
            $this->activeTab = 'orders';
        }
    }

    protected $queryString = ['activeTab'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function viewArtwork($id)
    {
        $this->selectedArtwork = Artwork::with('user')->find($id);
        $this->showArtworkModal = true;
    }

    public function closeArtworkModal()
    {
        $this->showArtworkModal = false;
        $this->selectedArtwork = null;
    }

    public function approveArtwork()
    {
        if ($this->selectedArtwork && $this->selectedArtwork->status === 'pending') {
            $this->selectedArtwork->update(['status' => 'approved']);
            session()->flash('message', 'Artwork approved successfully.');
            $this->closeArtworkModal();
        }
    }

    public function rejectArtwork()
    {
        if ($this->selectedArtwork && $this->selectedArtwork->status === 'pending') {
            $this->selectedArtwork->update(['status' => 'rejected']);
            session()->flash('message', 'Artwork rejected.');
            $this->closeArtworkModal();
        }
    }

    public function render()
    {
        $data = [];

        switch ($this->activeTab) {
            case 'home':
                $data = [
                    'total_buyers' => User::where('role', 'buyer')->count(),
                    'total_artists' => User::where('role', 'artist')->count(),
                    'total_artworks' => Artwork::count(),
                    'total_orders' => Order::count(),
                    'revenue' => Order::where('status', 'completed')->sum('total_amount'),
                ];
                break;
            case 'buyers':
                $data = User::where('role', 'buyer')
                    ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->latest()->paginate(10);
                break;
            case 'artists':
                $data = User::where('role', 'artist')
                    ->when($this->search, function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->latest()->paginate(10);
                break;
            case 'artworks':
                $data = Artwork::with('user')
                    ->when($this->search, function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->latest()->paginate(10);
                break;
            case 'orders':
                $data = Order::with(['user', 'items.artwork'])
                    ->when($this->search, function ($query) {
                        $query->where('id', 'like', '%' . $this->search . '%')
                            ->orWhereHas('user', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            });
                    })
                    ->latest()->paginate(10);
                break;
        }

        return view('livewire.admin.dashboard', [
            'items' => $data
        ])->layout('layouts.app');
    }
}
