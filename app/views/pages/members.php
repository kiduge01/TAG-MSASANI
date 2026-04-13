<?php $B = $baseUrl ?? ''; ?>

<!--  Page Header  -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h1 class="text-3xl font-heading font-semibold text-royal-900">Church Members</h1>
        <p class="text-mist-600 text-sm mt-0.5">Register, manage and import congregation members.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button id="btn-tab-members" class="px-4 py-2 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold tab-btn" data-tab="members">👥 Members</button>
        <button id="btn-tab-guests" class="px-4 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-semibold tab-btn" data-tab="guests">🧑 Guests</button>
    </div>
</div>

<!-- Members Tab Section -->
<div id="tab-content-members" class="tab-content">

<!--  Page Header  -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-2xl font-heading font-semibold text-royal-900">Members List</h2>
        <p class="text-mist-600 text-sm mt-0.5">Register, manage and import congregation members.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button id="btn-export-csv" class="px-4 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">&#11015; Export CSV</button>
        <button id="btn-open-import" class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold">&#8679; Import Excel / CSV</button>
        <button id="btn-open-add" class="px-4 py-2 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">+ Add Member</button>
    </div>
</div>

<!--  Stats Bar  -->
<div id="stats-bar" class="space-y-3 mb-6">
    <!-- Status Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-mist-500 uppercase tracking-wide">Total</p><p id="stat-total" class="text-2xl font-bold text-royal-800 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-emerald-600 uppercase tracking-wide">Active</p><p id="stat-active" class="text-2xl font-bold text-emerald-700 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-mist-500 uppercase tracking-wide">Inactive</p><p id="stat-inactive" class="text-2xl font-bold text-mist-600 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-dawn-600 uppercase tracking-wide">Transferred</p><p id="stat-transferred" class="text-2xl font-bold text-dawn-700 mt-1">--</p></div>
    </div>
    <!-- Demographics Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-blue-50 rounded-2xl border border-blue-200 p-4"><p class="text-xs text-blue-600 uppercase tracking-wide font-semibold">👨 Men</p><p id="stat-men" class="text-2xl font-bold text-blue-700 mt-1">--</p></div>
        <div class="bg-pink-50 rounded-2xl border border-pink-200 p-4"><p class="text-xs text-pink-600 uppercase tracking-wide font-semibold">👩 Women</p><p id="stat-women" class="text-2xl font-bold text-pink-700 mt-1">--</p></div>
        <div class="bg-yellow-50 rounded-2xl border border-yellow-200 p-4"><p class="text-xs text-yellow-600 uppercase tracking-wide font-semibold">👧 Children</p><p id="stat-children" class="text-2xl font-bold text-yellow-700 mt-1">--</p></div>
        <div class="bg-orange-50 rounded-2xl border border-orange-200 p-4"><p class="text-xs text-orange-600 uppercase tracking-wide font-semibold">👴 Elders</p><p id="stat-elders" class="text-2xl font-bold text-orange-700 mt-1">--</p></div>
    </div>
</div>

<!--  Filters  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm px-4 py-3 mb-4">
    <div class="flex flex-wrap gap-2">
        <input id="filter-search" type="text" placeholder="Search name, phone, code, email" class="flex-1 min-w-48 rounded-xl border border-mist-200 px-3 py-2 text-sm">
        <select id="filter-status" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
            <option value="transferred">Transferred</option>
            <option value="deceased">Deceased</option>
        </select>
        <select id="filter-gender" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>
        <select id="filter-region" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Regions</option>
        </select>
        <button id="btn-clear-filters" class="px-3 py-2 rounded-xl bg-mist-100 text-mist-600 hover:bg-mist-200 text-sm">Clear</button>
    </div>
</div>

<!--  Members Table  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-mist-100 flex flex-wrap items-center justify-between gap-2">
        <h2 class="font-semibold text-royal-800">Members List</h2>
        <span id="member-count" class="text-xs text-mist-500"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Full Name</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Phone</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Gender</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Region</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Status</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Joined</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody id="members-tbody" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="members-empty" class="hidden px-5 py-14 text-center text-mist-400">
        <p class="text-4xl mb-2">&#128100;</p>
        <p class="font-semibold text-mist-600">No members found</p>
        <p class="text-sm mt-1">Add members manually or import from Excel/CSV.</p>
    </div>
</div>

</div>
<!-- End Members Tab -->

<!-- Guests Tab Section -->
<div id="tab-content-guests" class="tab-content hidden">

<!--  Guests Header  -->
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
        <h2 class="text-2xl font-heading font-semibold text-royal-900">Guest Registry</h2>
        <p class="text-mist-600 text-sm mt-0.5">Track and manage all guest registrations.</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button id="btn-export-guests-csv" class="px-4 py-2 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">&#11015; Export CSV</button>
    </div>
</div>

