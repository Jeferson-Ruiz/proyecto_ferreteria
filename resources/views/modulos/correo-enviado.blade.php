@extends('layouts.plantilla-contrasena')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fas fa-envelope"></i> Correo Enviado</h4>
                </div>
                
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                    
                    <h5>✅ ¡Correo enviado exitosamente!</h5>
                    
                    <p class="mt-3">
                        Hemos enviado un enlace de recuperación a:<br>
                        <strong>{{ session('email') ?? 'tu correo electrónico' }}</strong>
                    </p>
                    
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle"></i>
                        <strong>Instrucciones:</strong>
                        <ul class="text-left mt-2">
                            <li>Revisa tu bandeja de entrada (y spam)</li>
                            <li>Busca el correo de "Ferretería San Miguel"</li>
                            <li>Haz clic en el enlace para restablecer tu contraseña</li>
                            <li>El enlace expirará en 24 horas</li>
                        </ul>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Volver al Inicio de Sesión
                        </a>
                        
                        <a href="{{ route('password.request') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-redo"></i> Reenviar Enlace
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection