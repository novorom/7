<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">

 <title>@yield('title', 'CERSANIT ЯНИНО - Официальный дилер в Санкт-Петербурге')</title>
 <meta name="description" content="@yield('meta_description', 'Керамическая плитка и керамогранит Cersanit со скидкой 20% от розницы. Склад в Янино, доставка по СПб и области.')">
 @if(View::hasSection('meta_keywords'))
 <meta name="keywords" content="@yield('meta_keywords')">
 @endif
 @if(View::hasSection('canonical'))
 <link rel="canonical" href="@yield('canonical')">
 @endif

 {{-- Open Graph --}}
 <meta property="og:locale" content="ru_RU">
 <meta property="og:site_name" content="CERSANIT ЯНИНО">
 <meta property="og:type" content="@yield('og_type', 'website')">
 <meta property="og:title" content="@yield('og_title', 'CERSANIT ЯНИНО - Официальный дилер в Санкт-Петербурге')">
 <meta property="og:description" content="@yield('og_description', 'Керамическая плитка и керамогранит Cersanit со скидкой 20% от розницы.')">
 <meta property="og:url" content="@yield('og_url', url()->current())">
 @if(View::hasSection('og_image'))
 <meta property="og:image" content="@yield('og_image')">
 <meta property="og:image:width" content="800">
 <meta property="og:image:height" content="800">
 @endif

 {{-- Twitter Card --}}
 <meta name="twitter:card" content="summary_large_image">
 <meta name="twitter:title" content="@yield('og_title', 'CERSANIT ЯНИНО')">
 <meta name="twitter:description" content="@yield('og_description', 'Керамическая плитка Cersanit со скидкой 20%.')">
 @if(View::hasSection('og_image'))
 <meta name="twitter:image" content="@yield('og_image')">
 @endif

 {{-- JSON-LD схемы страницы --}}
 @stack('schema')

 <!-- Fonts -->
 <link rel="preconnect" href="https://fonts.bunny.net">
 <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

 <!-- Styles -->
 @vite('resources/css/app.css')

 {{-- SEO JSON-LD Schema for the Organization --}}
 {{-- @include('components.seo.organization-schema') --}}
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function(m,e,t,r,i,k,a){
        m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();
        for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
        k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
    })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=107050254', 'ym');

    ym(107050254, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/107050254" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter --></head>
<body class="antialiased bg-gray-50 text-gray-800">
 <header class="bg-white shadow-md sticky top-0 z-50">
 <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
 <a href="/" class="text-xl font-bold text-gray-800 hover:text-blue-600 transition-colors">🏆 CERSANIT ЯНИНО</a>
 <div class="flex items-center gap-1">
 <a href="/catalog" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg font-medium transition-colors">Каталог</a>
 <a href="https://wa.me/79052050900" target="_blank" class="text-gray-600 hover:text-green-600 px-3 py-2 rounded-lg font-medium transition-colors">WhatsApp</a>
 <a href="tel:+79052050900" class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-lg font-medium transition-colors">+7 (905) 205-09-00</a>
 </div>
 </nav>
 </header>

 <main class="min-h-screen">
 @yield('content')
 </main>

 <footer class="bg-white border-t border-gray-200 mt-12">
 <div class="container mx-auto px-4 py-8">
 <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center md:text-left">
 <div>
 <h4 class="font-semibold text-gray-900 mb-2">📍 Адрес</h4>
 <p class="text-gray-600 text-sm">Янино-1, Ленинградская область</p>
 </div>
 <div>
 <h4 class="font-semibold text-gray-900 mb-2">🕐 Режим работы</h4>
 <p class="text-gray-600 text-sm">Пн-Пт: 9:00-18:00</p>
 <p class="text-gray-600 text-sm">Сб-Вс: 10:00-16:00</p>
 </div>
 <div>
 <h4 class="font-semibold text-gray-900 mb-2">📞 Контакты</h4>
 <p class="text-gray-600 text-sm">+7 (905) 205-09-00</p>
 </div>
 </div>
 <p class="text-center text-gray-500 text-sm mt-8 pt-6 border-t border-gray-100">&copy; {{ date('Y') }} CERSANIT ЯНИНО. Все права защищены.</p>
 </div>
 </footer>

 @vite('resources/js/app.js')
 @stack('scripts')
</body>
</html>
