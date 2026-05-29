<x-app-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="text-8xl font-bold text-red-400 mb-4">403</div>
            <div class="text-xl font-semibold text-gray-700 mb-2">Access Denied</div>
            <p class="text-gray-500 mb-6">You don't have permission to access this page.</p>
            <a href="{{ route('dashboard') }}"
                class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Go to Dashboard
            </a>
        </div>
    </div>
</x-app-layout>
