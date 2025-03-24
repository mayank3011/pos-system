<!DOCTYPE html>
<html lang="en">

<head>
    
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajput Book Store</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
            /* Prevents scrollbars due to animations */
        }

        /* Animated Gradient Background */
        .bg-animated {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(-45deg, #1a1a2e, #16213e, #0f3460, #e94560);
            background-size: 300% 300%;
            animation: gradientMove 8s ease infinite;
            z-index: -1;
        }

        @keyframes gradientMove {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating Orbs */
        .orb {
            position: absolute;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            filter: blur(20px);
            animation: floatOrb 10s infinite alternate ease-in-out;
        }

        .orb:nth-child(1) {
            top: 10%;
            left: 15%;
            animation-duration: 8s;
        }

        .orb:nth-child(2) {
            top: 40%;
            left: 70%;
            animation-duration: 12s;
        }

        .orb:nth-child(3) {
            top: 75%;
            left: 30%;
            animation-duration: 10s;
        }

        .orb:nth-child(4) {
            top: 20%;
            left: 85%;
            animation-duration: 15s;
        }

        @keyframes floatOrb {
            0% {
                transform: translateY(0) scale(1);
            }

            100% {
                transform: translateY(-30px) scale(1.2);
            }
        }

        /* Title Animation */
        .title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 3rem;
            background: linear-gradient(90deg, #ff416c, #8e44ad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-shadow: 0 0 5px rgba(255, 65, 108, 0.6);
            animation: float 3s infinite alternate ease-in-out;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            100% {
                transform: translateY(-10px);
            }
        }

        /* Glow effect on hover */
        .title:hover {
            text-shadow: 0 0 15px rgba(255, 65, 108, 0.9), 0 0 30px rgba(142, 68, 173, 0.9);
        }

        /* Fade-in effect */
        .fade-in {
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeIn 1s ease-in-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Buttons Bounce In */
        .btn {
            opacity: 0;
            transform: scale(0.8);
            animation: bounceIn 0.8s ease-in-out forwards;
        }

        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            60% {
                opacity: 1;
                transform: scale(1.05);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen relative">
    <!-- Animated Background -->
    <div class="bg-animated"></div>

    <!-- Floating Orbs -->
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>
    <div class="orb"></div>

    <div class="bg-gray-800 p-8 rounded-lg shadow-xl w-full max-w-md text-center border-2 border-gray-700 fade-in">
        <h1 class="title">Rajput Book Store</h1>
        <p class="text-gray-400 mt-2 text-sm font-medium">Your one-stop destination for all kinds of books</p>

        <div class="mt-6 space-y-4">
            @if (Route::has('login'))
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="block bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 rounded-lg text-lg font-bold tracking-wide uppercase transition-all duration-300 shadow-md btn">
                            Dashboard
                            </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="block bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg text-lg font-bold tracking-wide uppercase transition-all duration-300 shadow-md btn">
                            Login
                            </a>
                        
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="block bg-gray-700 hover:bg-gray-600 text-white py-3 px-4 rounded-lg text-lg font-bold tracking-wide uppercase transition-all duration-300 shadow-md btn">
                                Register
                                </a>
                        @endif
                    @endauth
                    
                </div>
                @endif
            </div>
        </div>
</body>

</html>
