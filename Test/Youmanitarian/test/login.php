<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-7xl">

        <div class="hidden md:block md:w-1/2">
            <img src="assets/images/logo/YI_Logo.png" alt="Login Image" class="w-full h-full object-cover">
        </div>

        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <div class="text-center mb-6">
                <img src="assets/images/logo/YI_Logo.png" alt="Logo" class="w-24 h-auto mx-auto">
                <h2 class="text-xl font-bold text-gray-800 mt-2">YOUMANITARIAN INTERNATIONAL</h2>
                <p class="text-gray-600 mt-1">Fill out the information below to log in.</p>
            </div>

            <form>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-semibold">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>

                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-gray-700">
                        <input type="checkbox" id="remember" name="remember" class="mr-2">
                        Remember me
                    </label>
                    <a href="#" class="text-sm text-gray-500 hover:underline">Forgot Password?</a>
                </div>

                <button type="submit" class="w-full py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                    Log In
                </button>
            </form>
        </div>
    </div>

</body>
</html>
