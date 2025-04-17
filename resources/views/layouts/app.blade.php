<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">


@if (!Auth::check())
    {{ Auth::logout() }}
    <script>
        window.location.href = "{{ route('login') }}";
    </script>
@endif

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>Book’n’Room </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/scss/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @if (session('status'))
            <div id="flash-message"
                class="fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-green-500 text-white p-4 rounded-sm shadow-lg z-50 transition-transform duration-500 ease-in-out">
                <div class="container mx-auto flex justify-between items-center">
                    <span>{!! session('status') !!}</span>
                    <button onclick="hideFlashMessage()" class="text-white font-bold ml-3">X</button>
                </div>
            </div>

            <script>
                // Fonction pour afficher le message avec une transition de glissement


                // Montre le message après un court délai pour l'animation
                window.onload = function() {
                    showFlashMessage();

                    // Masque le message après 5 secondes, sauf si la souris est dessus
                    let hideTimeout = setTimeout(function() {
                        hideFlashMessage();
                    }, 5000); // 5000 millisecondes = 5 secondes

                    const flashMessage = document.getElementById('flash-message');
                    flashMessage.addEventListener('mouseenter', function() {
                        clearTimeout(hideTimeout);
                    });

                    flashMessage.addEventListener('mouseleave', function() {
                        hideTimeout = setTimeout(function() {
                            hideFlashMessage();
                        }, 2000); // 2000 millisecondes = 2 secondes
                    });
                };
            </script>
        @endif
        @if (session('success'))
            <div id="flash-message"
                class="fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-green-500 text-white p-4 rounded-sm shadow-lg z-50 transition-transform duration-500 ease-in-out">
                <div class="container mx-auto flex justify-between items-center">
                    <span>{!! session('success') !!}</span>
                    <button onclick="hideFlashMessage()" class="text-white font-bold ml-3">X</button>
                </div>
            </div>

            <script>
                // Fonction pour afficher le message avec une transition de glissement


                // Montre le message après un court délai pour l'animation
                window.onload = function() {
                    showFlashMessage();

                    // Masque le message après 5 secondes, sauf si la souris est dessus
                    let hideTimeout = setTimeout(function() {
                        hideFlashMessage();
                    }, 5000); // 5000 millisecondes = 5 secondes

                    const flashMessage = document.getElementById('flash-message');
                    flashMessage.addEventListener('mouseenter', function() {
                        clearTimeout(hideTimeout);
                    });

                    flashMessage.addEventListener('mouseleave', function() {
                        hideTimeout = setTimeout(function() {
                            hideFlashMessage();
                        }, 2000); // 2000 millisecondes = 2 secondes
                    });
                };
            </script>
        @endif

        @if (session('error'))
            <div
                class="fixed top-0 left-1/2 transform -translate-x-1/2 bg-red-500 text-white p-4 rounded-sm shadow-lg z-50 mt-4">
                <div class="container mx-auto flex justify-between items-center">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.parentElement.remove()"
                        class="text-white font-bold ml-3">X</button>
                </div>
            </div>
        @endif
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow-sm flex ">
                <a href="{{ url()->previous() }}" onclick="window.history.go(-1); return false;"
                    class="flex px-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                    <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
                </a>
                <div class="w-5/6 ml-0 py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset
        @isset($header_nav)
            <header class="bg-white dark:bg-gray-800 shadow-sm flex ">
                <a href="{{ url()->previous() }}" onclick="window.history.go(-1); return false;"
                    class="flex p-4 sm:p-6 lg:p-8 px-1.5 hover:bg-gray-100 dark:hover:bg-gray-700 items-center">
                    <x-icon size="1" type="arrow_back" class="fill-gray-500 dark:fill-gray-300" />
                </a>
                <div class="w-5/6 ml-0 mt-10 px-4 sm:px-6 lg:px-8">
                    {{ $header_nav }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
    <script>
        function updateDateInputs(input) {
            // Parcourir chaque input
            // Vérifier si la valeur correspond au format 00XX-XX-XX
            const regex = /^00(\d{2})-(\d{2})-(\d{2})$/;
            if (regex.test(input.value)) {
                // Remplacer 00 par 20 dans l'année
                input.value = input.value.replace(regex, '20$1-$2-$3');
            }
        }

        function copyToClipboard(text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopiedMessage();
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showCopiedMessage();
                } catch (err) {
                    console.error('Could not copy text: ', err);
                }
                document.body.removeChild(textArea);
            }
        }

        function showCopiedMessage() {
            // Affiche un message temporaire pour indiquer que le texte a été copié
            const flashMessage = document.createElement('div');
            flashMessage.id = 'flash-message';
            flashMessage.className =
                'fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-green-500 text-white p-4 rounded-sm shadow-lg z-50 transition-transform duration-500 ease-in-out';
            flashMessage.innerHTML = `
                <div class="container mx-auto flex justify-between items-center">
                    <span>Texte copié</span>
                    <button onclick="hideFlashMessage()" class="text-white font-bold ml-3">X</button>
                </div>
            `;
            document.body.appendChild(flashMessage);
            setTimeout(function() {
                showFlashMessage();
            }, 100);
            setTimeout(function() {
                hideFlashMessage();
            }, 1000);
        }

        function showFlashMessage() {
            const flashMessage = document.getElementById('flash-message');
            flashMessage.classList.remove('-translate-y-full'); // Enlève la classe pour montrer l'élément
            flashMessage.classList.add('translate-y-0'); // Ajoute la classe pour faire le glissement
            flashMessage.classList.add('shadow-lg'); // Ajoute une ombre pour le rendre plus visible
        }

        // Fonction pour masquer le message avec une transition
        function hideFlashMessage() {
            const flashMessage = document.getElementById('flash-message');
            flashMessage.classList.remove('translate-y-0'); // Enlève la classe pour cacher l'élément
            flashMessage.classList.add('-translate-y-full'); // Ajoute la classe pour remonter le message
            flashMessage.classList.remove('shadow-lg'); // Enlève l'ombre pour le rendre moins visible
            setTimeout(function() {
                document.querySelectorAll('#flash-message').forEach(function(element) {
                    element.remove();
                });
            }, 500); // Délai pour permettre la transition avant de supprimer l'élément

        }

        function showFlashMessageFromJs(contenu, duree = 2000, type = 'success') {
            // Affiche un message temporaire pour indiquer que le texte a été copié
            const flashMessage = document.createElement('div');
            flashMessage.id = 'flash-message';
            if (type === 'error') {
                flashMessage.className =
                    'fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-red-500 text-white p-4 rounded-sm shadow-lg z-50 transition-transform duration-500 ease-in-out';
            } else if (type === 'success') {
                flashMessage.className =
                    'fixed top-0 left-1/2 transform -translate-x-1/2 -translate-y-full bg-green-500 text-white p-4 rounded-sm shadow-lg z-50 transition-transform duration-500 ease-in-out';
            }
            flashMessage.innerHTML = `
                <div class="container mx-auto flex justify-between items-center">
                    <span>${contenu}</span>
                    <button onclick="hideFlashMessage()" class="text-white font-bold ml-3">X</button>
                </div>
            `;
            document.body.appendChild(flashMessage);
            setTimeout(function() {
                showFlashMessage();
            }, 100);
            setTimeout(function() {
                hideFlashMessage();
            }, duree);
        }
    </script>


</body>

</html>
