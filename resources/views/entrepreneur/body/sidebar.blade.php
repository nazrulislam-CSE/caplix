 <div class="sidebar" id="sidebar">
     <!-- Close button for mobile -->
     <div class="d-flex justify-content-between align-items-center mb-4 d-md-none">
         <h4 class="mb-0">CapliX</h4>
         <button class="btn btn-outline-light btn-sm" id="sidebarClose">
             <i class="fa-solid fa-xmark"></i>
         </button>
     </div>

     <!-- Super Admin at top -->
     <a href="{{ route('entrepreneur.dashboard') }}" class="d-flex align-items-center mb-3 text-white fw-bold">
         <i class="fa-solid fa-user-shield me-2"></i> {{ Auth::user()->name ?? '' }}
     </a>

     <!-- Dashboard Overview -->
        <a href="{{ route('entrepreneur.dashboard') }}" 
        class="d-flex align-items-center mb-2 {{ Route::is('entrepreneur.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house me-2"></i> Dashboard Overview
        </a>




     <!-- One menu after Dashboard -->
     <a href="{{ route('entrepreneur.dashboard') }}"><i class="fa-solid fa-chart-simple me-2"></i> Overview Details</a>

     <!-- Investor submenu -->
     <div class="has-submenu">
         <a href="#"><i class="fa-solid fa-users me-2"></i> Entrepreneur</a>
         <div class="submenu">
             <a href="allinvestor.html"><i class="fa-solid fa-list me-2"></i> All Entrepreneur</a>
             <a href="addinvestor.html"><i class="fa-solid fa-user-plus me-2"></i> Add Entrepreneur</a>
         </div>
     </div>
     
     <!-- Remaining menu items (placeholders) -->
     <a href="#"><i class="fa-solid fa-coins me-2"></i> Withdrawal Requests</a>
     <a href="#"><i class="fa-solid fa-shield-halved me-2"></i> KYC Verification</a>
     <a href="#"><i class="fa-solid fa-file-lines me-2"></i> Reports & Audit</a>
     <a href="#"><i class="fa-solid fa-gear me-2"></i> Settings</a>
     <a href="#"><i class="fa-solid fa-credit-card me-2"></i> Payments</a>
     <a href="#"><i class="fa-solid fa-wallet me-2"></i> Wallet</a>
     <!-- Logout always last -->
     <a href="{{ route('entrepreneur.logout') }}"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</a>
 </div>
 </div>
