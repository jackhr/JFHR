# JFHR Site Hub

This repository is the root of a personal website space for hosting multiple mini applications under one domain.

## Purpose
- Provide a central landing page that lists available apps.
- Support apps on dedicated subdomains where appropriate.
- Keep shared framework/router code in one place while allowing each mini app to evolve independently.

## Current Applications
- `Medicine Log`  
  Primary URL: `https://medicine.jackrainey.com/`  
  Legacy route: `/com/medicine` (redirects to subdomain)  
  Description: Medicine intake tracking app.

## Routing Overview
- `/` renders the app directory landing page.
- `/com/medicine` and `/com/medicine/*` are redirected to `https://medicine.jackrainey.com`.
- The router supports wildcard route matching (for mounted apps and static assets).

## Repository Layout
- `public/` web root and front controller.
- `app/controllers/` app and route controllers.
- `app/views/` landing/admin views.
- `core/` bootstrap and router.
- `com/medicine/` legacy compatibility location for medicine routing.

## Notes
- This repo is now an app host, not a single-purpose app project.
- New mini apps should be mounted under `com/<app-name>` and linked from the landing page.