<!--  Guests Stats Bar  -->
<div id="guests-stats-bar" class="space-y-3 mb-6">
    <!-- Status Row -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-mist-500 uppercase tracking-wide">Total</p><p id="stat-guests-total" class="text-2xl font-bold text-royal-800 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-emerald-600 uppercase tracking-wide">Registered</p><p id="stat-guests-registered" class="text-2xl font-bold text-emerald-700 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-blue-600 uppercase tracking-wide">Visited</p><p id="stat-guests-visited" class="text-2xl font-bold text-blue-700 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-purple-600 uppercase tracking-wide">Converted</p><p id="stat-guests-converted" class="text-2xl font-bold text-purple-700 mt-1">--</p></div>
        <div class="bg-white rounded-2xl border border-mist-200 p-4"><p class="text-xs text-dawn-600 uppercase tracking-wide">Inactive</p><p id="stat-guests-inactive" class="text-2xl font-bold text-dawn-700 mt-1">--</p></div>
    </div>
    <!-- Age Group Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        <div class="bg-yellow-50 rounded-2xl border border-yellow-200 p-4"><p class="text-xs text-yellow-600 uppercase tracking-wide font-semibold">👧 Children</p><p id="stat-guests-children" class="text-2xl font-bold text-yellow-700 mt-1">--</p></div>
        <div class="bg-cyan-50 rounded-2xl border border-cyan-200 p-4"><p class="text-xs text-cyan-600 uppercase tracking-wide font-semibold">🧑 Youth</p><p id="stat-guests-youth" class="text-2xl font-bold text-cyan-700 mt-1">--</p></div>
        <div class="bg-emerald-50 rounded-2xl border border-emerald-200 p-4"><p class="text-xs text-emerald-600 uppercase tracking-wide font-semibold">👨 Adults</p><p id="stat-guests-adults" class="text-2xl font-bold text-emerald-700 mt-1">--</p></div>
        <div class="bg-orange-50 rounded-2xl border border-orange-200 p-4"><p class="text-xs text-orange-600 uppercase tracking-wide font-semibold">👴 Elders</p><p id="stat-guests-elders" class="text-2xl font-bold text-orange-700 mt-1">--</p></div>
    </div>
</div>

<!--  Guests Filters  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm px-4 py-3 mb-4">
    <div class="flex flex-wrap gap-2">
        <input id="filter-guests-search" type="text" placeholder="Search code, name, phone, email" class="flex-1 min-w-48 rounded-xl border border-mist-200 px-3 py-2 text-sm">
        <select id="filter-guests-status" class="rounded-xl border border-mist-200 px-3 py-2 text-sm">
            <option value="">All Status</option>
            <option value="registered">Registered</option>
            <option value="visited">Visited</option>
            <option value="converted">Converted</option>
            <option value="inactive">Inactive</option>
        </select>
        <button id="btn-clear-guest-filters" class="px-3 py-2 rounded-xl bg-mist-100 text-mist-600 hover:bg-mist-200 text-sm">Clear</button>
    </div>
</div>

<!--  Guests Table  -->
<div class="bg-white rounded-2xl border border-mist-200 shadow-sm overflow-hidden">
    <div class="px-5 py-3.5 border-b border-mist-100 flex flex-wrap items-center justify-between gap-2">
        <h3 class="font-semibold text-royal-800">Guest List</h3>
        <span id="guests-count" class="text-xs text-mist-500"></span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-mist-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Code</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Full Name</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Phone</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Email</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Service Date</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Visit Type</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Location</th>
                    <th class="px-4 py-3 text-left text-xs uppercase tracking-wider text-mist-500">Status</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody id="guests-tbody" class="divide-y divide-mist-100"></tbody>
        </table>
    </div>
    <div id="guests-empty" class="hidden px-5 py-14 text-center text-mist-400">
        <p class="text-4xl mb-2">🧑</p>
        <p class="font-semibold text-mist-600">No guests found</p>
        <p class="text-sm mt-1">Guest registrations will appear here.</p>
    </div>
</div>

</div>
<!-- End Guests Tab -->

<!-- 
     ADD / EDIT MEMBER MODAL
 -->
