# Laravel Inertia + Vue - Meeting Tracking System

## Project Overview

This is a Meeting Tracking System built with **Laravel Inertia + Vue 3** frontend. The frontend manages pages via Inertia, while Laravel handles the backend.

### Tech Stack
- **Backend**: Laravel 11+
- **Frontend**: Vue 3 + Inertia
- **Build**: Vite
- **Auth**: Laravel Breeze

---

## Database Schema

### Tables

| Table | Purpose |
|-------|---------|
| `users` | User accounts with roles (admin, member, user) |
| `agendas` | Meeting agendas withsoft deletes |
| `concerns` | Issues/tasks linked to agendas with soft deletes |
| `comments` | Comments on concerns (polymorphic) |
| `attachments` | File uploads (polymorphic) |
| `membership_request` | User role upgrade requests |
| `activity_logs` | Activity tracking |

### User Roles

- `admin` - Full CRUD access (Secretariat)
- `member` - Can view, create concerns, comment
- `user` - View only

---

## Authentication (Breeze + Inertia)

Inertia handles auth via Breeze (session-based, not API tokens).

### Auth Routes (routes/auth.php)

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET/POST | `/register` | RegisteredUserController | Register |
| GET/POST | `/login` | AuthenticatedSessionController | Login |
| GET/POST | `/forgot-password` | PasswordResetLinkController | Password reset |
| GET/POST | `/reset-password/{token}` | NewPasswordController | Reset password |
| POST | `/logout` | AuthenticatedSessionController@destroy | Logout |

---

## Inertia Page Structure

Inertia uses Vue pages. Controller returns `Inertia::render()` instead of `view()`.

### Current Routes (routes/web.php)

```php
Route::middleware(['auth'])->group(function () {
    
    // Page routes (render Vue pages)
    Route::get('/app/home', fn() => Inertia::render('Home'))->name('home');
    Route::get('/app/create-agenda', fn() => Inertia::render('Agenda/Create'))->name('agenda.create');
    Route::get('/app/view-agenda', fn() => Inertia::render('Agenda/ViewAll'))->name('agenda.view-all');
    Route::get('/app/view-agenda/{id}', [AgendaController::class, 'clickedAgenda']);
    
    // API-like JSON routes
    Route::get('/agenda-load', [AgendaController::class, 'loadAgendas']);
    Route::resource('agendas', AgendaController::class);
    // ... more routes
});
```

### Agenda Endpoints

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET | `/agenda-load` | AgendaController@loadAgendas | Get paginated agendas |
| GET | `/agendas` | AgendaController@index | List agendas |
| GET | `/agendas/{agenda}` | AgendaController@show | Show agenda |
| POST | `/agendas` | AgendaController@store | Create agenda (admin only) |
| PUT | `/agendas/{id}` | AgendaController@update | Update agenda (admin only) |
| DELETE | `/agendas/{id}` | AgendaController@destroy | Soft delete (admin/IT only) |
| GET | `/@gend4/trash-agenda` | AgendaController@trashed | Get trashed agendas |
| PUT | `/agendas/{id}/restore` | AgendaController@restore | Restore agenda |
| DELETE | `/agendas/{id}/fDelete` | AgendaController@forceDelete | Permanent delete |

---

### Concern Endpoints

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET | `/concerns/all` | ConcernController@allConcerns | Get all concerns |
| GET | `/concerns/your` | ConcernController@yourConcerns | Get my concerns |
| GET | `/{agenda_id}/concernBAg` | ConcernController@loadConcernAg | Get concerns by agenda |
| GET | `/concerns/{agenda_id}` | ConcernController@index | List concerns for agenda |
| POST | `/concerns` | ConcernController@store | Create concern (admin/member) |
| GET | `/concerns/edit/{id}` | ConcernController@edit | Edit concern form |
| PUT | `/concerns/{id}` | ConcernController@update | Update concern |
| DELETE | `/concerns/delete/{id}` | ConcernController@destroy | Soft delete concern |
| GET | `/concerns/show/{id}` | ConcernController@show | Show concern |
| GET | `/c0nC3rn/trash-concerns` | ConcernController@deletedConcerns | Get trashed concerns |
| PUT | `/concerns/{id}/restore` | ConcernController@restore | Restore concern |
| DELETE | `/concerns/{id}/fDelete` | ConcernController@forceDelete | Permanent delete |

