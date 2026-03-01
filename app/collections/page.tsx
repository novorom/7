import type { Metadata } from "next"
import { CollectionsClient } from "./collections-client"
import { products } from "@/lib/products-data"

export const metadata: Metadata = {
  title: "Коллекции Cersanit -- керамическая плитка и керамогранит в СПб",
  description:
    "Все коллекции Cersanit в наличии на складе в Янино. Керамическая плитка, керамогранит, мозаика. Купить с доставкой по СПб и ЛО.",
  alternates: { canonical: "/collections" },
}

export default function CollectionsPage() {
  // Серверная сборка коллекций — Яндексбот видит реальный контент без JS
  const allCollectionNames = [
    ...new Set(
      products
        .filter((p) => p.collection && p.collection.trim() && p.collection.toLowerCase() !== "other")
        .map((p) => p.collection as string)
    ),
  ]

  const initialCollections = allCollectionNames
    .map((collName) => {
      const collectionProducts = products.filter((p) => p.collection === collName)
      const firstProduct = collectionProducts[0]
      return {
        id: collName,
        name: collName,
        slug: collName.toLowerCase().replace(/\s+/g, "-").replace(/[^a-zа-яё0-9-]/gi, ""),
        image: firstProduct?.collection_image || firstProduct?.main_image || "",
        product_count: collectionProducts.length,
        types: [
          ...new Set(collectionProducts.map((p) => p.product_type).filter(Boolean)),
        ] as string[],
      }
    })
    .filter((c) => c.product_count > 0)

  return <CollectionsClient initialCollections={initialCollections} />
}
