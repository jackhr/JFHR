# JFHR Site Hub

This repository is the root of a personal website space for hosting multiple mini applications under one domain.

## Purpose
- Provide a central landing page that lists available apps.
- Route each app under a path segment (for example `/com/medicine`).
- Keep shared framework/router code in one place while allowing each mini app to evolve independently.

## Current Applications
- `Medicine Log`  
  Route: `/com/medicine`  
  Description: Medicine intake tracking app.

## Routing Overview
- `/` renders the app directory landing page.
- `/com/medicine` and `/com/medicine/*` are handled by `MedicineController`.
- The router supports wildcard route matching (for mounted apps and static assets).

## Repository Layout
- `public/` web root and front controller.
- `app/controllers/` app and route controllers.
- `app/views/` landing/admin views.
- `core/` bootstrap and router.
- `com/medicine/` mounted medicine app location.

## Notes
- This repo is now an app host, not a single-purpose app project.
- New mini apps should be mounted under `com/<app-name>` and linked from the landing page.
