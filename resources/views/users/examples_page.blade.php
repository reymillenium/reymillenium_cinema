@extends('layouts.general_layout')

@section('page_title', 'Ejemplos de como mostrar el ' . "{$title}")
@section('content')
    
    <h1><?php echo e($title) . ' sin condicional'; ?></h1>
    
    <ul>
        <?php foreach ($usersArray as $user): ?>
        <li> {{ $user}} </li>
        <?php endforeach; ?>
    </ul>
    
    
    <h1>{{ $title }} usando if</h1>
    <hr>
    @if(!empty($usersArray))
        <ul>
            @foreach($usersArray as $user)
                <li>{{ $user }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay usuarios registrados</p>
    @endif
    
    
    <h1>{{ $title  }} usando unless</h1>
    <hr>
    @unless(empty($usersArray))
        <ul>
            @foreach($usersArray as $user)
                <li>{{ $user }}</li>
            @endforeach
        </ul>
    @else
        <p>No hay usuarios registrados</p>
    @endunless
    
    
    <h1>{{ $title  }} usando directiva empty</h1>
    <hr>
    @empty($usersArray)
        <p>No hay usuarios registrados</p>
    @else
        <ul>
            @foreach($usersArray as $user)
                <li>{{ $user }}</li>
            @endforeach
        </ul>
    @endempty
    
    
    <h1>{{ $title  }} usando directiva forelse</h1>
    <hr>
    <ul>
        @forelse($usersArray as $user)
            <li>{{ $user }}</li>
        @empty
            <p>No hay usuarios registrados</p>
        @endforelse
    </ul>
@endsection

@section('sidebar')
     {{--Permite mostrar el contenido original--}}
     @parent
    
    <h2>personalizada!</h2>
@endsection