<div id="member-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen pt-10 pb-10 px-4">
        <div class="fixed inset-0 bg-black/40" id="member-modal-bg"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-mist-100">
                <h3 id="modal-title" class="text-lg font-heading font-semibold text-royal-900">Add New Member</h3>
                <button id="btn-close-member-modal" class="p-1.5 rounded-lg hover:bg-mist-100 text-mist-500">&#10005;</button>
            </div>
            <form id="member-form" class="px-6 py-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" id="edit-member-id" value="">
                <!-- Row 1 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">First Name <span class="text-red-500">*</span></label>
                    <input name="first_name" required placeholder="First name" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input name="last_name" required placeholder="Last name / Surname" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 2 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Phone <span class="text-red-500">*</span></label>
                    <input name="phone" required placeholder="+255 7XX XXX XXX" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 3 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Email</label>
                    <input type="email" name="email" placeholder="email@example.com" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Member Code <span class="text-mist-400 font-normal">(auto if blank)</span></label>
                    <input name="member_code" placeholder="MBR-2026-0001" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm font-mono">
                </div>
                <!-- Row 4 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Join Date</label>
                    <input type="date" name="join_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 5 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Marital Status</label>
                    <select name="marital_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="single">Single</option>
                        <option value="married">Married</option>
                        <option value="widowed">Widowed</option>
                        <option value="divorced">Divorced</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Baptism Date</label>
                    <input type="date" name="baptism_date" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 6 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Ward / Mtaa</label>
                    <input name="ward" placeholder="Mtaa / Ward" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">District / Wilaya</label>
                    <input name="district" placeholder="District" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 7 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">City / Village (Mji/Kijiji)</label>
                    <input name="city_village" placeholder="Ubungo, Kinondoni..." class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Country (Nchi)</label>
                    <input name="country" placeholder="Tanzania" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm" value="Tanzania">
                </div>
                <!-- Row 8 -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Region / Mkoa</label>
                    <input name="region" placeholder="Region" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Status</label>
                    <select name="member_status" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="transferred">Transferred</option>
                        <option value="deceased">Deceased</option>
                    </select>
                </div>
                <!-- Row 9 - Education & Job -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Education Level (Kiwango cha Elimu)</label>
                    <select name="education_level" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="primary">Primary School</option>
                        <option value="secondary">Secondary School</option>
                        <option value="diploma">Diploma</option>
                        <option value="bachelor">Bachelor's Degree</option>
                        <option value="masters">Master's Degree</option>
                        <option value="phd">PhD / Doctorate</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Job Title (Cheo Kazi)</label>
                    <input name="job_title" placeholder="Position / Majukumu Kazini" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 10 - Emergency Contact Details -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Emergency Contact Email (Baruapepe ya Dharura)</label>
                    <input type="email" name="emergency_contact_email" placeholder="emergency@example.com" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Relationship (Uhusiano)</label>
                    <select name="emergency_contact_relationship" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Select</option>
                        <option value="spouse">Spouse (Mke/Mume)</option>
                        <option value="sibling">Sibling (Kaka/Dada)</option>
                        <option value="parent">Parent (Mzazi)</option>
                        <option value="child">Child (Mtoto)</option>
                        <option value="friend">Friend (Rafiki)</option>
                        <option value="relative">Relative (Jamaa)</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <!-- Row 11 - Tithe Information -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Account Number (Nambari Ya Bahasha)</label>
                    <input name="account_number" placeholder="Bank account number" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Monthly Tithe Amount (Kiasi cha Zaka)</label>
                    <input type="number" name="tithe_amount_monthly" placeholder="0.00" step="0.01" min="0" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Row 12 - Service Information -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Pays Tithes Faithfully</label>
                    <select name="pays_tithes_faithfully" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Not specified</option>
                        <option value="1">Yes (Ndiyo)</option>
                        <option value="0">No (Hapana)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Doing Service Fully (Je Unafanya Huduma Yako)</label>
                    <select name="is_doing_service_fully" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                        <option value="">Not specified</option>
                        <option value="1">Yes (Ndiyo)</option>
                        <option value="0">No (Hapana)</option>
                    </select>
                </div>
                <!-- Row 13 - Church Service Details -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Service Level / Status (Daraja la Huduma)</label>
                    <input name="service_level" placeholder="e.g., Leader, Active, Beginner" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Church Services (Huduma Kanisani)</label>
                    <input name="church_services" placeholder="e.g., Choir, Ushering, Prayer Team" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Notes & Other -->
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Alternative Phone 2</label>
                    <input name="alt_phone_2" placeholder="Alternative phone" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Physical Address (Anuani)</label>
                    <input name="physical_address_detailed" placeholder="Detailed street address" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm">
                </div>
                <!-- Notes full width -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-mist-600 mb-1">Notes / Maelezo Mengine</label>
                    <textarea name="notes" rows="2" placeholder="Optional notes" class="w-full rounded-xl border border-mist-200 px-3 py-2.5 text-sm"></textarea>
                </div>
                <!-- Buttons -->
                <div class="md:col-span-2 flex justify-end gap-3 pt-2 border-t border-mist-100">
                    <button type="button" id="btn-cancel-member" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-royal-600 text-white hover:bg-royal-700 text-sm font-semibold">Save Member</button>
                </div>
            </form>
            <div id="member-form-feedback" class="hidden mx-6 mb-4 rounded-xl px-3 py-2 text-sm"></div>
        </div>
    </div>
</div>

<!-- 
     IMPORT MODAL
 -->
