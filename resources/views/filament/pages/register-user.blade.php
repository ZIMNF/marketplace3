<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
   <link rel="stylesheet" href="{{ asset('css/app.css') }}">
<script src="{{ asset('js/app.js') }}" defer></script>

</head>
<body class="bg-gray-100 dark:bg-gray-900">
    <div class="w-full min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded shadow">
            {{ $this->form }}

            <div class="mt-4">
                <button
                    wire:click="submit"
                    type="button"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Register
                </button>
            </div>

            <p class="mt-4 text-sm text-center text-gray-600 dark:text-gray-300">
                Already have an account?
                <a href="/panel/login" class="text-primary-600 underline">Login here</a>
            </p>
        </div>
    </div>

    @livewireScripts
</body>
</html>
