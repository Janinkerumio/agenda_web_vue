# Blade to Vue Conversion Guide

This guide shows how to convert your existing Blade views to Vue components for Inertia.

## Overview

### Current Architecture
- **Routes**: `routes/web.php` returns Blade views
- **Data Loading**: JS files in `public/js/modules/` fetch JSON data
- **User Input**: Forms submit to Laravel endpoints

### New Architecture (Inertia)
- **Routes**: `routes/web.php` returns `Inertia::render()`
- **Data Loading**: Laravel passes data as props to Vue
- **User Input**: Vue forms use `@inertiajs/vue3` `useForm()`

## Blade View Structure

Your current app has 2 versions:
- `resources/views/v1/` - Older version (empty sections, JS loads data)
- `resources/views/v2/` - Newer version (extends layouts)

### Common Blade Pattern

```php
// routes/web.php
Route::get('/app/home', fn() => view('pages.home'));

// pages/home.blade.php
@extends('v2.layout.app')
@section('title', 'Home')
@section('main-content')
    @include('v1.ui.dashboard-ui')
@endsection
```

## Conversion Steps

### Step 1: Update Route to Return Inertia

**Before:**
```php
Route::get('/app/home', fn() => view('pages.home'));
```

**After:**
```php
use Inertia\Inertia;
use App\Models\Agenda;

Route::get('/app/home', function () {
    $agendas = Agenda::with(['creator'])->latest()->take(5)->get();
    $stats = [
        'total_agendas' => Agenda::count(),
        'open_concerns' => \App\Models\Concern::where('status', 'pending')->count(),
    ];
    
    return Inertia::render('Home', [
        'agendas' => $agendas,
        'stats' => $stats
    ]);
});
```

### Step 2: Create Vue Page

Create `resources/js/Pages/Home.vue`:

```vue
<template>
  <AppLayout>
    <div class="space-y-6">
      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-sm">
          <p class="text-gray-500">Upcoming Agenda</p>
          <h3 class="text-2xl font-bold mt-2">{{ stats.total_agendas }}</h3>
        </div>
        <div class="bg-white p-6 rounded-xl shadow-sm">
          <p class="text-gray-500">Open Concerns</p>
          <h3 class="text-2xl font-bold mt-2">{{ stats.open_concerns }}</h3>
        </div>
      </div>

      <!-- Recent Agendas -->
      <div class="bg-white p-6 rounded-xl shadow-sm">
        <h3 class="font-semibold text-gray-800 mb-4">Latest Agenda</h3>
        <div v-for="agenda in agendas" :key="agenda.id">
          <h2>{{ agenda.title }}</h2>
          <p>{{ agenda.date }}</p>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

defineProps({
  agendas: Array,
  stats: Object
})
</script>
```

### Step 3: Create Layout Components

Create `resources/js/Layouts/AppLayout.vue`:

```vue
<template>
  <div>
    <header>
      <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
          <div class="flex justify-between h-16">
            <div class="flex items-center">
              <span class="text-xl font-bold">Agenda Web</span>
            </div>
            <div class="flex items-center space-x-4">
              <Link href="/app/home" class="text-gray-700">Home</Link>
              <Link href="/app/view-agenda" class="text-gray-700">Agendas</Link>
              <form method="POST" action="/logout">
                <button type="submit" class="text-gray-700">Logout</button>
              </form>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <main class="max-w-7xl mx-auto px-4 py-6">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
```

---

## Page-by-Page Conversion

### 1. Home / Dashboard

**Blade:** `resources/views/v2/pages/home.blade.php`
**Includes:** `resources/views/v1/ui/dashboard-ui.blade.php`

**Vue Structure:**
```
resources/js/Pages/Dashboard.vue
```

**Data Needed:**
- Stats (total agendas, open concerns, closed concerns, % completion)
- Recent agendas (title, date, status)
- Recent activity