<div id="import-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen pt-10 pb-10 px-4">
        <div class="fixed inset-0 bg-black/40" id="import-modal-bg"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-xl z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-mist-100">
                <h3 class="text-lg font-heading font-semibold text-royal-900">&#8679; Import Members</h3>
                <button id="btn-close-import" class="p-1.5 rounded-lg hover:bg-mist-100 text-mist-500">&#10005;</button>
            </div>
            <div class="px-6 py-5 space-y-5">

                <!-- Step 1: Template -->
                <div class="bg-royal-50 border border-royal-200 rounded-xl p-4">
                    <p class="text-sm font-semibold text-royal-800 mb-1">Step 1 &mdash; Download Template</p>
                    <p class="text-xs text-royal-700 mb-3">Download the CSV template. Fill it in Excel or Google Sheets, then save as <strong>.csv</strong> or <strong>.xlsx</strong>.</p>
                    <button id="btn-download-template" class="px-4 py-2 rounded-xl bg-royal-600 text-white text-sm hover:bg-royal-700 font-semibold">&#11015; Download CSV Template</button>
                </div>

                <!-- Step 2: Upload -->
                <div>
                    <p class="text-sm font-semibold text-mist-700 mb-2">Step 2 &mdash; Upload File</p>
                    <label id="drop-zone" class="block cursor-pointer border-2 border-dashed border-mist-300 rounded-xl px-4 py-8 text-center hover:border-royal-400 hover:bg-royal-50/30 transition-colors">
                        <input type="file" id="import-file-input" accept=".csv,.xlsx" class="hidden">
                        <p class="text-3xl mb-2">&#128196;</p>
                        <p class="text-sm font-semibold text-mist-700">Click to choose file or drag &amp; drop here</p>
                        <p class="text-xs text-mist-500 mt-1">Supported: <strong>.csv</strong> and <strong>.xlsx</strong></p>
                        <p id="selected-filename" class="text-sm font-semibold text-royal-700 mt-2 hidden"></p>
                    </label>
                </div>

                <!-- Supported columns reference -->
                <details class="text-xs text-mist-600">
                    <summary class="cursor-pointer font-semibold text-mist-700 hover:text-royal-700">Supported column names (click to expand)</summary>
                    <div class="mt-2 grid grid-cols-2 gap-1 pl-2">
                        <span><strong>first_name</strong> / First Name / Jina</span>
                        <span><strong>last_name</strong> / Surname / Familia</span>
                        <span><strong>gender</strong> / Jinsia</span>
                        <span><strong>phone</strong> / Simu / Mobile</span>
                        <span><strong>email</strong> / Barua Pepe</span>
                        <span><strong>date_of_birth</strong> / DOB / Tarehe</span>
                        <span><strong>join_date</strong> / Joined / Kujiunga</span>
                        <span><strong>member_status</strong> / Status / Hali</span>
                        <span><strong>member_code</strong> / Code / Nambari</span>
                        <span><strong>ward</strong> / Mtaa</span>
                        <span><strong>district</strong> / Wilaya</span>
                        <span><strong>region</strong> / Mkoa</span>
                        <span><strong>city_village</strong> / Mji / Kijiji</span>
                        <span><strong>country</strong> / Nchi</span>
                        <span><strong>marital_status</strong> / Hali ya Ndoa</span>
                        <span><strong>baptism_date</strong> / Tarehe ya Ubatizo</span>
                        <span><strong>education_level</strong> / Elimu</span>
                        <span><strong>job_title</strong> / Cheo Kazi</span>
                        <span><strong>church_services</strong> / Huduma</span>
                        <span><strong>service_level</strong> / Daraja la Huduma</span>
                        <span><strong>pays_tithes_faithfully</strong> / Zaka</span>
                        <span><strong>is_doing_service_fully</strong> / Huduma Kamili</span>
                        <span><strong>account_number</strong> / Bahasha</span>
                        <span><strong>tithe_amount_monthly</strong> / Kiasi Zaka</span>
                        <span><strong>emergency_contact_relationship</strong> / Uhusiano</span>
                        <span><strong>emergency_contact_email</strong> / Dharura Email</span>
                        <span><strong>notes</strong> / Maelezo</span>
                    </div>
                </details>

                <!-- Import Button -->
                <div class="flex justify-end gap-3 pt-2 border-t border-mist-100">
                    <button id="btn-cancel-import" class="px-4 py-2.5 rounded-xl bg-mist-100 text-mist-700 hover:bg-mist-200 text-sm font-medium">Cancel</button>
                    <button id="btn-do-import" disabled class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 text-sm font-semibold disabled:opacity-40 disabled:cursor-not-allowed">Import Members</button>
                </div>

                <!-- Result -->
                <div id="import-result" class="hidden rounded-xl px-4 py-3 text-sm"></div>
                <div id="import-errors" class="hidden bg-red-50 border border-red-200 rounded-xl px-4 py-3 text-xs text-red-700 space-y-1 max-h-40 overflow-y-auto"></div>
            </div>
        </div>
    </div>
</div>

<!-- 
     GUEST DETAILS MODAL
 -->
<div id="guest-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-start justify-center min-h-screen pt-10 pb-10 px-4">
        <div class="fixed inset-0 bg-black/40" id="guest-modal-bg"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl z-10">
            <div class="flex items-center justify-between px-6 py-4 border-b border-mist-100">
                <h3 class="text-lg font-heading font-semibold text-royal-900">Guest Details</h3>
                <button id="btn-close-guest-modal" class="p-1.5 rounded-lg hover:bg-mist-100 text-mist-500">&#10005;</button>
            </div>
            <div id="guest-details-content" class="px-6 py-5 max-h-96 overflow-y-auto">
                <!-- Content loaded dynamically -->
            </div>
        </div>
    </div>
</div>

<script>
const MB = '<?= $B ?>';
let allMembers = [];
let allGuests = [];

/* ━━━━━━━━━━━━━━ TAB SWITCHING ━━━━━━━━━━━━━━ */
function switchTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    const btns = document.querySelectorAll('.tab-btn');
    
    tabs.forEach(t => t.classList.add('hidden'));
    btns.forEach(b => b.classList.add('bg-mist-100', 'text-mist-700', 'hover:bg-mist-200'));
    btns.forEach(b => b.classList.remove('bg-royal-600', 'text-white', 'hover:bg-royal-700'));
    
    const activeTab = document.getElementById('tab-content-' + tabName);
    const activeBtn = document.getElementById('btn-tab-' + tabName);
    
    if (activeTab) activeTab.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.remove('bg-mist-100', 'text-mist-700', 'hover:bg-mist-200');
        activeBtn.classList.add('bg-royal-600', 'text-white', 'hover:bg-royal-700');
    }
    
    if (tabName === 'guests') {
        loadGuestsData();
    }
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        switchTab(btn.dataset.tab);
    });
});

