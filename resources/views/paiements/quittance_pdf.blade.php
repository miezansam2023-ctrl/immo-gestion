<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quittance_{{ $paiement->numero }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            font-size: 12px; 
            color: #1e293b; 
            margin: 0; 
            padding: 0;
            line-height: 1.5;
        }
        .container { padding: 40px; }
        
        /* En-tête */
        .header-table { width: 100%; border-bottom: 3px solid #1e293b; padding-bottom: 20px; }
        .brand { font-size: 24px; font-weight: 900; color: #1e293b; text-transform: uppercase; letter-spacing: -1px; }
        .brand span { color: #4F46E5; }
        .doc-type { text-align: right; }
        .doc-type h1 { margin: 0; font-size: 20px; font-weight: 900; text-transform: uppercase; color: #64748b; }
        .doc-type p { margin: 0; font-family: monospace; font-size: 14px; font-weight: bold; }

        /* Colonnes Clients */
        .address-table { width: 100%; margin-top: 40px; }
        .address-box { padding: 15px; border-radius: 12px; background: #f8fafc; }
        .label { font-size: 9px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .name { font-size: 14px; font-weight: bold; color: #1e293b; }

        /* Détails Paiement */
        .details-table { width: 100%; margin-top: 40px; border-collapse: collapse; }
        .details-table th { background: #1e293b; color: white; padding: 12px; text-align: left; font-size: 10px; text-transform: uppercase; }
        .details-table td { padding: 15px; border-bottom: 1px solid #e2e8f0; }
        
        /* Badge Statut Filigrane */
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

        /* Recapitulatif */
        .recap-container { margin-top: 30px; width: 100%; }
        .total-box { 
            background: #4F46E5; 
            color: white; 
            padding: 20px; 
            border-radius: 15px; 
            text-align: right; 
            float: right; 
            width: 40%;
        }
        .total-label { font-size: 10px; text-transform: uppercase; font-weight: bold; opacity: 0.8; }
        .total-amount { font-size: 22px; font-weight: 900; }

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
        .signature-space { margin-top: 40px; text-align: right; }
        .stamp { display: inline-block; border: 2px dashed #e2e8f0; padding: 20px 40px; color: #e2e8f0; font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="status-badge">PAYÉ</div>

    <div class="container">
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">IMMO<span>GESTION</span></div>
                    
                </td>
                <td class="doc-type">
                    <h1>Quittance de Loyer</h1>
                    <p>N° {{ $paiement->numero }}</p>
                </td>
            </tr>
        </table>

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
                            Désignation: {{ $paiement->bien->titre }}<br>
                            Localisation: {{ $paiement->bien->adresse }}<br>
                        </p>
                    </div>
                </td>
            </tr>
        </table>

        <table class="details-table">
            <thead>
                <tr>
                    <th width="50%">Désignation</th>
                    <th width="20%">Période</th>
                    <th width="30%" style="text-align: right;">Montant</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Loyer Principal</strong><br>
                        <span style="font-size: 10px; color: #64748b;">Paiement intégral du loyer mensuel</span>
                    </td>
                    <td>{{ $paiement->mois_annee }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 30px;">
            <div style="float: left; width: 50%;">
                <div class="label">Informations de règlement</div>
                <p style="font-size: 11px;">
                    Date d'encaissement : <strong>{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y') }}</strong><br>
                    Mode de paiement : <strong>{{ ucfirst($paiement->mode_paiement) }}</strong><br>
                    Date d'émission : {{ $date }}
                </p>
            </div>

            <div class="total-box">
                <div class="total-label">Montant Total Reçu</div>
                <div class="total-amount">{{ number_format($paiement->montant_paye, 0, ',', ' ') }} FCFA</div>
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