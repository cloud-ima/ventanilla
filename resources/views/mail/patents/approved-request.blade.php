<!DOCTYPE html>
<html lang="es-CL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante de Aprobación de Patente</title>
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
            border-bottom: 3px solid #06048c;
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
            color: #06048c;
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
            background-color: #06048c;
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
            color: #06048c;
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
            background-color: #06048c;
            color: #ffffff;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: 700;
            font-size: 14px;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid #06048c;
            padding: 20px;
            margin: 30px 0;
        }

        .info-box-title {
            font-size: 13px;
            font-weight: 600;
            color: #06048c;
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
            color: #06048c;
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
            color: #06048c;
            margin-bottom: 10px;
        }

        .divider {
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        .status-approved {
            background-color: #28a745;
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
                            <h1>Comprobante de Aprobación de Patente</h1>
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
                Le informamos que su solicitud de patente ha sido <strong>aprobada</strong> exitosamente.
                A continuación, detallamos la información de su patente aprobada el día {{ $patentRequest->reviewed_at->format('d-m-Y H:i') }}.
            </p>

            <!-- Datos de la Patente -->
            <h2 class="section-title">Datos de la Patente</h2>
            <table class="data-table">
                <tr>
                    <td>Número de Solicitud</td>
                    <td><span class="folio-highlight">{{ $patentRequest->code }}</span></td>
                </tr>
                <tr>
                    <td>Estado</td>
                    <td><span class="status-approved">Aprobada</span></td>
                </tr>
                <tr>
                    <td>Fecha de Aprobación</td>
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
                    <td>{{ $patentRequest->address }}, {{ $patentRequest->commune }}</td>
                </tr>
                <tr>
                    <td>Revisado por</td>
                    <td>{{ $patentRequest->reviewer->name }} ({{ $patentRequest->reviewer->email }})</td>
                </tr>
            </table>

            <!-- Formularios Asociados -->
            @if ($patentRequest->forms->count() > 0)
                <h2 class="section-title">Formularios Asociados</h2>
                <table class="data-table">
                    @foreach ($patentRequest->forms as $form)
                        <tr>
                            <td>{{ $form->patentForm->name }}</td>
                            <td>{{ $form->created_at->format('d-m-Y') }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <!-- Requerimientos Documentales -->
            @if ($patentRequest->requirements->count() > 0)
                <h2 class="section-title">Requerimientos Documentales</h2>
                <table class="data-table">
                    @foreach ($patentRequest->requirements as $requirement)
                        <tr>
                            <td>{{ $requirement->patentRequirement->name }}</td>
                            <td>{{ $requirement->patentRequirement->where_to_obtain }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif

            <!-- Info Box -->
            <div class="info-box">
                <div class="info-box-title">Importante: Conserve su número de solicitud</div>
                <div class="info-box-text">
                    El código <strong>{{ $patentRequest->code }}</strong> es el número de identificación único de su patente.
                    Le recomendamos guardarlo para cualquier consulta futura sobre su patente comercial.
                </div>
            </div>

            <div class="divider"></div>

            <!-- Próximos Pasos -->
            <h2 class="section-title">¿Qué sucede ahora?</h2>
            <ul class="steps-list">
                <li>Descargue los formularios asociados desde su correo electrónico</li>
                <li>Reúna los requisitos documentales necesarios según la lista proporcionada</li>
                <li>Presente toda la documentación en la Dirección de Rentas Municipales de forma presencial</li>
                <li>Siga las instrucciones del personal de Rentas para completar el trámite</li>
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