/* ━━━━━━━━━━━━━━ MEMBERS SECTION ━━━━━━━━━━━━━━ */
async function loadStats() {
    try {
        const res  = await fetch(MB + '/api/v1/members/stats');
        const data = await res.json();
        const s = data.data || {};
        document.getElementById('stat-total').textContent       = s.total       ?? '0';
        document.getElementById('stat-active').textContent      = s.active      ?? '0';
        document.getElementById('stat-inactive').textContent    = s.inactive    ?? '0';
        document.getElementById('stat-transferred').textContent = s.transferred ?? '0';
    } catch(_) {}
}

function calculateMembersDemographics() {
    if (!allMembers.length) {
        document.getElementById('stat-men').textContent = '0';
        document.getElementById('stat-women').textContent = '0';
        document.getElementById('stat-children').textContent = '0';
        document.getElementById('stat-elders').textContent = '0';
        return;
    }

    let men = 0, women = 0, children = 0, elders = 0;
    
    allMembers.forEach(m => {
        // Count by gender
        if (m.gender === 'male') men++;
        else if (m.gender === 'female') women++;
        
        // Count by age (requires date_of_birth)
        if (m.date_of_birth) {
            const birthDate = new Date(m.date_of_birth);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 18) children++;
            else if (age >= 60) elders++;
        }
    });
    
    document.getElementById('stat-men').textContent = men;
    document.getElementById('stat-women').textContent = women;
    document.getElementById('stat-children').textContent = children;
    document.getElementById('stat-elders').textContent = elders;
}

/*  Load Members  */
async function loadMembers() {
    const search = document.getElementById('filter-search').value.trim();
    const status = document.getElementById('filter-status').value;
    const gender = document.getElementById('filter-gender').value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (status) params.set('status', status);
    if (gender) params.set('gender', gender);

    try {
        const res  = await fetch(MB + '/api/v1/members?' + params.toString());
        const data = await res.json();
        allMembers = data.data || [];
        renderMembers(allMembers);
        rebuildRegionFilter();
        calculateMembersDemographics();
    } catch(e) {
        console.error('Failed to load members', e);
    }
}

function rebuildRegionFilter() {
    const sel = document.getElementById('filter-region');
    const current = sel.value;
    const regions = [...new Set(allMembers.map(m => m.region).filter(Boolean))].sort();
    sel.innerHTML = '<option value="">All Regions</option>' + regions.map(r => `<option value="${r}"${r===current?' selected':''}>${r}</option>`).join('');
}

function applyClientFilter() {
    const region = document.getElementById('filter-region').value;
    if (!region) { renderMembers(allMembers); return; }
    renderMembers(allMembers.filter(m => m.region === region));
}

/*  Render Table  */
function statusCls(s) {
    const m = { active:'bg-emerald-100 text-emerald-700', inactive:'bg-mist-100 text-mist-600', transferred:'bg-dawn-100 text-dawn-700', deceased:'bg-red-100 text-red-700' };
    return m[s] || 'bg-mist-100 text-mist-600';
}

function renderMembers(list) {
    const tbody = document.getElementById('members-tbody');
    const empty = document.getElementById('members-empty');
    document.getElementById('member-count').textContent = list.length + ' record' + (list.length !== 1 ? 's' : '');
    if (!list.length) { tbody.innerHTML = ''; empty.classList.remove('hidden'); return; }
    empty.classList.add('hidden');
    tbody.innerHTML = list.map(r => `
        <tr class="hover:bg-mist-50/60 cursor-pointer" data-id="${r.id}">
            <td class="px-4 py-3 text-xs font-mono text-mist-600">${r.member_code || '-'}</td>
            <td class="px-4 py-3 font-semibold text-royal-800">${r.first_name} ${r.last_name}</td>
            <td class="px-4 py-3 text-mist-700">${r.phone || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.email || '-'}</td>
            <td class="px-4 py-3 text-mist-600 capitalize text-xs">${r.gender || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.region || '-'}</td>
            <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${statusCls(r.member_status)}">${r.member_status}</span></td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.join_date ? r.join_date.substring(0,10) : '-'}</td>
            <td class="px-4 py-3 text-right">
                <button class="text-xs text-royal-600 hover:text-royal-800 font-semibold edit-btn" data-id="${r.id}">Edit</button>
            </td>
        </tr>
    `).join('');

    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', e => { e.stopPropagation(); openEditModal(Number(btn.dataset.id)); });
    });
}

/*  Filters  */
document.getElementById('filter-search').addEventListener('input',  loadMembers);
document.getElementById('filter-status').addEventListener('change', loadMembers);
document.getElementById('filter-gender').addEventListener('change', loadMembers);
document.getElementById('filter-region').addEventListener('change', applyClientFilter);
document.getElementById('btn-clear-filters').addEventListener('click', () => {
    ['filter-search','filter-status','filter-gender','filter-region'].forEach(id => {
        const el = document.getElementById(id);
        el.value = '';
    });
    loadMembers();
});

