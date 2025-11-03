 @php
     $currentRoute = Route::currentRouteName();
 @endphp
 <div class="sidebar" id="sidebar">
     <!-- Close button for mobile -->
     <div class="d-flex justify-content-between align-items-center mb-4 d-md-none">
         <h4 class="mb-0">CapliX</h4>
         <button class="btn btn-outline-light btn-sm" id="sidebarClose">
             <i class="fa-solid fa-xmark"></i>
         </button>
     </div>

     <!-- Super Admin at top -->
     <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 text-white fw-bold">
         <i class="fa-solid fa-user-shield me-2"></i> {{ Auth::user()->name ?? '' }}
     </a>

     <!-- Dashboard Overview -->
     <a href="{{ route('admin.dashboard') }}"
         class="d-flex align-items-center mb-2 {{ Route::is('admin.dashboard') ? 'active' : '' }}">
         <i class="fa-solid fa-house me-2"></i> Dashboard Overview
     </a>




     <!-- One menu after Dashboard -->
     <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-chart-simple me-2"></i> Overview Details</a>

     <!-- Investor submenu -->
     <div class="has-submenu">
         <a href="#"><i class="fa-solid fa-users me-2"></i> Investor</a>
         <div class="submenu">
             <a href="allinvestor.html"><i class="fa-solid fa-list me-2"></i> All Investors</a>
             <a href="addinvestor.html"><i class="fa-solid fa-user-plus me-2"></i> Add Investor</a>
         </div>
     </div>

     <!-- Projects submenu -->
     <div
         class="has-submenu {{ in_array($currentRoute, ['admin.project.index', 'admin.project.create']) ? 'active' : '' }}">
         <a href="#"><i class="fa-solid fa-briefcase me-2"></i> Projects </a>
         <div class="submenu">
             <!-- All Projects -->
             <a href="{{ route('admin.project.index') }}"
                 class="{{ $currentRoute == 'admin.projects.index' ? 'active' : '' }}">
                 <i class="fa-solid fa-list me-2"></i> All Projects
             </a>

             <!-- Add Project -->
             <a href="{{ route('admin.project.create') }}"
                 class="{{ $currentRoute == 'admin.projects.create' ? 'active' : '' }}">
                 <i class="fa-solid fa-plus-circle me-2"></i> Add Project
             </a>
         </div>
     </div>

     <!-- Remaining menu items (placeholders) -->
     <a href="#"><i class="fa-solid fa-coins me-2"></i> Withdrawal Requests</a>
     <a href="#"><i class="fa-solid fa-shield-halved me-2"></i> KYC Verification</a>
     <a href="#"><i class="fa-solid fa-file-lines me-2"></i> Reports & Audit</a>
     <a href="#"><i class="fa-solid fa-gear me-2"></i> Settings</a>
     <a href="#"><i class="fa-solid fa-chart-line me-2"></i> Analytics</a>
     <a href="#"><i class="fa-solid fa-users-cog me-2"></i> User Management</a>
     <a href="#"><i class="fa-solid fa-folder-open me-2"></i> Documents</a>
     <a href="#"><i class="fa-solid fa-envelope me-2"></i> Messages</a>
     <a href="#"><i class="fa-solid fa-bell me-2"></i> Notifications</a>
     <a href="#"><i class="fa-solid fa-comments me-2"></i> Feedback</a>
     <a href="#"><i class="fa-solid fa-credit-card me-2"></i> Payments</a>
     <a href="#"><i class="fa-solid fa-wallet me-2"></i> Wallet</a>
     <a href="#"><i class="fa-solid fa-cogs me-2"></i> System Settings</a>
     <a href="#"><i class="fa-solid fa-calendar me-2"></i> Calendar</a>
     <a href="#"><i class="fa-solid fa-file-invoice me-2"></i> Invoices</a>
     <a href="#"><i class="fa-solid fa-question-circle me-2"></i> Help Center</a>

     <!-- Logout always last -->
     <a href="{{ route('admin.logout') }}"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
 </div>
 </div>
