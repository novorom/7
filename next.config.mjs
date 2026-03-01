/** @type {import('next').NextConfig} */
const nextConfig = {
  images: {
    unoptimized: true,
    remotePatterns: [
      {
        protocol: "https",
        hostname: "pvi.cersanit.ru",
      },
    ],
  },
}

export default nextConfig
