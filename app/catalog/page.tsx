import { CatalogClient } from "./catalog-client"
import { products } from "@/lib/products-data"
import type { Product } from "@/lib/products-data"

export default function CatalogPage() {
  const initialProducts: Product[] = products
    .filter((p) => p.name && p.name.trim() && p.price_retail && p.price_retail > 0 && p.slug)
    .slice(0, 60)

  return <CatalogClient initialProducts={initialProducts} />
}
