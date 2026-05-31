<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AA Seafood</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="bg-blue-800 p-8 text-center">
            <h1 class="text-3xl font-black text-white tracking-tighter uppercase">AA SEAFOOD</h1>
            <p class="text-blue-200 text-xs mt-1 uppercase tracking-widest font-bold">Inventory & Sales System</p>
        </div>

        <div class="p-8">
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Email Atau Alias Akun</label>
                    <input type="text" name="login" id="login" value="{{ old('login') }}" required autofocus
                        placeholder="Contoh: owner, admin, atau email lengkap"
                        class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 outline-none transition text-lg font-bold">
                </div>

                <div>
                    <label for="password" class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-2">Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full p-4 bg-gray-50 border-2 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 outline-none transition text-lg font-bold">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember" class="ml-3 text-sm font-bold text-gray-600 uppercase tracking-tighter">Ingat Saya</label>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-800 hover:bg-blue-900 text-white font-black py-5 px-6 rounded-xl text-xl shadow-lg transform active:scale-95 transition uppercase tracking-widest flex justify-center items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    Masuk Sekarang
                </button>
            </form>

            <div class="mt-8 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.25em] mb-4 text-center">Akun Default</p>
                <div class="space-y-3">
                    <div class="p-4 bg-white rounded-xl border border-slate-100">
                        <p class="text-xs font-black text-blue-600 uppercase tracking-widest mb-1">Owner</p>
                        <p class="text-sm font-bold text-slate-700">Login: owner atau owner@aaseafood.com</p>
                        <p class="text-sm font-bold text-slate-700">Password: owner123</p>
                    </div>
                    <div class="p-4 bg-white rounded-xl border border-slate-100">
                        <p class="text-xs font-black text-emerald-600 uppercase tracking-widest mb-1">Admin Gudang</p>
                        <p class="text-sm font-bold text-slate-700">Login: admin atau admin@aaseafood.com</p>
                        <p class="text-sm font-bold text-slate-700">Password: admin123</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-6 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-400 uppercase font-bold tracking-widest">AA Seafood &copy; 2026</p>
        </div>
    </div>
</body>
</html>
