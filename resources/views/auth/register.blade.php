<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Register</h2>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm">
                    <option value="buyer">Buyer</option>
                    <option value="seller">Seller</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input type="tel" name="phone_number" 
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <textarea name="address" rows="3"
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm"></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 shadow-sm" />
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                    Register
                </button>
            </div>

            <p class="text-sm text-gray-600 text-center mt-4">
                Already have an account? <a href="/register" class="text-amber-600 hover:underline">Login here</a>
            </p>
        </form>
    </div>

</body>
</html>