**Data Loading:**
```php
// DashboardController
public function index()
{
    $stats = [
        'total_agendas' => Agenda::count(),
        'open_concerns' => Concern::where('status', 'pending')->count(),
        'closed_concerns' => Concern::where('status', 'completed')->count(),
    ];
    
    $recent_agendas = Agenda::with('creator')->latest()->take(5)->get();
    
    return Inertia::render('Dashboard', [
        'stats' => $stats,
        'recentAgendas' => $recent_agendas,
    ]);
}
```

---

### 2. Create Agenda

**Blade:** `resources/views/v2/pages/agenda/create.blade.php`
**Includes:** `resources/views/v1/ui/agenda/create-agenda.blade.php`

**Check:** Requires admin role

**Vue Structure:**
```
resources/js/Pages/Agenda/Create.vue
```

**Form Fields:**
- title (required, text)
- notes (optional, textarea)
- file_path (optional, file upload)

**Vue Form:**
```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  notes: '',
  file_path: null
})

const submit = () => {
  form.post('/agendas', {
    onSuccess: () => form.reset()
  })
}
</script>

<template>
  <form @submit.prevent="submit">
    <div>
      <label>Title</label>
      <input v-model="form.title" type="text" />
      <p v-if="form.errors.title">{{ form.errors.title }}</p>
    </div>
    <div>
      <label>Notes</label>
      <textarea v-model="form.notes"></textarea>
    </div>
    <div>
      <input type="file" @input="form.file_path = $event.target.files[0]" />
    </div>
    <button :disabled="form.processing" type="submit">
      Save
    </button>
  </form>
</template>
```

**Controller:**
```php
public function create()
{
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }
    return Inertia::render('Agenda/Create');
}
```

---

### 3. All Agendas (View All)

**Blade:** `resources/views/v2/pages/agenda/view-all.blade.php`
**Includes:** `resources/views/v1/ui/agenda/all-agenda.blade.php`
**JS Loads:** `public/js/modules/agendaLoad.js`

**Data Source:** `/agenda-load` endpoint returns JSON

**Vue Structure:**
```
resources/js/Pages/Agenda/Index.vue
```

**Prop Data:**
- agendas (paginated list with concerns_count)

**Controller:**
```php
public function index()
{
    $agendas = Agenda::with(['creator', 'attachments'])
        ->withCount('concerns')
        ->orderBy('date', 'desc')
        ->paginate(20);

    return Inertia::render('Agenda/Index', [
        'agendas' => $agendas
    ]);
}
```

**Vue with Pagination:**
```vue
<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  agendas: Object // paginator
})
</script>

<template>
  <div v-for="agenda in agendas.data" :key="agenda.id">
    <h2>{{ agenda.title }}</h2>
    <p>{{ agenda.status }}</p>
    <p>Concerns: {{ agenda.concerns_count }}</p>
    <Link :href="`/app/view-agenda/${agenda.id}`">View</Link>
  </div>

  <!-- Pagination -->
  <Link v-if="agendas.prev_page_url" :href="agendas.prev_page_url">
    Previous
  </Link>
  <Link v-if="agendas.next_page_url" :href="agendas.next_page_url">
    Next
  </Link>
</template>
```

---

### 4. View Agenda Detail

**Blade:** `resources/views/v2/pages/agenda/view-all.blade.php` (view with id)
**Route:** `/app/view-agenda/{agenda_id}`
**Controller:** `AgendaController@clickedAgenda`
**JS:** `public/js/modules/selectedAgendaMo.js`

**Data Source:** Single agenda with attachments and concerns

**Vue Structure:**
```
resources/js/Pages/Agenda/View.vue
```

**Prop Data:**
- agenda (title, date, notes, status, attachments, creator)
- attachment (file path)
- concerns (list)

**Controller:**
```php
public function clickedAgenda($id)
{
    $agenda = Agenda::with(['creator', 'attachments', 'concerns.responsible'])
        ->findOrFail($id);

    return Inertia::render('Agenda/View', [
        'agenda' => $agenda,
    ]);
}
```