---

### Comment Endpoints

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET | `/comments/{concern_id}/load` | CommentController@isCommentsLoad | Load comments |
| POST | `/comments/write` | CommentController@store | Create comment |
| GET | `/comments/{comment_id}/edit` | CommentController@edit | Get comment for edit |
| PATCH | `/comments/{comment_id}/update` | CommentController@update | Update comment |
| DELETE | `/comments/{comment_id}/delete` | CommentController@destroy | Delete comment |

---

### User/Membership Endpoints

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| POST | `/membership-requests/submit` | UserController@storeRequest | Submit request |
| GET | `/membership-requests/get-partial` | UserController@showPartialMemberRequests | Get partial list |
| GET | `/membership-requests/get-all` | UserController@showAllMemberRequests | Get all requests |

---

### Profile Endpoints

| Method | Endpoint | Controller | Description |
|--------|----------|------------|-------------|
| GET | `/profiles` | ProfileController@index | List profiles |
| GET | `/profiles/{id}` | ProfileController@show | Show profile |
| PATCH | `/profile` | ProfileController@update | Update profile |
| DELETE | `/profile` | ProfileController@destroy | Delete account |

---

## Controller Methods Detail

### AgendaController

```php
// Public API methods
loadAgendas()           // GET - Paginated agendas with concerns count
clickedAgenda()         // GET - View specific agenda
previewEditAgenda()    // GET - Edit form preview
trashed()              // GET - Soft deleted agendas (admin)

// Resource methods
index()                // GET - List
show()                 // GET - Show
create()               // GET - Create form (admin)
store()                // POST - Create (admin)
edit()                 // GET - Edit form (admin)
update()               // PUT - Update (admin)
destroy()              // DELETE - Soft delete (admin/IT)
restore()             // PUT - Restore (admin)
forceDelete()          // DELETE - Permanent delete (admin)
```

### ConcernController

```php
// Public API methods
loadConcernAg($id)     // GET - Concerns by agenda
yourConcerns()        // GET - Current user concerns
allConcerns()         // GET - All concerns
deletedConcerns()     // GET - Soft deleted concerns

// Resource methods
index()               // GET - List by agenda
create()              // GET - Create form
raiseConcern()        // GET - Create form preview
store()               // POST - Create
edit()               // GET - Edit form
editPreview()        // GET - Edit form preview
update()             // PUT - Update
show()               // GET - Show
destroy()            // DELETE - Soft delete
restore()            // PUT - Restore
forceDelete()        // DELETE - Permanent delete
```

### CommentController

```php
isCommentsLoad($id)    // GET - Load comments for concern
store()               // POST - Create comment
edit()                // GET - Get comment for edit
update()             // PATCH - Update comment
destroy()            // DELETE - Delete comment
```

---

## Middleware

### Role Middleware

Located in `app/Http/Middleware/Role.php`

```php
Role::class - Restricts access by role
// Usage: ->middleware('role:admin')
```

### Inertia Middleware

Inertia pages are rendered server-side. Routes in `web.php` use `auth` middleware.

---

## Inertia Vue Setup

### Install Inertia Vue

```bash
composer require inertiajs/inertia-laravel
npm install @inertiajs/vue3
```

### vite.config.js

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
});
```

### resources/js/app.js

```javascript
import { createApp } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import CreateLayout from './Layouts/CreateLayout.vue';

createInertiaApp({
    resolve: name => `./Pages/${name}`,
    setup({ el, App, props, plugin }) {
        createApp({ plugin })
            .component('CreateLayout', CreateLayout)
            .use(plugin)
            .mount(el);
    },
});
```

### Controller Returns Inertia Page

```php
use Inertia\Inertia;
use App\Models\Agenda;

