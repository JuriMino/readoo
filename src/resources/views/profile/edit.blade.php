<x-app-layout>
<div class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-4xl font-bold text-gray-900">My Profile</h1>
    <div class="mt-8 space-y-8">
         @include('profile.partials.update-profile-information-form')
         @include('profile.partials.update-password-form')
         @include('profile.partials.delete-user-form')
    </div>
</div>
</x-app-layout>
