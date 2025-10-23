# Hosting guide

This repo is a Vite + React + TypeScript app. Below are quick instructions for deploying to common hosts.

Environment variables
- VITE_SUPABASE_URL
- VITE_SUPABASE_PUBLISHABLE_KEY

Vercel (recommended for quick deploy)
1. Sign in to Vercel and import this repository.
2. Set the Build command: `npm run build` and Output Directory: `dist`.
3. Add the two environment variables in Project Settings (VITE_SUPABASE_URL and VITE_SUPABASE_PUBLISHABLE_KEY).

Netlify
1. Connect your Git repository in Netlify.
2. Build command: `npm run build`, Publish directory: `dist`.
3. Add environment variables in Site settings.
4. A `netlify.toml` is included for convenience.

GitHub Pages (static deploy)
1. The repository contains a GitHub Action that builds and publishes `dist` to GitHub Pages on push to `main`.
2. Make sure GitHub Pages is enabled in repository settings (deploy from gh-pages branch).
3. Add any environment variables as repository secrets and modify the workflow to inject them if needed.

Repository secrets for Supabase (used by the workflow)
- Go to your GitHub repository -> Settings -> Secrets -> Actions -> New repository secret.
- Add these secrets:
	- VITE_SUPABASE_URL
	- VITE_SUPABASE_PUBLISHABLE_KEY

The included workflow will inject those secrets as build-time environment variables so the static build can include the Supabase config.

Docker
1. Build: `docker build -t my-app .`
2. Run: `docker run -p 8080:80 my-app`

Local preview
1. Install dependencies: `npm ci`
2. Build: `npm run build`
3. Preview: `npm run preview` (uses Vite preview server)