public function index()
{
    $agendas = Agenda::with(['creator', 'attachments'])
        ->orderBy('date', 'desc')
        ->paginate(20);

    return Inertia::render('Agenda/Index', [
        'agendas' => $agendas
    ]);
}

public function clickedAgenda($id)
{
    $agenda = Agenda::with(['creator', 'attachments', 'concerns'])
        ->findOrFail($id);

    return Inertia::render('Agenda/View', [
        'agenda' => $agenda
    ]);
}

public function store(Request $request)
{
    // ... validation & create
    
    return redirect()->back()->with('success', 'Agenda saved!');
}

// Convert to Inertia response
public function store(Request $request)
{
    // ... validation & create
    
    return Inertia::back()->with('success', 'Agenda saved!');
}
```

---

## Vue Pages for Inertia

### Vue Page Structure

Create pages in `resources/js/Pages/`:

```
resources/js/
├── Pages/
│   ├── Agenda/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   ├── Edit.vue
│   │   └── View.vue
│   ├── Concern/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   └── View.vue
│   └── Home.vue
├── Layouts/
│   ├── AppLayout.vue
│   └── GuestLayout.vue
└── app.js
```

### Vue Page Example (Index.vue)

```vue
<template>
  <div>
    <h1>Agendas</h1>
    
    <div v-for="agenda in agendas.data" :key="agenda.id">
      <h2>{{ agenda.title }}</h2>
      <p>{{ agenda.date }}</p>
      <p>Status: {{ agenda.status }}</p>
      <p>Concerns: {{ agenda.concerns_count }}</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  agendas: Object
})
</script>
```

### Vue Page with Data Transformations

```vue
<script setup>
const props = defineProps({
  agenda: Object
})

const formatDate = (date) => {
  return new Date(date).toLocaleDateString()
}

const statusColors = {
  pending: 'bg-amber-500',
  ongoing: 'bg-blue-500',
  resolved: 'bg-green-500',
  closed: 'bg-gray-500'
}
</script>
```

### Inertia Form Submission

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  notes: '',
  status: 'pending'
})

const submit = () => {
  form.post('/agendas', {
    onSuccess: () => {
      form.reset()
    }
  })
}
</script>
```

### Inertia Link Navigation

```vue
<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <Link :href="`/app/view-agenda/${agenda.id}`" class="text-blue-500">
    View
  </Link>
</template>
```

### Handling Flash Messages

```vue
<script setup>
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const flash = page.props.flash

// Or display globally in layout
</script>

<template>
  <div v-if="$page.props.flash.success" class="alert">
    {{ $page.props.flash.success }}
  </div>
</template>
```

---

---

## Vue Frontend Integration Guide (Breeze API / Sanctum)

### Axios Setup with Sanctum Tokens

```javascript
// resources/js/api.js
import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

// Add token to all requests
api.interceptors.request.use(config => {
    const token = localStorage.getItem('api_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
```

### Authentication (Sanctum Tokens)

```javascript
// auth.js
import api from './api';

export const login = async (email, password) => {
    const response = await api.post('/login', { email, password });
    const { token } = response.data;
    localStorage.setItem('api_token', token);
    return response.data;
};

export const register = async (name, email, password, password_confirmation) => {
    const response = await api.post('/register', {
        name, email, password, password_confirmation
    });
    const { token } = response.data;
    localStorage.setItem('api_token', token);
    return response.data;
};

export const logout = async () => {
    await api.post('/logout');
    localStorage.removeItem('api_token');
};

export const getUser = async () => {
    const response = await api.get('/user');
    return response.data;
};
```

### Example API Calls

