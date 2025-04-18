<!DOCTYPE html>
<html lang="en">

<head>
    <title>Loja virtual - @yield('pagina_titulo')</title>

    <!-- Import Google Icon Font -->
    <link href="//fonts.google.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Import materialize.css-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/
    materialize.min.css" media="screen,projection">
    <!-- Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="/css/styles.css" rel="stylesheet">

</head>

<body>
    <header>
        <nav>
            <div class="nav-wrapper light-blue row">
                <a href="{{ route('index') }}" class="brand-logo col offset-l1">Loja virtual
                </a>
                <a href="#" data-activates="mobile-menu" class="button-collapse"><i class="
                material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Entrar</a></li>
                        <li><a href="{{ url('/register') }}">Cadastre-se</a></li>
                    @else
                        <li>
                            <a class="dropdown-button" href="#!" data-activates="
                                dropdown-user">
                                Olá {{ Auth::user()->name }}!<i class="material-icons right">arrow_drop_down</i>
                            </a>
                            <ul id="dropdown-user" class="dropdown-content">
                                <li class="divider"></li>
                                <li>
                                    <a href="{{ url('/loogut') }}" onclick="event.preventDefault();
                                                document.getElementById('logout-form').sub
                                                    mit();">
                                        Sair
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <main>
        @yield('pagina_conteudo')

        @if(!Auth::guest())
            <form id="logout-form" action="{{ url('/logout') }}"
        
        @endif
    </main>

</body>

</html>