FROM node:22-alpine AS base

# Set working directory
WORKDIR /app

RUN npm install -g bun

# Install Stage
FROM base AS install

COPY package.json bun.lock ./

# Install ALL dependencies (including devDependencies)
RUN bun install --frozen-lockfile

# Build Stage
FROM base AS build

COPY --from=install /app/node_modules /app/node_modules

COPY . .

# Run build (Nuxt generates production assets by default)
RUN bun run build

# Run Stage
FROM base AS run

# Set production env for runtime
ENV NODE_ENV=production
ENV NUXT_HOST=0.0.0.0
ENV NUXT_PORT=3000

WORKDIR /app

USER node

COPY --from=build --chown=node:node /app/.output ./.output

EXPOSE 3000

CMD ["bun", "run", ".output/server/index.mjs"]