---

### 5. Edit Agenda

**Blade:** `resources/views/v2/pages/agenda/edit.blade.php`
**Vue Structure:**
```
resources/js/Pages/Agenda/Edit.vue
```

**Form Fields:**
- title (text)
- date (date)
- notes (textarea)
- status (select: pending, ongoing, resolved, closed)
- file_path (file)

**Controller:**
```php
public function edit($id)
{
    $agenda = Agenda::findOrFail($id);
    $user = auth()->user();

    // Only admins or creator can edit
    if ($user->role !== 'admin' && $user->id !== $agenda->created_by) {
        abort(403);
    }

    return Inertia::render('Agenda/Edit', [
        'agenda' => $agenda
    ]);
}
```

---

### 6. All Concerns

**Blade:** `resources/views/v2/pages/concerns/all-concerns.blade.php`
**Includes:** `v2.ui.concerns.all-concerns`
**JS:** `public/js/modules/concernLoad.js`

**Data Source:** `/concerns/all`

**Vue Structure:**
```
resources/js/Pages/Concerns/Index.vue
```

**Prop Data:**
- concerns (paginated list with agenda, responsible, comment_list_count)

**Controller:**
```php
public function allConcerns()
{
    $concerns = Concern::with(['agenda', 'responsible'])
        ->withCount('commentList')
        ->latest()
        ->paginate(20);

    return Inertia::render('Concerns/Index', [
        'concerns' => $concerns
    ]);
}
```

---

### 7. Create Concern

**Blade:** `resources/views/v2/pages/concerns/create.blade.php`
**Includes:** `v2.ui.concerns.create`

**Vue Structure:**
```
resources/js/Pages/Concerns/Create.vue
```

**Form Fields:**
- agenda_id (hidden or dropdown)
- description (textarea)
- responsible_person_id (select dropdown - users with admin/member role)
- status (select: pending, ongoing, completed)
- due_date (date)
- file (file upload)

**Controller:**
```php
public function create($agenda_id)
{
    $agenda = Agenda::findOrFail($agenda_id);
    $users = User::whereIn('role', ['admin', 'member'])->get(['id', 'name']);

    return Inertia::render('Concerns/Create', [
        'agenda' => $agenda,
        'users' => $users
    ]);
}
```

---

### 8. View Concern Detail (with Comments)

**Blade:** `resources/views/v2/pages/concerns/comments.blade.php`
**JS:** `public/js/modules/commentLoad.js`

**Route:** `/app/concerns/{concern_id}/comments`

**Vue Structure:**
```
resources/js/Pages/Concerns/View.vue
```

**Prop Data:**
- concern (with responsible, agenda, attachments, commentList)
- comments (list)

**Controller:**
```php
public function index($concern_id)
{
    $concern = Concern::with(['responsible', 'agenda', 'attachments', 'commentList.user'])
        ->findOrFail($concern_id);

    return Inertia::render('Concerns/View', [
        'concern' => $concern,
    ]);
}
```

---

### 9. My Concerns

**Blade:** `resources/views/v2/pages/concerns/my-concerns.blade.php`
**JS:** `public/js/modules/myconcernLoad.js`

**Data Source:** `/concerns/your`

**Vue Structure:**
```
resources/js/Pages/Concerns/MyConcerns.vue
```

Controller:
```php
public function yourConcerns()
{
    $user = auth()->user();
    
    $concerns = Concern::with(['agenda', 'responsible'])
        ->where('responsible_person_id', $user->id)
        ->latest()
        ->paginate(20);

    return Inertia::render('Concerns/MyConcerns', [
        'concerns' => $concerns
    ]);
}
```

---

### 10. Profile Settings

**Blade:** `resources/views/v2/pages/settings/profile.blade.php`
**Vue Structure:**
```
resources/js/Pages/Settings/Profile.vue
```

**Form Fields:**
- name
- email
- (password update handled separately)

