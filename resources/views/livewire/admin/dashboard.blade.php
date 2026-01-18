<div class="fixed inset-0 bg-[#f8fafc] flex overflow-hidden font-sans antialiased" x-data="{ open: false }">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800&display=swap');

        .admin-dashboard {
            font-family: 'Outfit', sans-serif;
        }

        nav,
        footer {
            display: none !important;
        }

        .sidebar-border {
            border-right: 2px solid #1ABC9C;
        }

        .active-nav {
            background-color: #1ABC9C;
            color: white;
            border-color: #1ABC9C;
        }

        .inactive-nav {
            background-color: white;
            color: #1ABC9C;
            border: 1px solid #1ABC9C;
        }

        .table-header {
            border-bottom: 2px solid #1ABC9C;
        }

        .alternating-row:nth-child(even) {
            background-color: #efefef;
        }
    </style>

    {{-- Sidebar --}}
    <div class="w-72 h-full p-6">
        <div class="h-full bg-white border-2 border-[#1ABC9C] rounded-[30px] flex flex-col p-6 shadow-sm">
            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-10 px-2 mt-4">
                <img src="{{ asset('assets/logo.png') }}" class="h-12 w-auto" alt="Logo">
                <span class="font-extrabold text-[#2C3E50] text-lg tracking-tighter uppercase leading-none">Unique<br>Palette</span>
            </div>

            {{-- Navigation --}}
            <div class="space-y-4 flex-1">
                <button wire:click="setTab('home')" class="w-full py-4 px-6 rounded-2xl font-bold transition-all duration-200 text-xl {{ $activeTab === 'home' ? 'active-nav' : 'inactive-nav' }}">
                    Home
                </button>
                <button wire:click="setTab('buyers')" class="w-full py-4 px-6 rounded-2xl font-bold transition-all duration-200 text-xl {{ $activeTab === 'buyers' ? 'active-nav' : 'inactive-nav' }}">
                    Buyers
                </button>
                <button wire:click="setTab('artists')" class="w-full py-4 px-6 rounded-2xl font-bold transition-all duration-200 text-xl {{ $activeTab === 'artists' ? 'active-nav' : 'inactive-nav' }}">
                    Artists
                </button>
                <button wire:click="setTab('artworks')" class="w-full py-4 px-6 rounded-2xl font-bold transition-all duration-200 text-xl {{ $activeTab === 'artworks' ? 'active-nav' : 'inactive-nav' }}">
                    Artworks
                </button>
                <button wire:click="setTab('orders')" class="w-full py-4 px-6 rounded-2xl font-bold transition-all duration-200 text-xl {{ $activeTab === 'orders' ? 'active-nav' : 'inactive-nav' }}">
                    Orders
                </button>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" x-data>
                @csrf
                <button type="submit" class="w-full py-3 px-6 rounded-2xl font-bold text-red-500 border border-red-500 hover:bg-red-50 transition-all">
                    Logout
                </button>
            </form>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="flex-1 h-full flex flex-col p-8 pl-0 overflow-y-auto admin-dashboard">
        {{-- Top Bar --}}
        <div class="flex items-center justify-between mb-8">
            <div class="relative w-full max-w-4xl">
                <div class="flex items-center border-2 border-[#1ABC9C] rounded-full px-6 py-2 bg-white shadow-sm">
                    <input type="text" wire:model.live="search" placeholder="" class="w-full border-none focus:ring-0 outline-none shadow-none text-[#2C3E50] font-bold text-lg bg-transparent py-1">
                    <div class="text-[#2C3E50]">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <img src="{{ auth()->user()->profile_photo_url }}" class="h-16 w-16 rounded-full border-2 border-[#1ABC9C] object-cover p-0.5" alt="Admin">
            </div>
        </div>

        {{-- Content Area --}}
        <div class="bg-white rounded-3xl shadow-sm overflow-hidden flex-1 flex flex-col">
            @if($activeTab === 'home')
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-8 bg-teal-50 rounded-3xl border border-teal-100">
                    <h4 class="text-teal-600 font-bold uppercase text-xs tracking-widest mb-2">Total Buyers</h4>
                    <p class="text-4xl font-extrabold text-[#2C3E50]">{{ $items['total_buyers'] }}</p>
                </div>
                <div class="p-8 bg-teal-50 rounded-3xl border border-teal-100">
                    <h4 class="text-teal-600 font-bold uppercase text-xs tracking-widest mb-2">Total Artists</h4>
                    <p class="text-4xl font-extrabold text-[#2C3E50]">{{ $items['total_artists'] }}</p>
                </div>
                <div class="p-8 bg-teal-50 rounded-3xl border border-teal-100">
                    <h4 class="text-teal-600 font-bold uppercase text-xs tracking-widest mb-2">Total Artworks</h4>
                    <p class="text-4xl font-extrabold text-[#2C3E50]">{{ $items['total_artworks'] }}</p>
                </div>
                <div class="p-8 bg-teal-50 rounded-3xl border border-teal-100">
                    <h4 class="text-teal-600 font-bold uppercase text-xs tracking-widest mb-2">Total Orders</h4>
                    <p class="text-4xl font-extrabold text-[#2C3E50]">{{ $items['total_orders'] }}</p>
                </div>
                <div class="p-8 bg-teal-50 rounded-3xl border border-teal-100 lg:col-span-2">
                    <h4 class="text-teal-600 font-bold uppercase text-xs tracking-widest mb-2">Total Revenue</h4>
                    <p class="text-4xl font-extrabold text-[#2C3E50]">LKR {{ number_format($items['revenue'], 2) }}</p>
                </div>
            </div>
            @else
            {{-- Table container with scroll --}}
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="table-header sticky top-0 bg-white z-10">
                            @if($activeTab === 'buyers' || $activeTab === 'artists')
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Profile</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">#Id</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Full Name</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Email</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Joined Date</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Actions</th>
                            @elseif($activeTab === 'artworks')
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Image</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">#Id</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Title</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Artist</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Status</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Actions</th>
                            @elseif($activeTab === 'orders')
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Order #</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Buyer</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Total</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Status</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Date</th>
                            <th class="px-8 py-6 text-xl font-bold text-[#2C3E50]">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr class="alternating-row transition-colors">
                            @if($activeTab === 'buyers' || $activeTab === 'artists')
                            <td class="px-8 py-4">
                                <img src="{{ $item->profile_photo_url }}" class="h-16 w-16 rounded-full border-2 border-[#1ABC9C] object-cover" alt="{{ $item->name }}">
                            </td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->name }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->email }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="px-8 py-4">
                                <button class="px-6 py-2 rounded-full border border-[#1ABC9C] text-[#1ABC9C] font-bold hover:bg-teal-50 transition-all">View</button>
                            </td>
                            @elseif($activeTab === 'artworks')
                            <td class="px-8 py-4">
                                <img src="{{ $item->image_url }}" class="h-16 w-24 rounded-2xl border-2 border-[#1ABC9C] object-cover" alt="{{ $item->title }}">
                            </td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ str_pad($item->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->title }}</td>
                            <td class="px-8 py-4 font-bold text-[#1ABC9C]">{{ $item->user->name }}</td>
                            <td class="px-8 py-4 uppercase text-xs font-black {{ $item->status === 'approved' ? 'text-green-500' : ($item->status === 'pending' ? 'text-amber-500' : 'text-red-500') }}">
                                {{ $item->status }}
                            </td>
                            <td class="px-8 py-4">
                                <button wire:click="viewArtwork({{ $item->id }})" class="px-6 py-2 rounded-full border border-[#1ABC9C] text-[#1ABC9C] font-bold hover:bg-teal-50 transition-all">View</button>
                            </td>
                            @elseif($activeTab === 'orders')
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">#ORD-{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->user->name }}</td>
                            <td class="px-8 py-4 font-bold text-[#1ABC9C]">LKR {{ number_format($item->total_amount, 2) }}</td>
                            <td class="px-8 py-4 uppercase text-xs font-black">{{ $item->status }}</td>
                            <td class="px-8 py-4 font-bold text-[#2C3E50]">{{ $item->created_at->format('M d, Y') }}</td>
                            <td class="px-8 py-4">
                                <button class="px-6 py-2 rounded-full border border-[#1ABC9C] text-[#1ABC9C] font-bold hover:bg-teal-50 transition-all">View</button>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-24 text-center">
                                <p class="text-gray-400 font-bold uppercase tracking-widest">No records found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-8 border-t border-teal-50">
                {{ $items->links('components.gallery-pagination') }}
            </div>
            @endif
        </div>
    </div>

    {{-- Detail Modal (for Artworks Approval) --}}
    @if($showArtworkModal && $selectedArtwork)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#2C3E50]/40 backdrop-blur-sm">
        <div class="bg-white rounded-[40px] shadow-2xl max-w-4xl w-full flex flex-col md:flex-row overflow-hidden border-4 border-teal-50">
            <div class="md:w-1/2">
                <img src="{{ $selectedArtwork->image_url }}" class="w-full h-full object-cover" alt="{{ $selectedArtwork->title }}">
            </div>
            <div class="md:w-1/2 p-10 flex flex-col">
                <h3 class="text-3xl font-extrabold text-[#2C3E50] uppercase tracking-tighter mb-2">{{ $selectedArtwork->title }}</h3>
                <p class="text-2xl font-bold text-[#1ABC9C] mb-6">LKR {{ number_format($selectedArtwork->price, 2) }}</p>

                <div class="flex-1 space-y-6">
                    <div>
                        <h4 class="text-xs font-bold text-[#1ABC9C] uppercase tracking-widest mb-2">Description</h4>
                        <p class="text-gray-600 font-medium leading-relaxed">{{ $selectedArtwork->description }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <img src="{{ $selectedArtwork->user->profile_photo_url }}" class="h-12 w-12 rounded-full border-2 border-[#1ABC9C]" alt="Artist">
                        <div>
                            <p class="text-[10px] font-bold text-teal-600 uppercase">Artist</p>
                            <p class="text-base font-bold text-[#2C3E50] uppercase">{{ $selectedArtwork->user->name }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-gray-100 flex flex-col gap-4">
                    @if($selectedArtwork->status === 'pending')
                    <div class="grid grid-cols-2 gap-4">
                        <button wire:click="approveArtwork" class="py-4 bg-[#1ABC9C] text-white font-extrabold rounded-2xl uppercase tracking-widest text-xs hover:bg-teal-600 transition-all">Approve</button>
                        <button wire:click="rejectArtwork" class="py-4 bg-red-50 text-red-600 font-extrabold rounded-2xl border-2 border-red-100 uppercase tracking-widest text-xs hover:bg-red-600 hover:text-white transition-all">Reject</button>
                    </div>
                    @endif
                    <button wire:click="closeArtworkModal" class="py-4 text-gray-400 font-bold uppercase tracking-widest hover:text-[#1ABC9C] transition-colors text-xs">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>