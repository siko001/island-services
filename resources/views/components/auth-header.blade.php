@if(Auth::user())
    
    <div class="absolute top-0 right-0 border">
        <div class="relative">
            
            <div id="burger-menu" class="absolute top-8 right-8 cursor-pointer transition-colors duration-200 hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </div>
            
            <div id="main-menu" class="hidden h-0 border border-gray-200 rounded-md absolute top-16  right-8  flex-col">
                <a href="{{ route('central.account-settings', ['id' => Auth::id()]) }}" class="whitespace-nowrap text-white px-4 py-2 hover:bg-gray-700 duration-200
                transition-colors rounded-t-md
                cursor-pointer flex
                gap-1
                items-center">
                    
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>
                    
                    Account Settings </a>
                
                <a href="{{route('central.login-trail')}}" class="whitespace-nowrap text-white px-4 py-2 hover:bg-gray-700 duration-200
                transition-colors rounded-t-md cursor-pointer flex gap-1 items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z"/>
                    </svg>
                    
                    Login Trail </a>
                
                <a href="{{ route('user.logout') }}" onclick="e.preventDefault(); alert('logout'); document.getElementById('logout-form').submit();" class="px-4 text-white py-2 hover:bg-gray-700 duration-200 transition-colors rounded-b-md cursor-pointer flex gap-1 items-center">
                    
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 ">
                        <path d="M17 16L21 12M21 12L17 8M21 12L7 12M13 16V17C13 18.6569 11.6569 20 10 20H6C4.34315 20 3 18.6569 3 17V7C3 5.34315 4.34315 4 6 4H10C11.6569 4 13 5.34315 13 7V8" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                    </svg>
                    <div> Logout</div>
                </a>
            
            </div>
        </div>
    </div>
@else
    <a href="{{ route('login') }}" class="text-white cursor-pointer hover:border-white border hover:text-blue-600 transition-all duration-300 border-blue-600 px-3 py-1.5 rounded-sm absolute top-8
        right-8 "> Login </a>
@endif

<script>
	const burgerMenu = document.getElementById('burger-menu');
	const mainMenu = document.getElementById('main-menu');

	const openMenu = () => {
		mainMenu.classList.remove('hidden', 'h-0');
		mainMenu.classList.add('flex', 'h-auto');
	};

	const closeMenu = () => {
		mainMenu.classList.add('hidden', 'h-0');
		mainMenu.classList.remove('flex', 'h-auto');
	};

	const mainMenuClick = (e) => {
		if(burgerMenu.contains(e.target)) {
			// Toggle menu on burger button click
			if(mainMenu.classList.contains('hidden')) {
				openMenu();
			} else {
				closeMenu();
			}
		}
	};

	const handleOutsideClick = (event) => {
		const isClickInside = burgerMenu.contains(event.target) || mainMenu.contains(event.target);
		if(!isClickInside && !mainMenu.classList.contains('hidden')) {
			closeMenu();
		}
	};

	// listeners
	burgerMenu.addEventListener('click', mainMenuClick);
	window.addEventListener('click', handleOutsideClick);

</script>