**Controller:**
```php
public function edit()
{
    return Inertia::render('Settings/Profile', [
        'user' => auth()->user()
    ]);
}
```

---

### 11. Users List (Admin)

**Blade:** `resources/views/v2/pages/people/users.blade.php`
**Vue Structure:**
```
resources/js/Pages/Admin/Users.vue
```

**Controller:**
```php
public function index()
{
    if (auth()->user()->role !== 'admin') {
        abort(403);
    }
    
    $users = User::all();
    
    return Inertia::render('Admin/Users', [
        'users' => $users
    ]);
}
```

---

### 12. Membership Requests

**Blade:** `resources/views/v2/pages/people/memberships.blade.php`
**Data Source:** `/membership-requests/get-all`

**Vue Structure:**
```
resources/js/Pages/Admin/MembershipRequests.vue
```

---

### 13. Calendar

**Blade:** `resources/views/v2/pages/calendar.blade.php`
**JS:** `public/js/modules/calendar.js`

**Data Source:** JSON for calendar events

**Vue Structure:**
```
resources/js/Pages/Calendar.vue
```

---

### 14. Archives/History

**Blade:** `resources/views/v2/pages/archives/history.blade.php`
**Vue Structure:**
```
resources/js/Pages/Archives/History.vue
```

---

### 15. Trash (Trashed Agendas)

**Blade:** `resources/views/v2/pages/trash/agendas-arc.blade.php`
**JS:** `public/js/modules/trashAgendas.js`

**Data Source:** `/@gend4/trash-agenda`

**Vue Structure:**
```
resources/js/Pages/Trash/Agendas.vue
```

---

### 16. Login Page

**Blade:** `resources/views/v2/pages/login-page.blade.php`

Laravel Breeze provides this. Just configure Inertia in routes:

```php
Route::get('/login', fn() => Inertia::render('Auth/Login'))
    ->name('login');
```

---

## Form Handling

### Using Inertia useForm

```javascript
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
      // Optionally redirect
    }
  })
}
```

### File Upload

```javascript
const form = useForm({
  title: '',
  file: null
})

const handleFile = (event) => {
  form.file = event.target.files[0]
}

const submit = () => {
  form.post('/agendas', {
    forceFormData: true
  })
}
```

---

## Navigation with Inertia

### Using Link Component

```vue
<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <Link href="/app/home">Home</Link>
  <Link :href="`/app/view-agenda/${agenda.id}`">View</Link>
  <Link href="/app/create-agenda" class="btn-primary">Create</Link>
</template>
```

### Programmatic Redirect

```javascript
import { useForm } from '@inertiajs/vue3'

const form = useForm({...})

form.post('/agendas', {
  onSuccess: () => {
    // Inertia auto-scopes flashes
  }
})
```

---

## Handling Flash Messages

In Laravel controller:
```php
return Inertia::back()->with('success', 'Agenda created!');
```

In Vue Layout:
```vue
<script setup>
import { usePage } from '@inertiajs/vue3'
const page = usePage()
</script>

<template>
  <div v-if="$page.props.flash.success" class="alert">
    {{ $page.props.flash.success }}
  </div>
</template>
```

---

## Complete Vue Page Structure

```
resources/js/
├── Pages/
│   ├── Agenda/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   ├── Edit.vue
│   │   └── View.vue
│   ├── Concerns/
│   │   ├── Index.vue
│   │   ├── Create.vue
│   │   ├── View.vue
│   │   └── MyConcerns.vue
│   ├── Dashboard.vue
│   ├── Calendar.vue
│   ├── Trash/
│   │   ├── Agendas.vue
│   │   └── Concerns.vue
│   ├── Admin/
│   │   ├── Users.vue
│   │   └── MembershipRequests.vue
│   └── Settings/
│       └── Profile.vue
├── Layouts/
│   ├── AppLayout.vue
│   └── GuestLayout.vue
└── app.js
```

---

## Route Mapping

