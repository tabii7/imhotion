/** @type {import('next').NextConfig} */
module.exports = {
  output: 'export',
  basePath: '/v0',
  images: { unoptimized: true },
  eslint: { ignoreDuringBuilds: true },
  typescript: { ignoreBuildErrors: true }
}
