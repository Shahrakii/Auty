<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            scrollbar-width: thin;
            scrollbar-color: #4b5563 #1f2937;
        }
        *::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        *::-webkit-scrollbar-track {
            background: #1f2937;
        }
        *::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        *::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        .sidebar-resize {
            resize: horizontal;
            overflow: hidden;
        }
        .transition-all {
            transition: all 0.3s ease;
        }
        .table-row-hover:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 border-r border-gray-700 shadow-2xl flex flex-col">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-700 bg-gray-800/50 backdrop-blur">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-chart-line text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Crudly</h1>
                        <p class="text-xs text-gray-400">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-home w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Dashboard</span>
                </a>

                <a href="/posts" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group bg-gray-700/30 text-blue-400">
                    <i class="fas fa-file-alt w-5 text-blue-400"></i>
                    <span class="text-sm font-medium">Posts</span>
                    <span class="ml-auto text-xs bg-blue-500/20 text-blue-400 px-2 py-1 rounded">New</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-users w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Users</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-cube w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Products</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-inbox w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Messages</span>
                    <span class="ml-auto text-xs bg-red-500/20 text-red-400 px-2 py-1 rounded">3</span>
                </a>

                <div class="my-4 border-t border-gray-700"></div>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-chart-pie w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Analytics</span>
                </a>

                <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700/50 transition-all group">
                    <i class="fas fa-cog w-5 text-gray-500 group-hover:text-blue-400"></i>
                    <span class="text-sm font-medium">Settings</span>
                </a>
            </nav>

            <!-- User Profile -->
            <div class="p-4 border-t border-gray-700 bg-gray-800/50 backdrop-blur">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-600 hover:to-gray-700 transition-all cursor-pointer">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-white">Admin User</p>
                        <p class="text-xs text-gray-400">admin@example.com</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-500 text-xs"></i>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-950">
            <!-- Top Bar -->
            <div class="bg-gray-800/50 backdrop-blur border-b border-gray-700 shadow-lg">
                <div class="px-8 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-white">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-400 mt-1">@yield('page-subtitle', 'Welcome back to your dashboard')</p>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Search -->
                        <div class="relative hidden md:block">
                            <input type="text" placeholder="Search..." class="px-4 py-2 pl-10 rounded-lg bg-gray-700 border border-gray-600 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-500"></i>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-400 hover:text-white hover:bg-gray-700/50 rounded-lg transition-all">
                            <i class="fas fa-bell text-lg"></i>
                            <span class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full animate-pulse"></span>
                        </button>

                        <!-- Theme Toggle -->
                        <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-700/50 rounded-lg transition-all">
                            <i class="fas fa-sun text-lg"></i>
                        </button>

                        <!-- User Menu -->
                        <button class="flex items-center gap-3 p-2 hover:bg-gray-700/50 rounded-lg transition-all">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <i class="fas fa-chevron-down text-gray-500 text-xs hidden sm:block"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-auto">
                <div class="p-8">
                    <!-- Alerts -->
                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-900/30 border border-green-700/50 rounded-lg flex items-center gap-4 animate-in fade-in slide-in-from-top">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400 text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-green-300">Success</p>
                                <p class="text-sm text-green-200/80">{{ session('success') }}</p>
                            </div>
                            <button class="text-green-400 hover:text-green-300">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-900/30 border border-red-700/50 rounded-lg animate-in fade-in slide-in-from-top">
                            <p class="font-semibold text-red-300 mb-3 flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                Please fix the following errors:
                            </p>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li class="text-sm text-red-200/80 flex items-center gap-2">
                                        <i class="fas fa-arrow-right text-red-400 text-xs"></i>
                                        {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Content -->
                    <div class="bg-gray-800 rounded-xl shadow-xl border border-gray-700 overflow-hidden">
                        @yield('content')
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-800/50 border-t border-gray-700 px-8 py-4">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-400">
                        © 2024 Crudly Admin Panel. All rights reserved.
                    </p>
                    <div class="flex gap-6 text-sm text-gray-400">
                        <a href="#" class="hover:text-gray-300 transition">Privacy</a>
                        <a href="#" class="hover:text-gray-300 transition">Terms</a>
                        <a href="#" class="hover:text-gray-300 transition">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // Close alert
        document.querySelectorAll('[data-dismiss="alert"]').forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('[role="alert"]').remove();
            });
        });
    </script>
</body>
</html>