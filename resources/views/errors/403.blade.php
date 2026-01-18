<x-public-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full text-center space-y-8">
            <div class="flex flex-col items-center">
                <div class="text-[#1ABC9C] font-black text-9xl mb-4 animate-bounce">403</div>
                <h2 class="mt-6 text-3xl font-black text-[#2C3E50] dark:text-white uppercase tracking-tight">Access Forbidden</h2>
                <div class="h-1.5 w-20 bg-[#1ABC9C] mx-auto mt-4 rounded-full"></div>
                <p class="mt-6 text-lg text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                    Sorry, you don't have permission to access this page. Performance of unauthorized actions is strictly restricted.
                </p>
            </div>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ url()->previous() }}" class="w-full sm:w-auto px-10 py-4 bg-white dark:bg-gray-800 border-2 border-[#1ABC9C] text-[#1ABC9C] font-black rounded-2xl uppercase tracking-widest hover:bg-teal-50 dark:hover:bg-teal-900/20 transition-all">
                    Go Back
                </a>
                <a href="{{ route('welcome') }}" class="w-full sm:w-auto px-10 py-4 bg-[#1ABC9C] text-white font-black rounded-2xl uppercase tracking-widest hover:bg-teal-600 transition-all shadow-[0_10px_30px_-10px_rgba(26,188,156,0.5)]">
                    Home Page
                </a>
            </div>
        </div>
    </div>
</x-public-layout>