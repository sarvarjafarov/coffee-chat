<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CoffeeChat OS') }} — Admin</title>

    @include('layouts.partials.analytics')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="icon" type="image/svg+xml" sizes="any" href="{{ asset('favicon.svg?v=2') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg?v=2') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg?v=2') }}">
    @include('components.feedback-widget', ['pageTitle' => config('app.name', 'CoffeeChat OS').' Admin', 'pagePath' => request()->path()])
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link" target="_blank">View site</a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('admin.coffee-chats.index') }}" class="brand-link">
            <span class="brand-text font-weight-light">{{ config('app.name', 'CoffeeChat OS') }} Admin</span>
        </a>

        <div class="sidebar">
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">{{ auth()->user()?->name }}</a>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('admin.coffee-chats.index') }}" class="nav-link {{ request()->routeIs('admin.coffee-chats.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mug-hot"></i>
                            <p>Coffee Chats</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.companies.index') }}" class="nav-link {{ request()->routeIs('admin.companies.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-building"></i>
                            <p>Companies</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.contacts.index') }}" class="nav-link {{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>Contacts</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.channels.index') }}" class="nav-link {{ request()->routeIs('admin.channels.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-share-alt"></i>
                            <p>Channels</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.pages.index') }}" class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>Pages</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.seo.index') }}" class="nav-link {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-search"></i>
                            <p>SEO</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.workspace-fields.index') }}" class="nav-link {{ request()->routeIs('admin.workspace-fields.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Workspace fields</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.network-health.index') }}" class="nav-link {{ request()->routeIs('admin.network-health.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-heartbeat"></i>
                            <p>Network health</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.attribution.index') }}" class="nav-link {{ request()->routeIs('admin.attribution.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-project-diagram"></i>
                            <p>Attribution</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-comment-dots"></i>
                            <p>Feedback inbox</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.menu.index') }}" class="nav-link {{ request()->routeIs('admin.menu.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-link"></i>
                            <p>Menu items</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.site-menu.index') }}" class="nav-link {{ request()->routeIs('admin.site-menu.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-bars"></i>
                            <p>Header menu</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.stripe.settings') }}" class="nav-link {{ request()->routeIs('admin.stripe.settings') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Stripe integration</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.posts.index') }}" class="nav-link {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Posts</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div>
                    <div class="col-sm-6">
                        @yield('actions')
                    </div>
                </div>
                @if(session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer text-sm text-center">
        <strong>&copy; {{ now()->year }} {{ config('app.name', 'CoffeeChat OS') }}.</strong> All rights reserved.
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
