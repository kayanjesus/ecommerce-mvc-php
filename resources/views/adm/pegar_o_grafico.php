<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>   
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Custom CSS-->
    <link rel="stylesheet" href="{{ asset('css/css-dashboard/style.css') }}">

 
    
</head>
<body>
     
    <!-- Dropdown Structure -->
    <ul id='dropdown2' class='dropdown-content'>     
      <li><a href="{{ url('adm.sistema')}}">voltar</a></li>
      <li><a href="#">Sair</a></li> 
    </ul>

   
    <nav class="red">
        <div class="nav-wrapper container ">
            <a href="{{ route('dashboard') }}" class="center brand-logo "><img src="{{ asset('img/img-dashboard/logo.png') }}"></a>          
          <ul class="right ">                                 
              <li class="hide-on-med-and-down"><a href="#" onclick="fullScreen()"><i class="material-icons">settings_overscan</i> </a> </li>
              <li><a href="#" class="dropdown-trigger" data-target='dropdown2'>Olá {{ auth()->user()->name }}<i class="material-icons right">expand_more</i> </a></li>     
          </ul>
          <a href="#" data-target="slide-out" class="sidenav-trigger left  show-on-large"><i class="material-icons">menu</i></a>
        </div>
      </nav>
    

    <ul id="slide-out" class="sidenav " >
      <li><div class="user-view">
        <div class="background red ">
         <img src="img/office.jpg" style="opacity: 0.5"> 
        </div>
          <a href="#user"><img class="circle" src="{{ asset('img/img-dashboard/user.jpg') }}"></a>
          <a href="#name"><span class="white-text name">{{ auth()->user()->name }}</span></a>
          <a href="#email"><span class="white-text email">{{ auth()->user()->email }}</span></a>
       </div></li> 

        <li><a href="#!"><i class="material-icons">dashboard</i>Dashboard</a></li>
        <li><a href="#"><i class="material-icons">playlist_add_circle</i>Produtos</a></li>
        <li><a href="#!"><i class="material-icons">shopping_cart</i>Pedidos</a></li>
        <li><a href="#!"><i class="material-icons">bookmarks</i>Categorias</a></li>
        <li><a href="#!"><i class="material-icons">peoples</i>Usuários</a></li>
    </ul>

    <div class="row container">
      <section class="info">

        <div class="col s12 m4">
        <article class="bg-gradient-green card z-depth-4 ">
          <i class="material-icons">paid</i>
          <p>Faturamento</p>
          <h3>R$ 123,00</h3>       
        </article>
        </div>

        <div class="col s12 m4">
          <article class="bg-gradient-blue card z-depth-4 ">
            <i class="material-icons">face</i>
            <p>Usuários</p>
            <h3>{{ $usuarios }}</h3>           
          </article>
          </div>

          <div class="col s12 m4">
            <article class="bg-gradient-orange card z-depth-4 ">
              <i class="material-icons">shopping_cart</i>
              <p>Pedidos este mês</p>
              <h3>0</h3>            
            </article>
            </div>

      </section>        
    </div>


        <div class="row container ">
            <section class="graficos col s12 m6" >            
              <div class="grafico card z-depth-4">
                  <h5 class="center"> Aquisição de usuários</h5>
                  <canvas id="myChart" width="400" height="200"></canvas>
              </div>           
            </section> 
            
            <section class="graficos col s12 m6">            
                <div class="grafico card z-depth-4">
                    <h5 class="center"> Produtos </h5>
                <canvas id="myChart2" width="400" height="200"></canvas> 
            </div>            
           </section>             
        </div>

     


        </div>

      


    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="{{ asset('js/js-dashboard/chart.js') }}" ></script>
    <script src="{{ asset('js/js-dashboard/main.js') }}"></script>

  


</body>
</html>


<script>
/* Gráfico 01 */
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [{{ $userAno }}],
        datasets: [{
            label: [{!! $userLabel !!}],
            data: [{{ $userTotal }}],
            backgroundColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',                         
                'rgba(255, 159, 64, 1)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',                     
                'rgba(255, 159, 64, 1)'
            ],
           borderWidth: 1, 
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});

/* Gráfico 02 */
var ctx = document.getElementById('myChart2');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Facebook', 'Google', 'Instagram'],
        datasets: [{
            label: 'Visitas',
            data: [12, 19, 3],
            backgroundColor: [
                'rgba(255, 99, 132)',
                'rgba(54, 162, 235)',                         
                'rgba(255, 159, 64)'
            ]
        }]
    }
});
</script>
