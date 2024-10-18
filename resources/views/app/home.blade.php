@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row justify-content-center"> <div class="col-12">
            <div class="col-12">
                <div class="card">

                    <div class="card-body">

                        <div class="d-flex justify-content-between"> <div>
                            <div>
                                <h3>Hay, <span class="text-primary">{{      
                                $auth->name }}</span></h3>

                            </div>
                        <div>
                            <a href="{{ route('logout') }}"
                            class="btn btn-danger">Logout
                        </a>
                        </div>

                    </div>

                    <hr>

                    <p>Selamat untuk kamu karena telah berhasil

                    mengimplementasikan fitur autentikasi dasar.</p>

                </div>

            </div>

        </div>

    </div>

    </div>
@endsection