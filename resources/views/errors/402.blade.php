<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] border border-slate-100 p-8 text-center relative overflow-hidden">
        
        <!-- Decorative background ring -->
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-rose-50 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

        <div class="relative w-24 h-24 mx-auto mb-6 bg-rose-50 rounded-2xl flex items-center justify-center border-2 border-dashed border-rose-200">
            <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        
        <h1 class="text-2xl font-extrabold text-slate-800 mb-2 tracking-tight">Access Suspended</h1>
        <p class="text-slate-500 font-medium mb-8">
            Your company's subscription has expired. Please contact your company administrator to renew your plan and restore access.
        </p>
        
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg shadow-slate-900/20 transition-all hover:-translate-y-0.5 outline-none focus:ring-2 focus:ring-slate-900/50">
                Sign Out / Return Home
            </button>
        </form>
        
    </div>
</body>
</html>