| Old Route | New Inertia Route |
|-----------|-----------------|
| `/app/home` | Dashboard |
| `/app/create-agenda` | Agenda/Create |
| `/app/view-agenda` | Agenda/Index |
| `/app/view-agenda/{id}` | Agenda/View |
| `/app/edit-agenda/{id}` | Agenda/Edit |
| `/app/concerns` | Concerns/Index |
| `/app/concerns/{id}/raise-concern` | Concerns/Create |
| `/app/concerns/{id}/comments` | Concerns/View |
| `/app/concerns/me` | Concerns/MyConcerns |
| `/app/profile` | Settings/Profile |
| `/app/users` | Admin/Users |
| `/app/memberships` | Admin/MembershipRequests |
| `/app/calendar` | Calendar |
| `/app/trash-agenda` | Trash/Agendas |
| `/app/trash-concern` | Trash/Concerns |

---

## Data Transformation

### For Status Badges

Create a utility:

```javascript
// resources/js/utils/status.js
export const statusColors = {
  pending: 'bg-amber-500',
  ongoing: 'bg-blue-500',
  resolved: 'bg-green-500',
  closed: 'bg-gray-500',
  completed: 'bg-gray-500'
}

export const statusLabels = {
  pending: 'Pending',
  ongoing: 'Ongoing',
  resolved: 'Resolved',
  closed: 'Closed',
  completed: 'Completed'
}
```

### For Date Formatting

Create a utility:

```javascript
// resources/js/utils/date.js
export function formatDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

export function formatLongDate(date) {
  return new Date(date).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
```

### For Time Ago

```javascript
// resources/js/utils/timeAgo.js
export function timeAgo(date) {
  const seconds = Math.floor((new Date() - new Date(date)) / 1000)
  
  const intervals = {
    year: 31536000,
    month: 2592000,
    week: 604800,
    day: 86400,
    hour: 3600,
    minute: 60
  }
  
  for (const [unit, secondsInUnit] of Object.entries(intervals)) {
    const interval = Math.floor(seconds / secondsInUnit)
    if (interval >= 1) {
      return `${interval} ${unit}${interval > 1 ? 's' : ''} ago`
    }
  }
  return 'Just now'
}
```

---

## Loading Data in Vue Components

### Direct Loading (without Inertia props)

If you need to load data on button click:

```vue
<script setup>
import { router } from '@inertiajs/vue3'

const loadData = () => {
  router.get('/concerns/all', {}, {
    preserveState: true,
    onSuccess: (page) => {
      // Handle response
    }
  })
}
</script>

<template>
  <button @click="loadData">Load</button>
</template>
```

### With useForm for Updates

```vue
<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  agenda: Object
})

const form = useForm({
  title: props.agenda?.title || '',
  notes: props.agenda?.notes || '',
  status: props.agenda?.status || 'pending'
})

const update = () => {
  form.put(`/agendas/${props.agenda.id}`, {
    onSuccess: () => {
      // success
    }
  })
}
</script>
```

---

## Summary

| Current | Inertia |
|---------|---------|
| `view('page')` | `Inertia::render('Page')` |
| `return redirect()` | `return Inertia::back()` or `Inertia::render()` |
| JS fetch for data | Pass data as props |
| Blade `@if` in view | Vue `v-if` in component |
| Blade `@foreach` | Vue `v-for` |
| Form post to Laravel | `useForm().post()` |
| `asset()` for JS | Import directly in Vue |
| Flash messages | `$page.props.flash` |

---

## File Structure Summary

Old files to convert: ~90 blade files

Main pages to Vue:
- Dashboard
- Agenda (Index, Create, Edit, View)
- Concerns (Index, Create, View, MyConcerns)
- Calendar
- Archive/History
- Trash
- Admin (Users, Memberships)
- Settings (Profile)

Components (already use similar structure in v2/):
- header.vue
- app-nav.vue
- notification.vue
- confirm-modal.vue