```javascript
// Agenda
import api from './api';

// Get agendas
const getAgendas = async (page = 1) => {
    const response = await api.get(`/agendas?page=${page}`);
    return response.data;
};

// Create agenda
const createAgenda = async (data) => {
    const response = await api.post('/agendas', data);
    return response.data;
};

// Get concerns
const getConcerns = async (page = 1) => {
    const response = await api.get(`/concerns?page=${page}`);
    return response.data;
};

// Create concern
const createConcern = async (data) => {
    const response = await api.post('/concerns', data);
    return response.data;
};

// Get comments
const getComments = async (concernId) => {
    const response = await api.get(`/comments/${concernId}`);
    return response.data;
};

// Add comment
const addComment = async (concernId, content) => {
    const response = await api.post('/comments', {
        concern_id: concernId,
        write_comm: content
    });
    return response.data;
};
```

---

## Creating Fresh Laravel Backend

### Step 1: New Laravel Project

```bash
composer create-project laravel/laravel backend
cd backend
```

### Step 2: Install Laravel Breeze + Vue

```bash
composer require laravel/breeze --dev
php artisan breeze:install vue
# Installs Vue + Inertia frontend
```

### Step 3: Install Inertia Vue (if needed)

```bash
composer require inertiajs/inertia-laravel
npm install @inertiajs/vue3 @vitejs/plugin-vue
```

### Step 4: Enable Inertia Middleware

In `bootstrap/app.php`:
```php
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: HandleInertiaRequests::class);
    })
    ->createApplication();
```

### Step 5: Copy Models

Copy models to `app/Models/`:
- User.php, Agenda.php, Concern.php, Comment.php, Attachment.php, MemberRequest.php

### Step 6: Copy Controllers

Controllers work with Inertia. Update to use `Inertia::render()` instead of `view()`.

### Step 7: Create Migrations

Copy migrations from `database/migrations/`

### Step 8: Configure Routes

Routes in `routes/web.php` return Inertia pages:

```php
Route::middleware(['auth'])->group(function () {
    Route::get('/app/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/app/agendas', [AgendaController::class, 'index']);
    Route::get('/app/agendas/{id}', [AgendaController::class, 'clickedAgenda']);
});
```

### Step 9: Run Migrations

```bash
php artisan migrate
```

### Step 10: Create Vue Pages

Create Vue pages in `resources/js/Pages/`

---

## File Structure for Inertia/Vue

```
backend-api/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AgendaController.php
│   │   │   ├── ConcernController.php
│   │   │   ├── CommentController.php
│   │   │   ├── UserController.php
│   │   │   ├── ProfileController.php
│   │   │   └── Auth/                    # Breeze controllers
│   │   │       ├── AuthenticatedSessionController.php
│   │   │       ├── RegisteredUserController.php
│   │   │       ├── PasswordController.php
│   │   │       └── ...
│   │   └── Middleware/
│   │       └── Role.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Agenda.php
│   │   ├── Concern.php
│   │   ├── Comment.php
│   │   ├── Attachment.php
│   │   └── MemberRequest.php
│   └── Providers/
│       └── RouteServiceProvider.php
├── config/
│   ├── cors.php
│   └── sanctum.php
├── database/
│   ├── migrations/
│   └── seeders/
└── routes/
    ├── api.php
    ├── web.php
    └── auth.php                         # Breeze authentication routes
```

---

## Authentication Flow for Vue (Sanctum)

Breeze API uses **token-based authentication** with Laravel Sanctum. The flow:

1. **Login**: POST to `/login` with credentials → receive token
2. **Store Token**: Save token in localStorage
3. **Attach Token**: Include `Authorization: Bearer {token}` header
4. **Protected Routes**: Use `auth:sanctum` middleware

### Login Example

```javascript
const login = async (email, password) => {
    const response = await axios.post('/login', { email, password });
    const { token } = response.data;
    localStorage.setItem('api_token', token);
    return response.data;
};
```

### Logout Example

```javascript
const logout = async () => {
    await axios.post('/logout');
    localStorage.removeItem('api_token');
};
```

### Check Authenticated User

```javascript
const getUser = async () => {
    const token = localStorage.getItem('api_token');
    const response = await axios.get('/user', {
        headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
};
```

### Check Authentication State

