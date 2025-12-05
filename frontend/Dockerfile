# use the official Bun image
# FROM oven/bun:latest AS base # Error build tailwind
FROM node:22.14-slim AS base

# Set working directory
WORKDIR /app

RUN npm install -g bun

# Add pm2
RUN npm install -g pm2
# RUN bun add -g pm2

# install dependencies into temp directory
# this will cache them and speed up future builds
FROM base AS install

RUN mkdir -p /temp/dev

COPY package.json bun.lock /temp/dev/

RUN cd /temp/dev && bun install

# copy node_modules from temp directory
# then copy all (non-ignored) project files into the image
FROM base AS release

COPY --from=install /temp/dev/node_modules node_modules

COPY . .

# Install dependencies & build
RUN bun run build

# Expose port 80
EXPOSE 80

# run the app
USER node
# CMD ["bun", "dev"]
# CMD ["bun", ".output/server/index.mjs"]
CMD ["pm2-runtime", "start", "ecosystem.config.cjs"]
