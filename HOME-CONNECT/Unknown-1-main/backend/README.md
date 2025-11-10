# Freelance Marketplace Backend

Express + MySQL API backing the frontend pages. Minimal MVP includes auth and jobs endpoints.

## Quick Start

1. Create environment file:

Copy `.env.example` to `.env` and fill values.

2. Install dependencies:

```powershell
cd "a:\My project\New folder\freelance-marketplace\backend"
npm install
```

3. Create database schema:

```powershell
# Use your MySQL client to run schema.sql
# Example (PowerShell):
mysql -u root -p < schema.sql
```

4. Run the server (dev):

```powershell
npm run dev
```

Server will start at http://localhost:3000

5. Test endpoints:

Open `requests.http` in VS Code and use the REST Client extension, or use Postman.

## Scripts

- `npm run dev` - Start with nodemon
- `npm start` - Start in production mode

## Endpoints

- `GET /health` - Health check
- `POST /api/auth/register` - Register user (client/provider)
- `POST /api/auth/login` - Login and get JWT
- `GET /api/auth/verify-token` - Validate token
- `GET /api/jobs` - List jobs with filters
- `POST /api/jobs` - Create job (client only)
- `PUT /api/jobs/:id/status` - Update job status (client owner only)
- `GET /api/jobs/:id` - Get single job (public)
- `POST /api/jobs/:id/apply` - Apply to a job (provider only)

## Notes

- This is a minimal bootstrap; see `BACKEND_SETUP_GUIDE.md` for the full schema and additional routes.
- Ensure `JWT_SECRET` is set to a strong random string in production.

### Role requirements

- Posting jobs requires a signed-in user with role `client` (enforced by middleware and verified in the UI).
- Applying to jobs requires a signed-in user with role `provider`.
