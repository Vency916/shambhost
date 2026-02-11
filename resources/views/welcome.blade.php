<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>ShamHosts - Enterprise Web Hosting</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            dark: {
                                900: '#000000',
                                800: '#111111',
                                700: '#333333',
                            },
                        }
                    }
                }
            }
        </script>
        <style>
            body { font-family: 'Inter', sans-serif; background-color: #000; color: #fff; }
            .gradient-text {
                background: linear-gradient(to right, #fff, #888);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        </style>
    </head>
    <body class="antialiased min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="border-b border-white/10 backdrop-blur-md fixed w-full z-50 bg-black/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 flex items-center gap-2">
                             <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                             </div>
                             <span class="font-bold text-xl tracking-tight">ShamHosts</span>
                        </div>
                        <div class="hidden md:flex ml-10 space-x-8">
                            <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Products</a>
                            <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Solutions</a>
                            <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Resources</a>
                            <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Enterprise</a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center gap-4">
                        <a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Contact</a>
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="bg-white text-black px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition-colors">Sign Up</a>
                    </div>
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button id="mobile-menu-btn" class="text-gray-400 hover:text-white focus:outline-none p-2">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden bg-black border-b border-white/10">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Products</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Solutions</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Resources</a>
                    <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Enterprise</a>
                </div>
                <div class="pt-4 pb-4 border-t border-white/10">
                    <div class="px-2 space-y-1">
                        <a href="#" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Contact</a>
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-white/10">Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium text-white bg-white/10">Sign Up</a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow pt-24 md:pt-32 pb-12 md:pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-5xl lg:text-7xl font-extrabold tracking-tight mb-6 md:mb-8">
                    Build, Deploy,<br>
                    <span class="gradient-text">Ship globally.</span>
                </h1>
                <p class="text-base md:text-xl text-gray-400 mb-8 md:mb-10 max-w-2xl mx-auto px-4">
                    ShamHosts sets the new standard for web hosting. 
                    Deploy instantly, scale automatically, and serve your users from the edge.
                </p>
                <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16 md:mb-20 px-4">
                    <a href="#" class="bg-white text-black px-8 py-3.5 rounded-full font-semibold hover:bg-gray-200 transition-colors text-lg w-full sm:w-auto text-center">Start Deploying</a>
                    <a href="#" class="bg-white/10 border border-white/10 text-white px-8 py-3.5 rounded-full font-semibold hover:bg-white/20 transition-colors text-lg w-full sm:w-auto text-center">Get a Demo</a>
                </div>

                <!-- Feature Grid (Stats/Preview) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-left px-4 md:px-0">
                     <div class="bg-white/5 border border-white/10 rounded-xl p-6 hover:border-white/20 transition-colors">
                        <div class="h-10 w-10 bg-blue-500/20 rounded-lg flex items-center justify-center mb-4 text-blue-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Analysis</h3>
                        <p class="text-sm text-gray-400">Real-time insights into your apps performance and traffic.</p>
                     </div>
                     <div class="bg-white/5 border border-white/10 rounded-xl p-6 hover:border-white/20 transition-colors">
                        <div class="h-10 w-10 bg-purple-500/20 rounded-lg flex items-center justify-center mb-4 text-purple-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Global Edge</h3>
                        <p class="text-sm text-gray-400">Deploy your content to our global edge network in seconds.</p>
                     </div>
                     <div class="bg-white/5 border border-white/10 rounded-xl p-6 hover:border-white/20 transition-colors">
                        <div class="h-10 w-10 bg-green-500/20 rounded-lg flex items-center justify-center mb-4 text-green-400">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="font-semibold text-lg mb-2">Security</h3>
                        <p class="text-sm text-gray-400">Enterprise-grade security and DDoS protection included.</p>
                     </div>
                </div>

                <!-- Code/Deployment Preview -->
                <div class="mt-16 md:mt-24 border border-white/10 rounded-2xl bg-black overflow-hidden relative mx-4 md:mx-0">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-900/10 to-transparent pointer-events-none"></div>
                    <div class="p-4 border-b border-white/10 bg-white/5 flex items-center gap-4">
                        <div class="flex gap-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        </div>
                        <div class="text-xs text-gray-500 font-mono">vercel-integration-service</div>
                    </div>
                    <div class="p-4 md:p-8 text-left font-mono text-xs md:text-sm text-gray-300 overflow-x-auto">
                        <div class="flex"><span class="text-blue-400 mr-4">1</span><span><span class="text-purple-400">class</span> <span class="text-yellow-300">VercelOrchestrator</span> {</span></div>
                        <div class="flex"><span class="text-blue-400 mr-4">2</span><span class="ml-4"><span class="text-purple-400">public</span> <span class="text-purple-400">function</span> <span class="text-blue-300">syncEnvironmentVariables</span>(<span class="text-yellow-300">$project_id</span>, <span class="text-yellow-300">$vars</span>) {</span></div>
                        <div class="flex"><span class="text-blue-400 mr-4">3</span><span class="ml-8"><span class="text-gray-500">// Auto-Handshake with Vercel API</span></span></div>
                        <div class="flex"><span class="text-blue-400 mr-4">4</span><span class="ml-8"><span class="text-pink-400">return</span> Http::<span class="text-blue-300">withToken</span>(<span class="text-yellow-300">$this</span>->token)-><span class="text-blue-300">post</span>(...);</span></div>
                        <div class="flex"><span class="text-blue-400 mr-4">5</span><span class="ml-4">}</span></div>
                        <div class="flex"><span class="text-blue-400 mr-4">6</span><span>}</span></div>
                    </div>
                </div>

            </div>
        </main>

        <footer class="border-t border-white/10 bg-black py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4 md:gap-0">
                <div class="flex items-center gap-2">
                     <div class="w-6 h-6 bg-white rounded-full flex items-center justify-center">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                     </div>
                     <span class="font-bold text-gray-300">ShamHosts</span>
                </div>
                <div class="text-sm text-gray-500 text-center md:text-right">
                    &copy; 2024 ShamHosts Inc. All rights reserved.
                </div>
            </div>
        </footer>

        <script>
            // Simple mobile menu toggle
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');

            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        </script>
    </body>
</html>
