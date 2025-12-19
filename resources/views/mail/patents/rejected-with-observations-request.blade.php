<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Rechazo con Observaciones de Patente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        .banner {
            width: 100%;
            max-width: 600px;
            height: auto;
            display: block;
        }

        .header {
            background-color: #ffffff;
            padding: 30px 0px;
            border-bottom: 3px solid #2563eb;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-table td:first-child {
            width: auto;
            padding: 0 40px;
            text-align: center;
        }

        .header-table td:last-child {
            width: 70px;
            text-align: right;
            padding-right: 0px;
        }

        .header-text h1 {
            color: #2563eb;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            text-align: center;
        }

        .header-text p {
            color: #666666;
            font-size: 14px;
            margin: 8px 0 0 0;
            text-align: center;
        }

        .header-icon {
            background-color: #2563eb;
            width: 70px;
            height: 70px;
            display: inline-block;
            text-align: center;
            border-radius: 4px;
            vertical-align: middle;
        }

        .header-icon svg {
            width: 36px;
            height: 36px;
            fill: none;
            stroke: #ffffff;
            vertical-align: middle;
            margin-top: 17px;
        }

        .content {
            padding: 40px;
            background-color: #ffffff;
        }

        .greeting {
            font-size: 14px;
            color: #333333;
            margin-bottom: 20px;
        }

        .intro-text {
            font-size: 13px;
            color: #666666;
            margin-bottom: 30px;
            line-height: 1.8;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #2563eb;
            margin: 30px 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fafafa;
            border: 1px solid #e0e0e0;
        }

        .data-table tr {
            border-bottom: 1px solid #e0e0e0;
        }

        .data-table tr:last-child {
            border-bottom: none;
        }

        .data-table td {
            padding: 15px 20px;
            font-size: 13px;
        }

        .data-table td:first-child {
            font-weight: 600;
            color: #666666;
            width: 40%;
        }

        .data-table td:last-child {
            color: #333333;
            text-align: right;
        }

        .folio-highlight {
            background-color: #2563eb;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: 700;
            font-size: 14px;
        }

        .observations-box {
            background-color: #fff7ed;
            border: 1px solid #fed7aa;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .observations-box-title {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .observations-box-title svg {
            width: 16px;
            height: 16px;
            color: #2563eb;
        }

        .observations-content {
            font-size: 13px;
            color: #666666;
            line-height: 1.8;
            white-space: pre-wrap;
            background-color: #ffffff;
            padding: 15px;
            border-radius: 4px;
            border: 1px solid #fed7aa;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 30px 0;
        }

        .info-box-title {
            font-size: 13px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .info-box-text {
            font-size: 12px;
            color: #666666;
            line-height: 1.8;
        }

        .steps-list {
            margin: 20px 0;
            padding: 0;
            list-style: none;
        }

        .steps-list li {
            padding: 12px 0;
            font-size: 13px;
            color: #666666;
            border-bottom: 1px solid #f0f0f0;
            padding-left: 25px;
            position: relative;
        }

        .steps-list li:last-child {
            border-bottom: none;
        }

        .steps-list li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #2563eb;
            font-weight: bold;
            font-size: 18px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
        }

        .footer p {
            font-size: 11px;
            color: #999999;
            margin: 5px 0;
            line-height: 1.6;
        }

        .footer-logo {
            font-size: 12px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 10px;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        .status-rejected-obs {
            background-color: #dc2626;
            color: #ffffff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <!-- Banner Superior (opcional - se muestra solo si existe) -->
        @if (file_exists(public_path('images/email/header_mailing.png')))
            <img src="{{ asset('images/email/header_mailing.png') }}" alt="Ilustre Municipalidad de Arica" class="banner">
        @endif

        <!-- Header -->
        <div class="header">
            <table class="header-table">
                <tr>
                    <td>
                        <div class="header-text">
                            <h1>Comprobante de Rechazo con Observaciones</h1>
                            <p>Ventanilla Única</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Estimado/a <strong>{{ $patentRequest->user->name }}</strong>,</p>

            <p class="intro-text">
                Le informamos que su solicitud de patente ha sido <strong>rechazada con observaciones</strong>.
                A continuación, detallamos la información de su solicitud evaluada el día {{ $patentRequest->reviewed_at->format('d-m-Y H:i') }}.
            </p>

            <!-- Datos de la Patente -->
            <h2 class="section-title">Datos de la Solicitud</h2>
            <table class="data-table">
                <tr>
                    <td>Número de Solicitud</td>
                    <td><span class="folio-highlight">{{ $patentRequest->code }}</span></td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td><span class="status-rejected-obs">Rechazado con Obs.</span></td>
                </tr>
                <tr>
                    <td>Fecha de Evaluación</td>
                    <td>{{ $patentRequest->reviewed_at->format('d-m-Y H:i') }}</td>
                </tr>
                <tr>
                    <td>Solicitante</td>
                    <td>{{ $patentRequest->user->name }}</td>
                </tr>
                <tr>
                    <td>RUT</td>
                    <td>{{ $patentRequest->rut }}</td>
                </tr>
                <tr>
                    <td>Giro Comercial</td>
                    <td>{{ $patentRequest->business_activity }}</td>
                </tr>
                <tr>
                    <td>Dirección</td>
                    <td>{{ $patentRequest->business_address }}</td>
                </tr>
                <tr>
                    <td>Revisado por</td>
                    <td>{{ $patentRequest->reviewer->name }} ({{ $patentRequest->reviewer->email }})</td>
                </tr>
            </table>

            <!-- Observaciones -->
            <h2 class="section-title">Observaciones del Evaluador</h2>
            <div class="observations-box">
                <div class="observations-box-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    Comentarios y Retroalimentación
                </div>
                <div class="observations-content">{{ $patentRequest->observations }}</div>
            </div>

            <!-- Info Box -->
            <div class="info-box">
                <div class="info-box-title">¿Qué debe hacer?</div>
                <div class="info-box-text">
                    Para que su solicitud pueda ser aprobada, es necesario que regularice los aspectos mencionados 
                    en las observaciones. Una vez corregidos, podrá presentar una nueva solicitud a través de la Ventanilla Única.
                </div>
            </div>

            <div class="divider"></div>

            <!-- Pasos a seguir -->
            <h2 class="section-title">Pasos para Regularizar</h2>
            <ul class="steps-list">
                <li>Revise detenidamente todas las observaciones indicadas</li>
                <li>Regularice los aspectos mencionados según las normativas municipales</li>
                <li>Reúna la documentación que respalde las correcciones realizadas</li>
                <li>Presente una nueva solicitud a través de la Ventanilla Única</li>
                <li>Adjunte toda la documentación de respaldo en la nueva solicitud</li>
            </ul>
        </div>

        <!-- Banner Inferior (opcional - se muestra solo si existe) -->
        @if (file_exists(public_path('images/email/pie_mailing.png')))
            <img src="{{ asset('images/email/pie_mailing.png') }}" alt="Pie de página Municipalidad de Arica"
                class="banner">
        @endif

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">ILUSTRE MUNICIPALIDAD DE ARICA</div>
            <p>Ventanilla Única</p>
            <p>Este es un correo automático, por favor no responder a este mensaje.</p>
            <p>&copy; {{ date('Y') }} Ilustre Municipalidad de Arica. Todos los derechos reservados.</p>
        </div>
    </div>
</body>

</html>