```javascript
const checkAuth = async () => {
    try {
        const response = await axios.get('/user');
        return response.data;
    } catch (error) {
        return null;
    }
};
```

---

## API Routes for Vue

Add to `routes/api.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\ConcernController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    
    // Agenda API - /api/agendas
    Route::get('/agendas', [AgendaController::class, 'loadAgendas']);
    Route::get('/agendas/trashed', [AgendaController::class, 'trashed']);
    Route::get('/agendas/{id}', [AgendaController::class, 'show']);
    Route::post('/agendas', [AgendaController::class, 'store']);
    Route::put('/agendas/{id}', [AgendaController::class, 'update']);
    Route::delete('/agendas/{id}', [AgendaController::class, 'destroy']);
    Route::put('/agendas/{id}/restore', [AgendaController::class, 'restore']);
    Route::delete('/agendas/{id}/force-delete', [AgendaController::class, 'forceDelete']);

    // Concern API - /api/concerns
    Route::get('/concerns', [ConcernController::class, 'allConcerns']);
    Route::get('/concerns/my', [ConcernController::class, 'yourConcerns']);
    Route::get('/concerns/agenda/{agenda_id}', [ConcernController::class, 'loadConcernAg']);
    Route::get('/concerns/{id}', [ConcernController::class, 'show']);
    Route::post('/concerns', [ConcernController::class, 'store']);
    Route::put('/concerns/{id}', [ConcernController::class, 'update']);
    Route::delete('/concerns/{id}', [ConcernController::class, 'destroy']);
    Route::get('/concerns/trashed', [ConcernController::class, 'deletedConcerns']);
    Route::put('/concerns/{id}/restore', [ConcernController::class, 'restore']);
    Route::delete('/concerns/{id}/force-delete', [ConcernController::class, 'forceDelete']);

    // Comment API - /api/comments
    Route::get('/comments/{concern_id}', [CommentController::class, 'isCommentsLoad']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::get('/comments/{id}/edit', [CommentController::class, 'edit']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

    // User/Membership API
    Route::get('/membership-requests', [UserController::class, 'showAllMemberRequests']);
    Route::post('/membership-requests', [UserController::class, 'storeRequest']);

    // Profile API
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
});
```

---

## Vue Frontend API Calls

| Feature | Endpoint | Method |
|---------|----------|--------|
| Get agendas | `/api/agendas` | GET |
| Create agenda | `/api/agendas` | POST |
| Get concerns | `/api/concerns` | GET |
| Get my concerns | `/api/concerns/my` | GET |
| Get concerns by agenda | `/api/concerns/agenda/{id}` | GET |
| Create concern | `/api/concerns` | POST |
| Get comments | `/api/comments/{id}` | GET |
| Create comment | `/api/comments` | POST |
| Update profile | `/api/profile` | PUT |

---

## Role-Based Access Control

```php
// Middleware checks
admin: auth()->user()->role === 'admin'
member: in_array(auth()->user()->role, ['admin', 'member'])
user: any authenticated user
```

---

## Response Format

All JSON responses follow this format:

```json
{
    "success": true,
    "message": "Operation description",
    "data": { ... },
    "agendas": { ... },
    "concerns": { ... }
}
```

Error responses:
```json
{
    "success": false,
    "message": "Error description"
}
```

---

## Converting Blade Views to JSON API for Vue

Your current controllers return **Blade views** for the old frontend. For Vue, you need **JSON API** responses.

### Current Controller Methods Analysis

#### AgendaController

| Method | Current Return | Needed for Vue |
|--------|---------------|----------------|
| `index()` | `view('agendas.index')` | JSON list |
| `show()` | `view('agendas.show')` | JSON object |
| `create()` | `view('agendas.create')` | JSON (or skip, Vue has form) |
| `edit()` | `view('agendas.edit')` | JSON object |
| `loadAgendas()` | JSON ✓ | Use as-is |
| `store()` | redirect | JSON |
| `update()` | redirect | JSON |
| `destroy()` | JSON ✓ | Use as-is |

