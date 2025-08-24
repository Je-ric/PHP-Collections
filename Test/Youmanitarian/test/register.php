<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration | Youmanitarian</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="flex bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-5xl">
        <div class="hidden md:block md:w-1/2">
            <img src="assets/images/logo/YI_Logo.png" alt="Login Image" class="w-full h-full object-cover">
        </div>

        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <h2 class="text-center text-xl font-bold mb-4">YOUMANITARIAN INTERNATIONAL</h2>
            <p class="text-center mb-4">Fill out the information to create an account.</p>

            <form>
                <div class="mb-4">
                    <label for="full-name" class="block text-gray-700 font-semibold">Full Name</label>
                    <input type="text" id="full-name" name="full-name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="age" class="block text-gray-700 font-semibold">Age</label>
                        <input type="number" id="age" name="age" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="sex" class="block text-gray-700 font-semibold">Sex</label>
                        <select id="sex" name="sex" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="email" class="block text-gray-700 font-semibold">Email</label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                    <div>
                        <label for="mobile-number" class="block text-gray-700 font-semibold">Mobile Number</label>
                        <input type="text" id="mobile-number" name="mobile-number" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>
                <div class="mb-4">
                    <label for="confirm-password" class="block text-gray-700 font-semibold">Confirm Password</label>
                    <input type="password" id="confirm-password" name="confirm-password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-gray-500">
                </div>

                <button type="submit" class="w-full py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 transition">
                    Register
                </button>
            </form>
        </div>
    </div>

</body>
</html>
