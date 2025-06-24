<x-app-layout>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="edit-profile-container">
        <h2 class="profile-edit-title">
            <i class="fas fa-user-edit"></i> Editar Perfil
        </h2>

        <div class="profile-section">
            <div class="profile-section-content">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="profile-section">
            <div class="profile-section-content">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="profile-section danger-section">
            <div class="profile-section-content">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>