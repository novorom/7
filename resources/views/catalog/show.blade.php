@extends('layout')

  @php
      $parsed = $product->getParsedDescription();

      // === SEO-переменные ===
      $seoTitle = $product->seo_title ?? ($product->name . ' — купить в СПб | Дилер Cersanit');

      $autoDesc = 'Купить ' . $product->name . ' в Санкт-Петербурге по цене от '
          . number_format(round($product->price_retail * 0.8), 0, ',', ' ')
          . ' ₽/м². Со скидкой 20% от розницы. Склад Янино, доставка по СПб и ЛО.';
      $seoDescription = $product->seo_description ?? $autoDesc;

      $seoKeywords = $product->seo_keywords
          ?? implode(', ', array_filter([
              $product->name,
              $product->sku,
              $product->collection ? 'коллекция ' . $product->collection : null,
              'купить ' . ($product->product_type ?? 'плитку') . ' Cersanit',
              'Cersanit ' . $product->sku,
              'Cersanit СПб',
              'плитка Янино',
          ]));

      $canonicalUrl = route('product.show', $product->sku);

      // OG-изображение
      $ogImage = $product->main_image ?? null;
      if (!$ogImage && !empty($product->images)) {
          $imgs = is_array($product->images) ? $product->images : json_decode($product->images, true);
          $ogImage = is_array($imgs[0] ?? null) ? ($imgs[0]['url'] ?? null) : ($imgs[0] ?? null);
      }

      // BreadcrumbList JSON-LD
      $breadcrumbSchema = [
          '@context'        => 'https://schema.org',
          '@type'           => 'BreadcrumbList',
          'itemListElement' => [
              ['@type' => 'ListItem', 'position' => 1, 'item' => ['@id' => url('/'),        'name' => 'Главная']],
              ['@type' => 'ListItem', 'position' => 2, 'item' => ['@id' => url('/catalog'), 'name' => 'Каталог']],
              ['@type' => 'ListItem', 'position' => 3, 'item' => ['@id' => $canonicalUrl,   'name' => $product->name]],
          ],
      ];
  @endphp

  {{-- === SEO-секции === --}}
  @section('title', $seoTitle)
  @section('meta_description', $seoDescription)
  @section('meta_keywords', $seoKeywords)
  @section('canonical', $canonicalUrl)
  @section('og_type', 'product')
  @section('og_title', $seoTitle)
  @section('og_description', $seoDescription)
  @section('og_url', $canonicalUrl)
  @if($ogImage)
      @section('og_image', (str_starts_with($ogImage, 'http') ? $ogImage : url($ogImage)))
  @endif

  {{-- === JSON-LD: Product + BreadcrumbList === --}}
  @push('schema')
      <x-seo.product-schema :product="$product" />
      <script type="application/ld+json">
      {!! json_encode($breadcrumbSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
      </script>
  @endpush

  @section('content')
  <div class="container mx-auto px-4 py-6">
      {{-- Хлебные крошки --}}
      <nav class="text-sm text-gray-500 mb-6">
          <a href="/" class="hover:text-blue-600">Главная</a>
          <span class="mx-2">/</span>
          <a href="/catalog" class="hover:text-blue-600">Каталог</a>
          <span class="mx-2">/</span>
          <span class="text-gray-700">{{ $product->name }}</span>
      </nav>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
          {{-- Фото --}}
          <div>
              <div class="bg-white rounded-2xl shadow-sm border p-4">
                  @if($product->main_image)
                      <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full rounded-xl" loading="eager">
                  @else
                      <div class="w-full aspect-square bg-gray-100 rounded-xl flex items-center justify-center">
                          <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                          </svg>
                      </div>
                  @endif
              </div>

              {{-- Дополнительно фото из parsed --}}
              @if($parsed['collection_image'] || count($parsed['technical_images']) > 0)
                  <div class="flex gap-3 mt-4 overflow-x-auto">
                      @if($parsed['collection_image'])
                          <img src="{{ $parsed['collection_image'] }}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition"
                          onclick="this.parentElement.previousElementSibling.querySelector('img').src=this.src">
                      @endif
                      @foreach($parsed['technical_images'] as $img)
                          <img src="{{ $img }}" class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition"
                          onclick="this.parentElement.previousElementSibling.querySelector('img').src=this.src">
                      @endforeach
                  </div>
              @endif
          </div>

          {{-- Инфо --}}
          <div>
              <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
              <div class="text-gray-500 mb-4">
                  Артикул: <span class="text-gray-800 font-medium">{{ $product->sku }}</span>
              </div>

              {{-- Цена --}}
              @if($product->price_retail)
                  @php $price = round($product->price_retail * 0.8); @endphp
                  <div class="text-3xl font-bold text-gray-900 mb-4">
                      {{ number_format($price, 0, '.', ' ') }} ₽
                      @if($product->price_retail > $price)
                          <span class="text-sm text-gray-400 line-through ml-2">
                              {{ number_format($product->price_retail, 0, '.', ' ') }} ₽
                          </span>
                      @endif
                  </div>
              @endif

              {{-- Остатки --}}
              <div class="space-y-2 mb-6 text-sm">
                  @if($product->stock_factory)
                      <div class="text-green-600">
                          ● В наличии на заводе: {{ $product->stock_factory }} м²
                          <span class="text-gray-500">— доставка в СПб ~ 7 дней</span>
                      </div>
                  @endif
                  @if($product->stock_yanino)
                      <div class="text-blue-600">
                          ● В наличии в Янино: {{ $product->stock_yanino }} м²
                          <span class="text-gray-500">— самовывоз / доставка на следующий раб. день</span>
                      </div>
                  @endif
              </div>

              {{-- Кнопки --}}
              <div class="flex gap-3 mb-8">
                  <a href="https://wa.me/79052050900?text=Здравствуйте, интересует {{ $product->name }} (арт: {{ $product->sku }})"
                     target="_blank"
                     class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-xl font-medium transition">
                      Купить в WhatsApp
                  </a>
                  <a href="tel:+79052050900" class="border hover:bg-gray-50 px-6 py-3 rounded-xl font-medium transition">
                      Позвонить
                  </a>
              </div>

              {{-- Характеристики --}}
              <div class="bg-white rounded-2xl shadow-sm border p-5 space-y-2 text-sm">
                  @if($product->collection)
                      <div><b>Коллекция:</b> {{ $product->collection }}</div>
                  @endif
                  @if($product->format)
                      <div><b>Формат:</b> {{ $product->format }}</div>
                  @endif
                  @if($product->color)
                      <div><b>Цвет:</b> {{ $product->color }}</div>
                  @endif
                  @if($product->surface)
                      <div><b>Поверхность:</b> {{ $product->surface }}</div>
                  @endif
                  @if($product->material_type)
                      <div><b>Материал:</b> {{ $product->material_type }}</div>
                  @endif
              </div>
          </div>
      </div>

      {{-- Описание --}}
      @if(count($parsed['text_lines']) > 0)
          <div class="mt-10 bg-white rounded-2xl shadow-sm border p-6">
              <h2 class="text-lg font-semibold mb-3">Описание</h2>
              <div class="prose max-w-none text-sm text-gray-700">
                  @foreach($parsed['text_lines'] as $line)
                      <p class="mb-2">{{ $line }}</p>
                  @endforeach
              </div>
          </div>
      @endif
  </div>
  @endsection