/*  Export CSV  */
document.getElementById('btn-export-csv').addEventListener('click', () => {
    if (!allMembers.length) { alert('No members loaded to export.'); return; }
    const cols = ['member_code','first_name','last_name','gender','phone','email','member_status','join_date','ward','district','region','date_of_birth'];
    const header = cols.join(',');
    const rows = allMembers.map(r => cols.map(c => JSON.stringify(r[c] ?? '')).join(','));
    const blob = new Blob([header + '\n' + rows.join('\n')], { type: 'text/csv' });
    const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(blob), download: 'members_export.csv' });
    a.click();
});

/*  ADD MEMBER MODAL  */
function openAddModal() {
    document.getElementById('edit-member-id').value = '';
    document.getElementById('modal-title').textContent = 'Add New Member';
    document.getElementById('member-form').reset();
    hideFeedback();
    document.getElementById('member-modal').classList.remove('hidden');
}

function openEditModal(id) {
    const m = allMembers.find(x => x.id === id);
    if (!m) return;
    document.getElementById('edit-member-id').value = id;
    document.getElementById('modal-title').textContent = 'Edit Member  ' + m.first_name + ' ' + m.last_name;
    const f = document.getElementById('member-form');
    const fields = ['first_name','last_name','gender','phone','email','member_code','date_of_birth','join_date','marital_status','baptism_date','ward','district','region','member_status','notes'];
    fields.forEach(name => {
        const el = f.querySelector('[name="' + name + '"]');
        if (el) el.value = m[name] || '';
    });
    hideFeedback();
    document.getElementById('member-modal').classList.remove('hidden');
}

function closeMemberModal() { document.getElementById('member-modal').classList.add('hidden'); }

document.getElementById('btn-open-add').addEventListener('click', openAddModal);
document.getElementById('btn-close-member-modal').addEventListener('click', closeMemberModal);
document.getElementById('btn-cancel-member').addEventListener('click', closeMemberModal);
document.getElementById('member-modal-bg').addEventListener('click', closeMemberModal);

function showFeedback(msg, isError) {
    const el = document.getElementById('member-form-feedback');
    el.textContent = msg;
    el.className = 'mx-6 mb-4 rounded-xl px-3 py-2 text-sm ' + (isError ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200');
    el.classList.remove('hidden');
}
function hideFeedback() { document.getElementById('member-form-feedback').classList.add('hidden'); }

document.getElementById('member-form').addEventListener('submit', async e => {
    e.preventDefault();
    const fd      = new FormData(e.target);
    const payload = Object.fromEntries(fd.entries());
    const editId  = document.getElementById('edit-member-id').value;
    const isEdit  = editId !== '';

    const url    = isEdit ? MB + '/api/v1/members/' + editId : MB + '/api/v1/members';
    const method = isEdit ? 'PUT' : 'POST';

    try {
        const res  = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
        const data = await res.json();
        if (!res.ok || !data.success) { showFeedback(data.message || 'Failed to save member.', true); return; }
        showFeedback(isEdit ? 'Member updated successfully.' : ('Member created. Code: ' + (data.data?.member_code || '')), false);
        e.target.reset();
        document.getElementById('edit-member-id').value = '';
        await Promise.all([loadStats(), loadMembers()]);
        setTimeout(closeMemberModal, 1200);
    } catch(err) {
        showFeedback('Network error. Please try again.', true);
    }
});

/*  IMPORT MODAL  */
function openImportModal()  { document.getElementById('import-modal').classList.remove('hidden'); resetImportModal(); }
function closeImportModal() { document.getElementById('import-modal').classList.add('hidden'); }

document.getElementById('btn-open-import').addEventListener('click', openImportModal);
document.getElementById('btn-close-import').addEventListener('click', closeImportModal);
document.getElementById('btn-cancel-import').addEventListener('click', closeImportModal);
document.getElementById('import-modal-bg').addEventListener('click', closeImportModal);

function resetImportModal() {
    document.getElementById('import-file-input').value = '';
    document.getElementById('selected-filename').classList.add('hidden');
    document.getElementById('selected-filename').textContent = '';
    document.getElementById('btn-do-import').disabled = true;
    document.getElementById('import-result').classList.add('hidden');
    document.getElementById('import-errors').classList.add('hidden');
}

/* Template download */
document.getElementById('btn-download-template').addEventListener('click', () => {
    const header = 'member_code,first_name,last_name,gender,phone,email,date_of_birth,join_date,member_status,physical_address,ward,district,region,marital_status,baptism_date,notes';
    const sample = ',John,Doe,male,0712345678,john@example.com,1990-01-15,2024-01-01,active,123 Msasani St,Msasani,Kinondoni,Dar es Salaam,married,,';
    const blob = new Blob([header + '\n' + sample], { type: 'text/csv' });
    const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(blob), download: 'members_import_template.csv' });
    a.click();
});

/* File selection */
document.getElementById('import-file-input').addEventListener('change', e => {
    const f = e.target.files[0];
    if (!f) return;
    const fn = document.getElementById('selected-filename');
    fn.textContent = '&#128196; ' + f.name + ' (' + (f.size / 1024).toFixed(1) + ' KB)';
    fn.classList.remove('hidden');
    document.getElementById('btn-do-import').disabled = false;
    document.getElementById('import-result').classList.add('hidden');
    document.getElementById('import-errors').classList.add('hidden');
});

