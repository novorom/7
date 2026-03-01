import type { Metadata } from "next"
import { products } from "@/lib/products-data"
import { ProductPageClient } from "./product-client"

const SITE_URL = "https://cersanit-spb.ru"

export async function generateStaticParams() {
  return products
    .filter((p) => p.slug)
    .map((p) => ({ slug: p.slug as string }))
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>
}): Promise<Metadata> {
  const { slug } = await params
  const product = products.find((p) => p.slug === slug)

  if (!product) {
    return {
      title: "Товар не найден | Дом Плитки CERSANIT",
    }
  }

  const title = `${product.name} — купить в СПб ${product.price_retail} ₽/м²`
  const description = `Купить ${product.name} в Санкт-Петербурге. Цена ${product.price_retail} ₽/м². В наличии на складе Янино. Доставка по СПб и ЛО от 1 дня. Артикул: ${product.sku}.`

  return {
    title,
    description,
    alternates: { canonical: `/catalog/${product.slug}` },
    openGraph: {
      title,
      description,
      url: `${SITE_URL}/catalog/${product.slug}`,
      siteName: "Дом Плитки CERSANIT",
      locale: "ru_RU",
      type: "website",
      images: product.main_image
        ? [{ url: product.main_image, alt: product.name }]
        : [],
    },
  }
}

export default async function ProductPage({
  params,
}: {
  params: Promise<{ slug: string }>
}) {
  const { slug } = await params
  return <ProductPageClient slug={slug} />
}