### How to Convert Blade to JSON

#### Example: Converting show() to JSON API

**Before (Blade):**
```php
public function show(Agenda $agenda)
{
    return view('agendas.show', compact('agenda'));
}
```

**After (JSON API):**
```php
public function show(Agenda $agenda)
{
    return response()->json([
        'success' => true,
        'agenda' => [
            'id' => $agenda->agenda_id,
            'title' => $agenda->title,
            'date' => $agenda->date,
            'notes' => $agenda->notes,
            'status' => $agenda->status,
            'created_by' => $agenda->created_by,
            'creator' => $agenda->creator?->only('id', 'name'),
            'attachments' => $agenda->attachments->map(fn($a) => $a->file_path),
            'concerns_count' => $agenda->concerns_count,
            'created_at' => $agenda->created_at,
            'updated_at' => $agenda->updated_at,
        ]
    ]);
}
```

### Creating API Resources (Recommended)

Use Laravel API Resources for consistent JSON output:

```php
// php artisan make:resource AgendaResource

// app/Http/Resources/AgendaResource.php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->agenda_id,
            'title' => $this->title,
            'date' => $this->date,
            'notes' => $this->notes,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', function() {
                return ['id' => $this->creator->id, 'name' => $this->creator->name];
            }),
            'attachments' => $this->attachments->pluck('file_path'),
            'concerns_count' => $this->whenCounted('concerns'),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
```

**Controller using Resource:**
```php
use App\Http\Resources\AgendaResource;

public function show(Agenda $agenda)
{
    return response()->json([
        'success' => true,
        'agenda' => new AgendaResource($agenda)
    ]);
}

public function index()
{
    $agendas = Agenda::with(['creator', 'attachments'])->paginate(20);
    return AgendaResource::collection($agendas);
}
```

### Vue Integration for View Data

When Vue needs data that was previously passed to Blade views:

```javascript
// Vue - Fetch agenda for viewing
const getAgenda = async (id) => {
    const response = await api.get(`/api/agendas/${id}`);
    return response.data.agenda;
};

// Vue - Fetch agenda for editing
const getAgendaForEdit = async (id) => {
    const response = await api.get(`/api/agendas/${id}/edit`);
    return response.data.agenda;
};

// Vue - Get agendas list
const getAgendas = async (page = 1) => {
    const response = await api.get(`/api/agendas?page=${page}`);
    return response.data;
};
```

### Complete API Routes for Vue

Add these to `routes/api.php`:

```php
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Agenda - Read
    Route::get('/agendas', [AgendaController::class, 'index']);  // JSON list
    Route::get('/agendas/{agenda}', [AgendaController::class, 'show']);  // Single
    Route::get('/agendas/trashed', [AgendaController::class, 'trashed']);
    
    // Agenda - Write
    Route::post('/agendas', [AgendaController::class, 'store']);
    Route::put('/agendas/{agenda}', [AgendaController::class, 'update']);
    Route::delete('/agendas/{agenda}', [AgendaController::class, 'destroy']);
    Route::put('/agendas/{agenda}/restore', [AgendaController::class, 'restore']);
    Route::delete('/agendas/{agenda}/force-delete', [AgendaController::class, 'forceDelete']);
    
    // Additional JSON endpoints for Vue views
    Route::get('/agendas/{agenda}/edit', [AgendaController::class, 'show']);  // Return JSON for edit form
});
```

### Handling Redirects in API

**Before (Blade):**
```php
return redirect()->back()->with('success', 'Agenda saved!');
```

**After (JSON API):**
```php
return response()->json([
    'success' => true,
    'message' => 'Agenda saved successfully!',
    'redirect' => '/agendas'  // Vue can use this to redirect
]);
```

Vue handles this response:
```javascript
const createAgenda = async (data) => {
    const response = await api.post('/api/agendas', data);
    const { success, message, redirect } = response.data;
    
    if (success) {
        showNotification(message, 'success');
        router.push(redirect);
    }
};
```