/* Drag and drop */
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-royal-400', 'bg-royal-50/50'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-royal-400', 'bg-royal-50/50'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('border-royal-400', 'bg-royal-50/50');
    const f = e.dataTransfer.files[0];
    if (!f) return;
    const ext = f.name.split('.').pop().toLowerCase();
    if (!['csv','xlsx'].includes(ext)) { alert('Only .csv and .xlsx files are supported.'); return; }
    const dt = new DataTransfer();
    dt.items.add(f);
    document.getElementById('import-file-input').files = dt.files;
    document.getElementById('import-file-input').dispatchEvent(new Event('change'));
});

/* Do import */
document.getElementById('btn-do-import').addEventListener('click', async () => {
    const fileInput = document.getElementById('import-file-input');
    if (!fileInput.files.length) return;

    const btn = document.getElementById('btn-do-import');
    btn.disabled = true;
    btn.textContent = 'Importing';

    const fd = new FormData();
    fd.append('file', fileInput.files[0]);

    try {
        const res  = await fetch(MB + '/api/v1/members/import', { method: 'POST', body: fd });
        const data = await res.json();

        const resultEl = document.getElementById('import-result');
        const errorsEl = document.getElementById('import-errors');

        if (data.success) {
            resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-emerald-50 border border-emerald-200 text-emerald-800';
            resultEl.innerHTML = '<strong>' + (data.message || 'Import complete.') + '</strong>';
            resultEl.classList.remove('hidden');
            await Promise.all([loadStats(), loadMembers()]);
        } else {
            resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800';
            resultEl.innerHTML = '<strong>Import failed:</strong> ' + (data.message || 'Unknown error');
            resultEl.classList.remove('hidden');
        }

        const errs = data.data?.errors || [];
        if (errs.length) {
            errorsEl.innerHTML = '<strong class="block mb-1">Row Warnings:</strong>' + errs.map(e => '<div>' + e + '</div>').join('');
            errorsEl.classList.remove('hidden');
        }
    } catch(err) {
        const resultEl = document.getElementById('import-result');
        resultEl.className = 'rounded-xl px-4 py-3 text-sm bg-red-50 border border-red-200 text-red-800';
        resultEl.textContent = 'Network error. Please try again.';
        resultEl.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Import Members';
    }
});

/*  Bootstrap  */
Promise.all([loadStats(), loadMembers()]);

/* ━━━━━━━━━━━━━━ GUESTS SECTION ━━━━━━━━━━━━━━ */

async function loadGuestsData() {
    const search = document.getElementById('filter-guests-search').value.trim();
    const status = document.getElementById('filter-guests-status').value;
    const params = new URLSearchParams();
    if (search) params.set('search', search);
    if (status) params.set('status', status);
    
    try {
        const res = await fetch(MB + '/api/v1/attendance/guests?' + params.toString());
        const data = await res.json();
        allGuests = data.data || [];
        renderGuests(allGuests);
        updateGuestsStats();
    } catch(e) {
        console.error('Failed to load guests', e);
    }
}

function updateGuestsStats() {
    const stats = {
        total: allGuests.length,
        registered: allGuests.filter(g => g.status === 'registered').length,
        visited: allGuests.filter(g => g.status === 'visited').length,
        converted: allGuests.filter(g => g.status === 'converted').length,
        inactive: allGuests.filter(g => g.status === 'inactive').length,
        children: allGuests.filter(g => g.age_group === 'child').length,
        youth: allGuests.filter(g => g.age_group === 'teen' || g.age_group === 'youth').length,
        adults: allGuests.filter(g => g.age_group === 'adult').length,
        elders: allGuests.filter(g => g.age_group === 'senior').length,
    };
    
    document.getElementById('stat-guests-total').textContent = stats.total;
    document.getElementById('stat-guests-registered').textContent = stats.registered;
    document.getElementById('stat-guests-visited').textContent = stats.visited;
    document.getElementById('stat-guests-converted').textContent = stats.converted;
    document.getElementById('stat-guests-inactive').textContent = stats.inactive;
    document.getElementById('stat-guests-children').textContent = stats.children;
    document.getElementById('stat-guests-youth').textContent = stats.youth;
    document.getElementById('stat-guests-adults').textContent = stats.adults;
    document.getElementById('stat-guests-elders').textContent = stats.elders;
}

function getGuestStatusClass(status) {
    const classes = {
        'registered': 'bg-emerald-100 text-emerald-700',
        'visited': 'bg-blue-100 text-blue-700',
        'converted': 'bg-purple-100 text-purple-700',
        'inactive': 'bg-dawn-100 text-dawn-700'
    };
    return classes[status] || 'bg-mist-100 text-mist-600';
}

function getVisitTypeLabel(visitType) {
    const labels = {
        'first_time': '1st Time',
        'returning': 'Returning',
        'referred': 'Referred'
    };
    return labels[visitType] || visitType;
}

