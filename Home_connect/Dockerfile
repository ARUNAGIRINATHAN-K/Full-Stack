# Multi-stage Dockerfile for building and serving the Vite app with nginx
FROM node:20-alpine AS builder
WORKDIR /app

# Install dependencies (try npm ci, fall back to npm install)
COPY package.json package-lock.json* ./
RUN npm ci || npm install

# Copy source and build
COPY . .
RUN npm run build

FROM nginx:stable-alpine
COPY --from=builder /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

# Notes:
# - This serves the static `dist` folder using nginx. For SPA routing on refresh,
#   you may want to provide a custom nginx config that uses `try_files $uri /index.html`.