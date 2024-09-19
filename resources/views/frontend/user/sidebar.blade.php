<a wire:navigate href="{{route('user.profile')}}" id="profile-info-link" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        Personal Information
    </div>
</a>
<div id="address-info-link" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        My Address Book
    </div>
</div>
<a wire:navigate href="{{route('user.favourite')}}" id="favourite-info-link" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        My Favourites
    </div>
</a>
<div id="order-info-link" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        My Orders
    </div>
</div>
<a wire:navigate id="profile-password-link" href="{{route('user.password.change')}}" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        Change Password
    </div>
</a>
<a href="{{route('logout')}}" class="w-full hover:no-underline h-12 px-4 py-3 bg-user-nav-pink border !border-user-nav-pink-active inline-flex items-center gap-1.5">
    <div class="text-center text-black-5 text-base font-medium leading-4 font-roboto break-words">
        Logout
    </div>
</a>