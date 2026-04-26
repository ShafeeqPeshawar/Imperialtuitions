<x-app-layout>
    <div class="admin-wrap min-h-screen flex">
        <!-- Sidebar: Imperial Tuitions branding (teal) -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-brand">
                <span class="admin-brand-main">Imperial</span>
                <span class="admin-brand-accent">Tuitions</span>
            </div>

            <nav class="admin-nav">
                <a href="{{ route('dashboard') }}"
                   class="admin-nav-item {{ request()->routeIs('dashboard') ? 'admin-nav-item-active' : '' }}">
                    Dashboard
                </a>
                <div class="admin-nav-label">Your Courses</div>
                <a href="{{ route('admin.courses.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.courses.index') ? 'admin-nav-item-active' : '' }}">
                    Manage Courses
                </a>
                <a href="{{ route('admin.courses.popular') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.courses.popular') ? 'admin-nav-item-active' : '' }}">
                    Popular Courses
                </a>
                <a href="{{ route('admin.course-launches.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.course-launches.*') ? 'admin-nav-item-active' : '' }}">
                    Launch Free Courses
                </a>
                <a href="{{ route('training.index') }}"
                   class="admin-nav-item {{ request()->routeIs('training.*') ? 'admin-nav-item-active' : '' }}">
                    Training gallery
                </a>

                <div class="admin-nav-label">Enrollments & Inquiries</div>
                <a href="{{ route('admin.course-enrollments.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.course-enrollments.*') ? 'admin-nav-item-active' : '' }}">
                    Course Enrollments
                    @if($pendingEnrollmentsCount > 0)
                        <span class="admin-nav-badge">{{ $pendingEnrollmentsCount }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.course-inquiries.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.course-inquiries.*') ? 'admin-nav-item-active' : '' }}">
                    Course Inquiries
                    @if($pendingInquiriesCount > 0)
                        <span class="admin-nav-badge">{{ $pendingInquiriesCount }}</span>
                    @endif
                </a>

                <div class="admin-nav-label">Contact Us</div>
                <a href="{{ route('admin.contacts.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.contacts.*') ? 'admin-nav-item-active' : '' }}">
                    Contact Leads
                    @if($pendingContactsCount > 0)
                        <span class="admin-nav-badge">{{ $pendingContactsCount }}</span>
                    @endif
                </a>
                <div class="admin-nav-label">Newsletter</div>
                <a href="{{ route('admin.subscribers.index') }}"
                   class="admin-nav-item {{ request()->routeIs('admin.subscribers.*') ? 'admin-nav-item-active' : '' }}">
                    Subscribers
                </a>
            </nav>

            <div class="admin-sidebar-footer">
                © {{ date('Y') }} Imperial Tuitions
            </div>
        </aside>

        <!-- Main content: light, clean -->
        <main class="admin-main">
            <header class="admin-header">
                <h1 class="admin-header-title">{{ $title ?? 'Admin' }}</h1>
            </header>
            <div class="admin-content">
                @yield('content')
                @stack('scripts')
            </div>
        </main>
    </div>

    <style>
        .admin-wrap { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; }
        .admin-sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #09515D 0%, #0a6573 100%);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            box-shadow: 4px 0 24px rgba(9, 81, 93, 0.2);
        }
        .admin-sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .admin-brand-main { font-size: 20px; font-weight: 800; color: #fff; letter-spacing: -0.02em; }
        .admin-brand-accent { font-size: 20px; font-weight: 800; color: #F47B1E; letter-spacing: -0.02em; margin-left: 2px; }
        .admin-nav { flex: 1; padding: 20px 12px; overflow-y: auto; }
        .admin-nav-label {
            font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em;
            color: rgba(255,255,255,0.6); margin: 20px 12px 8px; padding-left: 4px;
        }
        .admin-nav-item {
            display: flex; align-items: center; gap: 8px;
            padding: 12px 16px; margin-bottom: 4px;
            border-radius: 10px;
            color: rgba(255,255,255,0.95);
            text-decoration: none;
            font-weight: 600; font-size: 15px;
            transition: background 0.2s, color 0.2s;
        }
        .admin-nav-item:hover { background: rgba(255,255,255,0.15); color: #fff; }
        .admin-nav-item-active { background: #F47B1E; color: #0f172a; }
        .admin-nav-item-active:hover { background: #f59e0b; color: #0f172a; }
        .admin-nav-badge {
            margin-left: auto;
            background: #F47B1E; color: #0f172a;
            font-size: 12px; font-weight: 700;
            padding: 2px 8px; border-radius: 999px;
        }
        .admin-nav-item-active .admin-nav-badge { background: #0f172a; color: #F47B1E; }
        .admin-sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255,255,255,0.15);
            font-size: 12px; color: rgba(255,255,255,0.7);
        }
        .admin-main { flex: 1; min-height: 100vh; background: #f1f5f9; display: flex; flex-direction: column; }
        .admin-header {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 28px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        .admin-header-title { font-size: 22px; font-weight: 700; color: #0f172a; margin: 0; }
        .admin-content { padding: 28px; flex: 1; }
    </style>
</x-app-layout>
