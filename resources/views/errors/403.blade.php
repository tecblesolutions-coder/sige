<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso denegado</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8 max-w-md text-center">
        <div class="mx-auto mb-4 h-12 w-12 flex items-center justify-center rounded-full bg-red-50 text-red-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 5a7 7 0 100 14 7 7 0 000-14z" />
            </svg>
        </div>
        <h1 class="text-xl font-semibold text-gray-900 mb-2">Acceso denegado</h1>
        <p class="text-gray-600 mb-4">No tienes permisos para acceder a esta sección.</p>
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white font-semibold hover:bg-indigo-500">
            Volver al panel
        </a>
    </div>
</body>
</html>