function renderGuests(list) {
    const tbody = document.getElementById('guests-tbody');
    const empty = document.getElementById('guests-empty');
    document.getElementById('guests-count').textContent = list.length + ' record' + (list.length !== 1 ? 's' : '');
    
    if (!list.length) {
        tbody.innerHTML = '';
        empty.classList.remove('hidden');
        return;
    }
    
    empty.classList.add('hidden');
    tbody.innerHTML = list.map(r => `
        <tr class="hover:bg-mist-50/60">
            <td class="px-4 py-3 text-xs font-mono text-mist-600 font-semibold">${r.guest_code || '-'}</td>
            <td class="px-4 py-3 font-semibold text-royal-800">${r.first_name} ${r.last_name}</td>
            <td class="px-4 py-3 text-mist-700 text-sm">${r.phone || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.email || '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.service_date ? r.service_date.substring(0,10) : '-'}</td>
            <td class="px-4 py-3 text-mist-600 text-xs"><span class="inline-flex px-2 py-0.5 rounded text-xs font-medium bg-mist-100">${getVisitTypeLabel(r.visit_type)}</span></td>
            <td class="px-4 py-3 text-mist-600 text-xs">${r.location || '-'}</td>
            <td class="px-4 py-3"><span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${getGuestStatusClass(r.status)}">${r.status}</span></td>
            <td class="px-4 py-3 text-right">
                <button class="text-xs text-mist-500 hover:text-royal-700 font-semibold view-guest-btn" data-id="${r.id}" title="View details">👁</button>
            </td>
        </tr>
    `).join('');
    
    document.querySelectorAll('.view-guest-btn').forEach(btn => {
        btn.addEventListener('click', e => {
            e.stopPropagation();
            showGuestDetails(Number(btn.dataset.id));
        });
    });
}

function showGuestDetails(guestId) {
    const guest = allGuests.find(g => g.id === guestId);
    if (!guest) return;
    
    const detailsHTML = `
        <div class="space-y-3 text-sm">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Guest Code</p>
                    <p class="font-mono text-royal-700 font-semibold">${guest.guest_code}</p>
                </div>
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Status</p>
                    <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-semibold ${getGuestStatusClass(guest.status)}">${guest.status}</span>
                </div>
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Service Date</p>
                    <p>${guest.service_date ? guest.service_date.substring(0,10) : '-'}</p>
                </div>
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Visit Type</p>
                    <p>${getVisitTypeLabel(guest.visit_type)}</p>
                </div>
            </div>
            <div class="pt-2 border-t border-mist-200 space-y-2">
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Full Name</p>
                    <p>${guest.first_name} ${guest.last_name}</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-mist-500 font-semibold">Phone</p>
                        <p class="font-mono">${guest.phone || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-mist-500 font-semibold">Email</p>
                        <p class="text-xs">${guest.email || '-'}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-xs text-mist-500 font-semibold">Location</p>
                        <p>${guest.location || '-'}</p>
                    </div>
                    <div>
                        <p class="text-xs text-mist-500 font-semibold">Age Group</p>
                        <p>${guest.age_group ? guest.age_group.charAt(0).toUpperCase() + guest.age_group.slice(1) : '-'}</p>
                    </div>
                </div>
                ${guest.invited_by_name ? `
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Invited By</p>
                    <p>${guest.invited_by_name}</p>
                </div>
                ` : ''}
                ${guest.follow_up_date ? `
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Follow-up Date</p>
                    <p>${guest.follow_up_date.substring(0,10)}</p>
                </div>
                ` : ''}
                ${guest.notes ? `
                <div>
                    <p class="text-xs text-mist-500 font-semibold">Notes</p>
                    <p class="text-xs bg-mist-50 p-2 rounded border border-mist-200">${guest.notes}</p>
                </div>
                ` : ''}
                <div class="pt-2 text-xs text-mist-400">
                    Registered: ${new Date(guest.created_at).toLocaleDateString()}
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('guest-details-content').innerHTML = detailsHTML;
    document.getElementById('guest-modal').classList.remove('hidden');
}

function closeGuestModal() {
    document.getElementById('guest-modal').classList.add('hidden');
}

document.getElementById('btn-close-guest-modal').addEventListener('click', closeGuestModal);
document.getElementById('guest-modal-bg').addEventListener('click', closeGuestModal);

/* Guest Filters */
document.getElementById('filter-guests-search').addEventListener('input', () => {
    if (allGuests.length > 0) loadGuestsData();
});

document.getElementById('filter-guests-status').addEventListener('change', () => {
    if (allGuests.length > 0) loadGuestsData();
});

document.getElementById('btn-clear-guest-filters').addEventListener('click', () => {
    document.getElementById('filter-guests-search').value = '';
    document.getElementById('filter-guests-status').value = '';
    loadGuestsData();
});

/* Export Guests CSV */
document.getElementById('btn-export-guests-csv').addEventListener('click', () => {
    if (!allGuests.length) { alert('No guests to export.'); return; }
    const cols = ['guest_code','first_name','last_name','phone','email','location','service_date','visit_type','status','age_group','notes'];
    const header = cols.join(',');
    const rows = allGuests.map(r => cols.map(c => JSON.stringify(r[c] ?? '')).join(','));
    const blob = new Blob([header + '\n' + rows.join('\n')], { type: 'text/csv' });
    const a = Object.assign(document.createElement('a'), { href: URL.createObjectURL(blob), download: 'guests_export_' + new Date().toISOString().split('T')[0] + '.csv' });
    a.click();
});
</script>
