<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full mx-auto px-6">

        {{-- Logo / Title --}}
        <div class="text-center mb-8">
            <div class="text-5xl mb-4">📦</div>
            <h1 class="text-3xl font-bold text-gray-800">Inventory Manager</h1>
            <p class="text-gray-500 mt-2 text-sm">Manage your stock, suppliers, and reports — all in one place.</p>
        </div>

        {{-- Card --}}
        <div class="bg-white shadow-lg rounded-2xl p-8">

            <div class="space-y-3">
                <a href="{{ route('login') }}"
                   class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700 transition">
                    Log In
                </a>
                <a href="{{ route('register') }}"
                   class="block w-full text-center border border-indigo-600 text-indigo-600 py-3 rounded-lg font-medium hover:bg-indigo-50 transition">
                    Register
                </a>
            </div>

            {{-- Features --}}
            <div class="mt-8 pt-6 border-t border-gray-100 space-y-3">
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Role-based access — Admin & Staff
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Real-time product search
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Stock In / Stock Out tracking
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> Low stock alerts on dashboard
                </div>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <span class="text-green-500">✓</span> CSV export for reports
                </div>
            </div>

        </div>

        <p class="text-center text-xs text-gray-400 mt-6">
            Built with Laravel 12 · PostgreSQL · Tailwind CSS
        </p>

    </div>

</body>
</html>