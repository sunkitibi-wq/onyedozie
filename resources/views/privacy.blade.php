@extends('layouts.frontend')

@section('content')
<!-- Hero Section -->
<header class="relative py-20 overflow-hidden bg-primary text-on-primary">
    <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
    <div class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop relative z-10 text-center space-y-4">
        <span class="inline-block px-4 py-1.5 bg-secondary-container text-on-secondary-container font-label-md rounded-full border border-secondary-container/20">Legal Information</span>
        <h1 class="text-headline-xl font-headline-xl">Privacy Policy</h1>
        <p class="text-body-lg font-body-lg max-w-2xl mx-auto opacity-95 leading-relaxed">
            How we collect, manage, and safeguard the data of volunteers, coordinators, and supporters on the Onyendozi Connect platform.
        </p>
    </div>
</header>

<!-- Content Section -->
<section class="py-20 bg-surface">
    <div class="max-w-4xl mx-auto px-margin-mobile md:px-margin-desktop">
        <div class="bg-white border border-outline-variant/30 rounded-2xl p-8 md:p-12 shadow-sm space-y-10 text-on-surface-variant leading-relaxed">
            
            <!-- Intro -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">1. Introduction</h2>
                <p class="text-body-md">
                    Onyendozi Connect ("we", "our", or "the platform") is dedicated to protecting the privacy and personal data of our users, volunteers, coordinators, and supporters. This Privacy Policy details how we collect, store, and utilize your personal information when you register through our website, mobile application, or participate in campaigns across Anambra Central Senatorial District.
                </p>
                <p class="text-body-md">
                    By registering or using the platform, you consent to the collection and use of information in accordance with this policy.
                </p>
            </div>

            <!-- Information Collection -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">2. Information We Collect</h2>
                <p class="text-body-md">
                    To coordinate campaign activities and manage regional networks effectively, we collect personal and geographic details. This includes:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-body-md">
                    <li><strong>Personal Identity Data:</strong> Full name, password hash, and contact details.</li>
                    <li><strong>Contact Identifiers:</strong> Email address (used for secure account notifications and OTP deliveries) and mobile phone number.</li>
                    <li><strong>Geographic Parameters:</strong> Your registered Local Government Area (LGA), Ward, and Polling Unit coordinates/designations.</li>
                    <li><strong>Campaign Roles:</strong> Selected network designations such as Volunteer, LGA Coordinator, Ward Coordinator, or Polling Unit Coordinator.</li>
                    <li><strong>App Logs & Interactions:</strong> Temporary activity stamps, device details, and log interactions captured for security verification.</li>
                </ul>
            </div>

            <!-- How We Use Information -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">3. How We Use Your Information</h2>
                <p class="text-body-md">
                    The gathered details are processed exclusively for administrative, mobilization, and communication purposes, including:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-body-md">
                    <li>Verifying registration accounts using One-Time Passwords (OTPs) dispatched securely via email or SMS.</li>
                    <li>Structuring volunteer operations and coordinating local campaign wings at the Polling Unit, Ward, and LGA levels.</li>
                    <li>Providing relevant updates, notices of local town halls, and mobilization guidelines.</li>
                    <li>Authenticating login access for staff, administrators, and coordinators on backend dashboards.</li>
                    <li>Maintaining comprehensive audit logs to ensure security and prevent fraudulent account creation.</li>
                </ul>
            </div>

            <!-- Data Security -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">4. Data Storage & Security</h2>
                <p class="text-body-md">
                    We employ industry-standard encryption protocols and administrative safeguards to protect your personal details:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-body-md">
                    <li>Passwords are strongly hashed using secure cryptography before being written to our database.</li>
                    <li>Communication endpoints are secured using HTTPS/SSL layers.</li>
                    <li>Database tables containing coordinator locations and tracker logs are restricted to authenticated system administrators.</li>
                </ul>
                <p class="text-body-md italic text-xs">
                    Please note that while we use commercially acceptable security measures to safeguard your data, no method of transmission over the internet is 100% secure.
                </p>
            </div>

            <!-- Sharing and Disclosures -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">5. Sharing and Disclosures</h2>
                <p class="text-body-md">
                    We hold a strict policy regarding the protection of your personal information:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-body-md">
                    <li><strong>No Commercial Sale:</strong> We do not sell, rent, or lease your personal information, contact numbers, or location parameters to third-party commercial advertisers or data brokers.</li>
                    <li><strong>Campaign Operations:</strong> Your name and registered geographic area may be visible to designated LGA, Ward, or Polling Unit coordinators to coordinate campaign wings.</li>
                    <li><strong>Legal Mandates:</strong> We may disclose information only if required by law, court order, or to protect the safety and rights of the platform and its users.</li>
                </ul>
            </div>

            <!-- Your Rights & Controls -->
            <div class="space-y-4">
                <h2 class="text-headline-lg font-headline-lg text-primary font-bold">6. Your Rights and Choices</h2>
                <p class="text-body-md">
                    You maintain complete control over your registration profile. You have the right to:
                </p>
                <ul class="list-disc pl-6 space-y-2 text-body-md">
                    <li>Access your profile through the dashboard to update your contact identifiers or geographic selections.</li>
                    <li>Opt out of campaign newsletter broadcasts by following the unsubscribe link in mail footers.</li>
                    <li>Request complete deletion of your account and related records from the database by contacting our administrator.</li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="space-y-4 pt-6 border-t border-outline-variant/30">
                <h2 class="text-headline-md font-headline-md text-primary font-bold">7. Contact Information</h2>
                <p class="text-body-md">
                    For inquiries regarding this Privacy Policy, your personal data, or to request account removal, please reach out to:
                </p>
                <p class="text-body-md font-bold text-primary">
                    Onyendozi Connect Web Administration<br>
                    Email: admin@onyendoziconnect.org<br>
                    Powered by: Henmark Info-Tech Ltd.
                </p>
            </div>

        </div>
    </div>
</section>
@endsection
