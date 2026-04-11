<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quittance_{{ $paiement->numero }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Helvetica', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            margin: 0;
            padding: 0;
            line-height: 1.5;
        }

        .container {
            padding: 40px;
        }

        .header-table {
            width: 100%;
            border-bottom: 3px solid #1e293b;
            padding-bottom: 20px;
        }

        .brand {
            font-size: 24px;
            font-weight: 900;
            color: #1e293b;
            text-transform: uppercase;
            letter-spacing: -1px;
        }

        .brand span {
            color: #4F46E5;
        }

        .doc-type {
            text-align: right;
        }

        .doc-type h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
            color: #64748b;
        }

        .doc-type p {
            margin: 0;
            font-family: monospace;
            font-size: 14px;
            font-weight: bold;
        }

        .address-table {
            width: 100%;
            margin-top: 40px;
        }

        .address-box {
            padding: 15px;
            border-radius: 12px;
            background: #f8fafc;
        }

        .label {
            font-size: 9px;
            font-weight: 900;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .name {
            font-size: 14px;
            font-weight: bold;
            color: #1e293b;
        }

        .details-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }

        .details-table th {
            background: #1e293b;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .details-table td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
        }

        .status-badge {
            position: absolute;
            top: 45%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            font-size: 80px;
            font-weight: 900;
            color: rgba(16, 185, 129, 0.1);
            text-transform: uppercase;
            z-index: -1;
            border: 10px solid rgba(16, 185, 129, 0.1);
            padding: 10px 30px;
            border-radius: 20px;
        }

        .total-box {
            background: #4F46E5;
            color: white;
            padding: 20px;
            border-radius: 15px;
            text-align: right;
            float: right;
            width: 40%;
        }

        .total-label {
            font-size: 10px;
            text-transform: uppercase;
            font-weight: bold;
            opacity: 0.8;
        }

        .total-amount {
            font-size: 22px;
            font-weight: 900;
        }

        .retard-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            padding: 10px 15px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 10px;
            color: #dc2626;
        }

        .mois-badge {
            display: inline-block;
            background: #eef2ff;
            color: #4338ca;
            padding: 3px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: bold;
            margin: 2px;
        }

        .footer {
            position: absolute;
            bottom: 40px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            font-size: 9px;
            color: #94a3b8;
            text-align: center;
        }

        .signature-space {
            margin-top: 40px;
            text-align: right;
        }

        .stamp {
            display: inline-block;
            border: 2px dashed #e2e8f0;
            padding: 20px 40px;
            color: #e2e8f0;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>
    <div class="status-badge">PAYÉ</div>

    <div class="container">

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">IMMO<span>GESTION</span></div>
                </td>
                <td class="doc-type">
                    <h1>Quittance de Loyer</h1>
                    <p>N° {{ $paiement->numero_quittance ?? $paiement->numero }}</p>
                    @if ($paiement->type_selection === 'multiple')
                        <p style="font-size:10px; color:#6366f1; margin-top:4px;">Paiement Multi-Mois</p>
                    @endif
                </td>
            </tr>
        </table>

        {{-- ADRESSES --}}
        <table class="address-table">
            <tr>
                <td width="45%">
                    <div class="label">Bailleur / Mandataire</div>
                    <div class="address-box">
                        <div class="name">IMMOGESTION SARL</div>
                        <p style="margin: 0; color: #64748b;">
                            Plateau, Avenue Marchand<br>
                            01 BP 4552 Abidjan 01<br>
                            Contact: +225 07 00 00 00 00
                        </p>
                    </div>
                </td>
                <td width="10%"></td>
                <td width="45%">
                    <div class="label">Locataire</div>
                    <div class="address-box">
                        <div class="name">{{ $paiement->locataire->nom }} {{ $paiement->locataire->prenoms }}</div>
                        <p style="margin: 0; color: #64748b;">
                            Contact : {{ $paiement->locataire->telephone }}<br>
                            Bien : {{ $paiement->bien->titre }}<br>
                            Adresse : {{ $paiement->bien->commune }}, {{ $paiement->bien->adresse }}<br>
                            Contrat : {{ $paiement->contrat->numero }}
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        {{-- TABLEAU DES LIGNES --}}
        <table class="details-table">
            <thead>
                <tr>
                    <th width="45%">Désignation</th>
                    <th width="30%">Période</th>
                    <th width="25%" style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                @if ($paiement->type_selection === 'multiple' && !empty($paiement->mois_concernes))
                    @php
                        $moisFrMap = [
                            'January' => 'Janvier',
                            'February' => 'Février',
                            'March' => 'Mars',
                            'April' => 'Avril',
                            'May' => 'Mai',
                            'June' => 'Juin',
                            'July' => 'Juillet',
                            'August' => 'Août',
                            'September' => 'Septembre',
                            'October' => 'Octobre',
                            'November' => 'Novembre',
                            'December' => 'Décembre',
                        ];
                        $totalMulti = $paiement->montant_paye * count($paiement->mois_concernes);
                        $descriptionPaiement = ($paiement->statut === 'paye' || $paiement->montant_paye >= $paiement->montant_du)
                            ? 'Paiement intégral du loyer mensuel'
                            : 'Paiement partiel du loyer mensuel';
                    @endphp
                    @foreach ($paiement->mois_concernes as $moisRaw)
                        @php
                            $d = \Carbon\Carbon::parse($moisRaw . '-01');
                            $moisNom = ($moisFrMap[$d->format('F')] ?? $d->format('F')) . ' ' . $d->format('Y');
                        @endphp
                        <tr>
                            <td>
                                <strong>Loyer Principal</strong><br>
                                <span style="font-size: 10px; color: #64748b;">{{ $descriptionPaiement }}</span>
                            </td>
                            <td>{{ $moisNom }}</td>
                            <td style="text-align: right; font-weight: bold;">
                                {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                    @endforeach
                @else
                    @php
                        $descriptionPaiement = ($paiement->statut === 'paye' || $paiement->montant_paye >= $paiement->montant_du)
                            ? 'Paiement intégral du loyer mensuel'
                            : 'Paiement partiel du loyer mensuel';
                    @endphp
                    <tr>
                        <td>
                            <strong>Loyer Principal</strong><br>
                            <span style="font-size: 10px; color: #64748b;">{{ $descriptionPaiement }}</span>
                        </td>
                        <td>{{ $paiement->mois_annee }}</td>
                        <td style="text-align: right; font-weight: bold;">
                            {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        {{-- INFOS RÈGLEMENT --}}
        <div style="margin-top: 30px;">
            <div style="float: left; width: 55%;">
                <div class="label">Informations de règlement</div>
                <p style="font-size: 11px;">
                    Date d'encaissement :
                    <strong>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</strong><br>
                    Mode de paiement :
                    <strong>{{ ucwords(str_replace('_', ' ', $paiement->mode_paiement)) }}</strong><br>
                    @if ($paiement->reference_paiement)
                        Référence : <strong>{{ $paiement->reference_paiement }}</strong><br>
                    @endif
                    Jour de paiement contractuel : <strong>le {{ $paiement->contrat->jour_paiement ?? 5 }} du
                        mois</strong><br>
                    Date d'émission : {{ $date }}
                </p>

                {{-- Retard --}}
                @if ($paiement->est_en_retard)
                    @php
                        $jourP = $paiement->contrat->jour_paiement ?? 5;
                        $dateLimite = \Carbon\Carbon::parse($paiement->periode_debut)->setDay(
                            min($jourP, \Carbon\Carbon::parse($paiement->periode_debut)->daysInMonth),
                        );
                        $joursRetard = $dateLimite->diffInDays($paiement->date_paiement);
                    @endphp
                    <div class="retard-box">
                        Paiement effectué avec {{ $joursRetard }} jour(s) de retard<br>
                        (Échéance contractuelle : {{ $dateLimite->format('d/m/Y') }})
                    </div>
                @endif

                {{--  Alerte paiement partiel --}}
                @if ($paiement->statut === 'partiel' && $paiement->reste_a_payer > 0)
                    <div
                        style="margin-top: 10px; background: #fff7ed; border: 1px solid #fed7aa; padding: 10px 15px; border-radius: 8px; font-size: 10px; color: #c2410c;">
                        Paiement PARTIEL — Solde restant dû :
                        <strong>{{ number_format($paiement->reste_a_payer, 0, ',', ' ') }} FCFA</strong>
                    </div>
                @endif
            </div>

            {{-- Boîte montant --}}
            @php
                $isMulti = $paiement->type_selection === 'multiple' && !empty($paiement->mois_concernes);
                $montantTotal = $isMulti
                    ? $paiement->montant_paye * count($paiement->mois_concernes)
                    : $paiement->montant_paye;
                $montantDuTotal = $isMulti
                    ? $paiement->montant_du * count($paiement->mois_concernes)
                    : $paiement->montant_du;
                $isPartiel = $paiement->statut === 'partiel';
            @endphp

            <div class="total-box" style="background: {{ $isPartiel ? '#ea580c' : '#4F46E5' }};">
                <div class="total-label">
                    {{ $isPartiel ? 'Montant Partiel Reçu' : 'Montant Total Reçu' }}
                </div>
                <div class="total-amount">
                    {{ number_format($montantTotal, 0, ',', ' ') }} FCFA
                </div>
                @if ($isPartiel)
                    <div
                        style="font-size: 10px; opacity: 0.9; margin-top: 6px; border-top: 1px solid rgba(255,255,255,0.3); padding-top: 6px;">
                        Loyer dû : {{ number_format($montantDuTotal, 0, ',', ' ') }} FCFA<br>
                        Reste à payer : <strong>{{ number_format($paiement->reste_a_payer, 0, ',', ' ') }}
                            FCFA</strong>
                    </div>
                @elseif($isMulti)
                    <div style="font-size: 10px; opacity: 0.8; margin-top: 5px;">
                        {{ count($paiement->mois_concernes) }} mois ×
                        {{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA
                    </div>
                @endif
            </div>
            <div style="clear: both;"></div>
        </div>

        <div class="signature-space">
            <p style="font-size: 11px; margin-bottom: 10px;">Le Mandataire,</p>
            <div class="stamp">Cachet & Signature</div>
        </div>

        <div class="footer">
            <i>Généré électroniquement le {{ now()->format('d/m/Y H:i') }}</i>
        </div>
    </div>
</body>

</html>
