import type { Metadata } from "next"
import Link from "next/link"
import { ChevronRight } from "lucide-react"
import { products } from "@/lib/products-data"
import { ProductCard } from "@/components/product-card"

interface CollectionPageProps {
  params: Promise<{ collection: string }>
}

export async function generateStaticParams() {
  const allCollectionNames = [
    ...new Set(
      products
        .filter((p) => p.collection && p.collection.trim() && p.collection.toLowerCase() !== "other")
        .map((p) => p.collection as string)
    ),
  ]

  return allCollectionNames.map((name) => ({
    collection: name.toLowerCase().replace(/\s+/g, "-").replace(/[^a-zа-яё0-9-]/gi, ""),
  }))
}

export async function generateMetadata({
  params,
}: CollectionPageProps): Promise<Metadata> {
  const { collection } = await params

  // Decode the collection slug back to name
  const decodedName = decodeURIComponent(collection)
    .replace(/-/g, " ")
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
    .join(" ")

  const collectionProducts = products.filter((p) =>
    p.collection?.toLowerCase().replace(/\s+/g, "-").replace(/[^a-zа-яё0-9-]/gi, "") ===
    collection.toLowerCase()
  )

  const title = `Плитка ${decodedName} Cersanit купить в Санкт-Петербурге`
  const description = `Коллекция ${decodedName} -- ${collectionProducts.length} товаров в наличии на складе Янино. Керамическая плитка и керамогранит Cersanit. Доставка по СПб и ЛО.`
  const firstImage = collectionProducts[0]?.main_image || collectionProducts[0]?.collection_image

  return {
    title,
    description,
    alternates: { canonical: `/collections/${collection}` },
    openGraph: {
      title,
      description,
      url: `https://cersanit-spb.ru/collections/${collection}`,
      siteName: "Дом Плитки CERSANIT",
      locale: "ru_RU",
      type: "website",
      images: firstImage ? [{ url: firstImage, alt: decodedName }] : [],
    },
  }
}

export default async function CollectionPage({
  params,
}: CollectionPageProps) {
  const { collection } = await params

  // Find actual collection name by matching slug
  const collectionName = [
    ...new Set(
      products
        .filter((p) => p.collection && p.collection.trim() && p.collection.toLowerCase() !== "other")
        .map((p) => p.collection as string)
    ),
  ].find(
    (name) =>
      name.toLowerCase().replace(/\s+/g, "-").replace(/[^a-zа-яё0-9-]/gi, "") ===
      collection.toLowerCase()
  )

  if (!collectionName) {
    return (
      <div className="min-h-screen bg-background py-12 px-4">
        <div className="mx-auto max-w-7xl text-center">
          <h1 className="text-3xl font-bold text-foreground mb-4">Коллекция не найдена</h1>
          <Link href="/collections" className="text-primary hover:text-primary/80 transition-colors">
            Вернуться к коллекциям
          </Link>
        </div>
      </div>
    )
  }

  const collectionProducts = products.filter(
    (p) => p.collection === collectionName && p.slug && p.name && p.price_retail > 0
  )

  return (
    <div className="bg-background min-h-screen">
      {/* Breadcrumbs */}
      <div className="border-b border-border">
        <div className="mx-auto max-w-7xl px-4 py-3">
          <nav className="flex items-center gap-1.5 text-sm flex-wrap" aria-label="Breadcrumb">
            <Link href="/" className="text-muted-foreground hover:text-primary transition-colors">
              Главная
            </Link>
            <ChevronRight className="h-3.5 w-3.5 text-muted-foreground/50" />
            <Link href="/catalog" className="text-muted-foreground hover:text-primary transition-colors">
              Каталог
            </Link>
            <ChevronRight className="h-3.5 w-3.5 text-muted-foreground/50" />
            <Link href="/collections" className="text-muted-foreground hover:text-primary transition-colors">
              Коллекции
            </Link>
            <ChevronRight className="h-3.5 w-3.5 text-muted-foreground/50" />
            <span className="text-foreground font-medium">{collectionName}</span>
          </nav>
        </div>
      </div>

      {/* Header */}
      <div className="py-12 lg:py-16 bg-muted/50">
        <div className="mx-auto max-w-7xl px-4">
          <h1 className="text-3xl lg:text-4xl font-bold text-foreground text-balance">
            Плитка {collectionName} Cersanit купить в Санкт-Петербурге
          </h1>
          <p className="text-lg text-muted-foreground mt-4 max-w-2xl">
            {collectionProducts.length} товаров в наличии на складе Янино. Доставка по Санкт-Петербургу
            и Ленинградской области от 1 рабочего дня. Самовывоз бесплатно.
          </p>
        </div>
      </div>

      {/* Products Grid */}
      <div className="py-12 lg:py-16">
        <div className="mx-auto max-w-7xl px-4">
          {collectionProducts.length > 0 ? (
            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
              {collectionProducts.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          ) : (
            <div className="text-center py-12">
              <p className="text-muted-foreground mb-6">В этой коллекции пока нет товаров</p>
              <Link
                href="/catalog"
                className="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-primary text-primary-foreground font-medium hover:bg-primary/90 transition-colors"
              >
                Смотреть все товары
              </Link>
            </div>
          )}
        </div>
      </div>

      {/* SEO Text */}
      <div className="py-12 lg:py-16 bg-muted/30">
        <div className="mx-auto max-w-7xl px-4 max-w-3xl">
          <h2 className="text-2xl font-bold text-foreground mb-6">
            Почему выбирают коллекцию {collectionName}
          </h2>
          <div className="flex flex-col gap-4 text-foreground/80 leading-relaxed">
            <p>
              Коллекция {collectionName} -- это продукты высокого качества от официального производителя Cersanit.
              Все товары в наличии на складе Янино и готовы к отправке. Мы предлагаем конкурентные цены,
              бесплатный самовывоз и доставку по Санкт-Петербургу в течение 1-2 рабочих дней.
            </p>
            <p>
              Вся керамическая плитка и керамогранит из коллекции {collectionName} имеют сертификаты качества
              и соответствуют российским стандартам. Для любых вопросов о выборе товара свяжитесь с нашими
              специалистами по телефону +7 (905) 205-09-00 или в Telegram @flyroman.
            </p>
          </div>
        </div>
      </div>
    </div>
  )
}
