

<header id="myheaderNav" class="border-b border-cerulean-200 bg-cerulean-50/95 backdrop-blur sticky top-0 ">
    <nav class="mx-auto w-full max-w-8xl px-4 py-3 md:px-6 " >
        <div class="flex items-center justify-between">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2">
                <img alt="logo" src="{{asset('storage/images/nass_split_logo.png')}}" height="50" width="50" />
                <span class="font-cash2 text-2xl text-cerulean-800 hidden md:block ">Nass Split</span>
            </a>
            @auth
                <div class="flex w-full justify-evenly md:px-[10%] ">

                    <svg class="hover:bg-cerulean-100 border-2 border-cerulean-500 p-1  rounded-md md:rounded-2xl     h-7 cursor-pointer md:w-12 w-7 md:h-12 text-cerulean-500" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="m498.195312 222.695312c-.011718-.011718-.023437-.023437-.035156-.035156l-208.855468-208.847656c-8.902344-8.90625-20.738282-13.8125-33.328126-13.8125-12.589843 0-24.425781 4.902344-33.332031 13.808594l-208.746093 208.742187c-.070313.070313-.140626.144531-.210938.214844-18.28125 18.386719-18.25 48.21875.089844 66.558594 8.378906 8.382812 19.445312 13.238281 31.277344 13.746093.480468.046876.964843.070313 1.453124.070313h8.324219v153.699219c0 30.414062 24.746094 55.160156 55.167969 55.160156h81.710938c8.28125 0 15-6.714844 15-15v-120.5c0-13.878906 11.289062-25.167969 25.167968-25.167969h48.195313c13.878906 0 25.167969 11.289063 25.167969 25.167969v120.5c0 8.285156 6.714843 15 15 15h81.710937c30.421875 0 55.167969-24.746094 55.167969-55.160156v-153.699219h7.71875c12.585937 0 24.421875-4.902344 33.332031-13.808594 18.359375-18.371093 18.367187-48.253906.023437-66.636719zm0 0"/></svg>
                    <svg id="Layer_1" class="hover:bg-cerulean-100 border-2 border-cerulean-500 p-1  rounded-md md:rounded-2xl     h-7 cursor-pointer md:w-12 w-7 md:h-12 text-cerulean-500" enable-background="new 0 0 512 512" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><g><path d="m301.294 56.162c1.343.446 2.72-.578 2.656-1.992-1.48-32.471-27.47-54.17-55.934-54.17-28.637 0-54.452 21.978-55.933 54.19-.065 1.414 1.313 2.438 2.655 1.992 32.815-10.919 74.311-10.742 106.556-.02z"/><path d="m449.746 376.09c-26.52-22.73-41.73-55.8-41.73-90.72v-13.488c0-1.27-1.165-2.202-2.41-1.952-67.926 13.603-133.59-38.705-133.59-109.93 0-48.357 26.506-74.251 35.063-81.593 1.136-.975.841-2.816-.55-3.368-103.643-41.122-218.513 35.28-218.513 148.961v61.37c0 34.215-14.697 67.56-41.73 90.72-4.46 3.82-8.1 8.65-10.53 13.95-12.442 27.043 7.425 57.96 37.14 57.96h349.525c21.564 0 39.961-16.426 41.491-37.936.965-13.581-4.822-25.971-14.166-33.974z"/><path d="m248.016 512c29.692 0 55.241-18.063 66.233-43.782.853-1.996-.572-4.218-2.743-4.218h-126.98c-2.171 0-3.596 2.222-2.743 4.218 10.992 25.719 36.542 43.782 66.233 43.782z"/><path d="m384.016 256c53.272 0 96-43.13 96-96 0-52.93-43.07-96-96-96-53.23 0-96 43.229-96 96 0 52.93 43.07 96 96 96zm-12.42-120.84c-3.96 1.97-8.76.37-10.74-3.58-1.97-3.95-.37-8.76 3.58-10.74l16-8c5.28-2.64 11.58 1.177 11.58 7.16v71c0 .552.448 1 1 1h6.728c4.262 0 7.982 3.218 8.255 7.471.299 4.66-3.388 8.529-7.983 8.529h-31.728c-4.262 0-7.982-3.218-8.255-7.471-.299-4.66 3.388-8.529 7.983-8.529h7c.552 0 1-.448 1-1v-56.439c0-.744-.784-1.228-1.449-.894z"/></g></svg>
                    <svg id="Layer_3" class="hover:bg-cerulean-100 border-2 border-cerulean-500 p-1  rounded-md md:rounded-2xl     h-7 cursor-pointer md:w-12 w-7 md:h-12 text-cerulean-500" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 3" fill="currentColor"><circle cx="4" cy="6" r="3"/><path d="m7.29 11.07a6.991 6.991 0 0 0 -3.29 5.93h-2a2.006 2.006 0 0 1 -2-2v-2a3.009 3.009 0 0 1 3-3h2a3 3 0 0 1 2.29 1.07z"/><circle cx="20" cy="6" r="3"/><path d="m24 13v2a2.006 2.006 0 0 1 -2 2h-2a6.991 6.991 0 0 0 -3.29-5.93 3 3 0 0 1 2.29-1.07h2a3.009 3.009 0 0 1 3 3z"/><circle cx="12" cy="7" r="4"/><path d="m18 17v1a3.009 3.009 0 0 1 -3 3h-6a3.009 3.009 0 0 1 -3-3v-1a5 5 0 0 1 5-5h2a5 5 0 0 1 5 5z"/></svg>
                    <svg class="hover:bg-cerulean-100 border-2 border-cerulean-500 p-1  rounded-md md:rounded-2xl     h-7 cursor-pointer md:w-12 w-7 md:h-12 text-cerulean-500" viewBox="-42 0 512 512.002" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><path d="m210.351562 246.632812c33.882813 0 63.222657-12.152343 87.195313-36.128906 23.972656-23.972656 36.125-53.304687 36.125-87.191406 0-33.875-12.152344-63.210938-36.128906-87.191406-23.976563-23.96875-53.3125-36.121094-87.191407-36.121094-33.886718 0-63.21875 12.152344-87.191406 36.125s-36.128906 53.308594-36.128906 87.1875c0 33.886719 12.15625 63.222656 36.132812 87.195312 23.976563 23.96875 53.3125 36.125 87.1875 36.125zm0 0"/><path d="m426.128906 393.703125c-.691406-9.976563-2.089844-20.859375-4.148437-32.351563-2.078125-11.578124-4.753907-22.523437-7.957031-32.527343-3.308594-10.339844-7.808594-20.550781-13.371094-30.335938-5.773438-10.15625-12.554688-19-20.164063-26.277343-7.957031-7.613282-17.699219-13.734376-28.964843-18.199219-11.226563-4.441407-23.667969-6.691407-36.976563-6.691407-5.226563 0-10.28125 2.144532-20.042969 8.5-6.007812 3.917969-13.035156 8.449219-20.878906 13.460938-6.707031 4.273438-15.792969 8.277344-27.015625 11.902344-10.949219 3.542968-22.066406 5.339844-33.039063 5.339844-10.972656 0-22.085937-1.796876-33.046874-5.339844-11.210938-3.621094-20.296876-7.625-26.996094-11.898438-7.769532-4.964844-14.800782-9.496094-20.898438-13.46875-9.75-6.355468-14.808594-8.5-20.035156-8.5-13.3125 0-25.75 2.253906-36.972656 6.699219-11.257813 4.457031-21.003906 10.578125-28.96875 18.199219-7.605469 7.28125-14.390625 16.121094-20.15625 26.273437-5.558594 9.785157-10.058594 19.992188-13.371094 30.339844-3.199219 10.003906-5.875 20.945313-7.953125 32.523437-2.058594 11.476563-3.457031 22.363282-4.148437 32.363282-.679688 9.796875-1.023438 19.964844-1.023438 30.234375 0 26.726562 8.496094 48.363281 25.25 64.320312 16.546875 15.746094 38.441406 23.734375 65.066406 23.734375h246.53125c26.625 0 48.511719-7.984375 65.0625-23.734375 16.757813-15.945312 25.253906-37.585937 25.253906-64.324219-.003906-10.316406-.351562-20.492187-1.035156-30.242187zm0 0"/></svg>

                </div>
            @endauth


            <button
                type="button"
                class=" inline-flex h-10 w-10 items-center justify-center rounded-xl border border-cerulean-300 text-cerulean-800 md:hidden"
                id="burgerMenu"
            >
                <svg id="openBurgerMenu" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" >
                    <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm1 4a1 1 0 100 2h12a1 1 0 100-2H4z" clip-rule="evenodd" />
                </svg>
{{--                svg for when clicked--}}
                <svg id="closeBurgerMenu" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" viewBox="0 0 20 20" fill="currentColor" >
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <ul class="hidden items-center gap-2 md:flex">
                @auth

                    <li ><form method="post" action="{{ route('logout') }}" class="cursor-pointer">
                            @csrf
                            <button type="submit"  class=" rounded-xl bg-cerulean-700 px-3 py-2 text-sm font-semibold text-white hover:bg-cerulean-800">Logout</button>
                        </form>
                    </li>
                @else
                <li><a href="{{ route('login') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-cerulean-700 hover:bg-cerulean-100">Login</a></li>
                <li><a href="{{ route('register') }}" class="rounded-xl bg-cerulean-700 px-3 py-2 text-sm font-semibold text-white hover:bg-cerulean-800">Register</a></li>
                @endauth
            </ul>
        </div>

        <div id="mobile-menu" class="mt-3 hidden rounded-2xl border border-cerulean-200 bg-white p-2 md:hidden">
            <ul class="space-y-1">
                @auth

                    <li><form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"  class=" rounded-xl bg-cerulean-700 px-3 py-2 text-sm font-semibold text-white hover:bg-cerulean-800">Logout</button>
                        </form>
                    </li>

                @else
                <li><a href="{{ route('login') }}" class=" rounded-xl px-3 py-2 text-sm font-semibold text-cerulean-700 hover:bg-cerulean-100">Login</a></li>
                <li><a href="{{ route('register') }}" class=" rounded-xl bg-cerulean-700 px-3 py-2 text-sm font-semibold text-white hover:bg-cerulean-800">Register</a></li>
                @endauth
            </ul>
        </div>
    </nav>
</header>

<script>
    document.addEventListener('click', (e) => {
    const closeBurgerMenu = document.getElementById('closeBurgerMenu');
    const openBurgerMenu = document.getElementById('openBurgerMenu');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.getElementById('myheaderNav');
    [closeBurgerMenu, openBurgerMenu, mobileMenu].forEach(menu => {
        menu.classList.toggle('hidden');
    })

    });
</script>

