<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>CONTRAT_BAIL_{{ $contrat->numero }}</title>
    <style>
        @page { 
            margin: 1.5cm 2cm; 
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12px; 
            color: #222; 
            line-height: 1.5; 
            text-align: justify; 
        }
        
        /* En-tête */
        .header { border-bottom: 2px solid #1a237e; padding-bottom: 10px; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; color: #1a237e; text-transform: uppercase; }
        .ref-box { float: right; border: 1px solid #333; padding: 5px 10px; background: #f9f9f9; font-weight: bold; font-family: monospace; }

        .main-title { text-align: center; margin: 20px 0; background: #f0f0f0; padding: 10px; border: 1px solid #333; }
        .main-title h1 { font-size: 18px; text-transform: uppercase; margin: 0; }

        /* Sections */
        .section { margin-bottom: 12px; }
        .section-title { font-weight: bold; text-transform: uppercase; border-bottom: 1px solid #ccc; margin-bottom: 8px; color: #1a237e; }
        
        /* Tableaux */
        .data-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        .data-table td { border: 1px solid #444; padding: 6px 10px; }
        .label { background-color: #f2f2f2; font-weight: bold; width: 40%; }

        /* Saut de page */
        .page-break { page-break-before: always; }

        /* Signatures */
        .signature-area { margin-top: 30px; }
        .sig-box { width: 48%; float: left; border: 1px solid #333; height: 200px; padding: 10px; text-align: center; }
        .sig-box.right { float: right; }
        .mention-manuscrite { font-size: 10px; font-style: italic; color: #666; margin-top: 5px; }
        .sig-space { height: 100px; }

        .footer { position: fixed; bottom: -0.5cm; width: 100%; text-align: center; font-size: 9px; border-top: 1px solid #ccc; padding-top: 5px; color: #555; }
        .clearfix { clear: both; }
    </style>
</head>
<body>

    <div class="header">
        <div class="ref-box">DOSSIER N° {{ $contrat->numero }}</div>
        <div class="company-name">IMMOGESTION PROFESSIONNELLE</div>
        <div style="font-size: 10px;">Mandataire Immobilier - Gestion Locative & Syndic de Copropriété</div>
    </div>

    <div class="main-title">
        <h1>CONTRAT DE BAIL À USAGE D'HABITATION</h1>
    </div>

    <div class="section">
        <div class="section-title">Article 1 - Désignation des Parties</div>
        <p><strong>LE BAILLEUR :</strong> La société <strong>IMMOGESTION</strong>, agissant en qualité de mandataire du propriétaire, dument habilitée aux présentes.</p>
        <p><strong>LE PRENEUR :</strong> <span style="text-transform: uppercase; font-weight: bold;">M./Mme {{ $contrat->locataire->nom }} {{ $contrat->locataire->prenoms }}</span>, né(e) le ________________ à ________________, de nationalité ________________.</p>
    </div>

    <div class="section">
        <div class="section-title">Article 2 - Objet et Destination</div>
        <p>Le Bailleur loue au Preneur, qui l'accepte, le bien immobilier suivant :<br>
           <strong>Type :</strong> {{ $contrat->bien->titre }} <br>
           <strong>Localisation :</strong> {{ $contrat->bien->adresse }} <br>
           Le local est strictement destiné à l'usage d'habitation. Toute activité commerciale ou professionnelle y est interdite.</p>
    </div>

    <div class="section">
        <div class="section-title">Article 3 - Durée et Prise d'Effet</div>
        <p>Le bail est conclu pour une durée de <strong>{{ $contrat->duree_mois }} mois</strong> ferme, commençant le <strong>{{ \Carbon\Carbon::parse($contrat->date_debut)->format('d/m/Y') }}</strong> pour s'achever le <strong>{{ \Carbon\Carbon::parse($contrat->date_fin)->format('d/m/Y') }}</strong>. 
        {!! $contrat->renouvellement_automatique ? 'Le bail est renouvelable par tacite reconduction.' : 'Le bail ne sera pas renouvelé automatiquement à son terme.' !!}</p>
    </div>

    <div class="section">
        <div class="section-title">Article 4 - Conditions Financières</div>
        <table class="data-table">
            <tr>
                <td class="label">Loyer Mensuel Net</td>
                <td><strong>{{ number_format($contrat->loyer_mensuel, 0, ',', ' ') }} FCFA</strong></td>
            </tr>
            <tr>
                <td class="label">Dépôt de Garantie (Caution)</td>
                <td>{{ number_format($contrat->caution, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td class="label">Frais de Dossier & Honoraires</td>
                <td>{{ number_format($contrat->frais_agence ?? 0, 0, ',', ' ') }} FCFA</td>
            </tr>
            <tr>
                <td class="label">Date d'échéance mensuelle</td>
                <td>Le <strong>{{ $contrat->jour_paiement }}</strong> de chaque mois au plus tard</td>
            </tr>
            <tr>
                <td class="label">Mode de Règlement</td>
                <td style="text-transform: capitalize;">{{ $contrat->mode_paiement }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Article 5 - Obligations du Preneur</div>
        <p>Le Preneur s'engage à : <br>
           1. Payer le loyer et les charges aux termes convenus. <br>
           2. User des locaux paisiblement selon la destination prévue. <br>
           3. Répondre des dégradations et pertes qui surviennent pendant la durée du contrat. <br>
           4. Ne procéder à aucune transformation ou travaux sans accord écrit du Bailleur.</p>
    </div>

    <div class="footer">
        Page 1/2 - Contrat de Bail {{ $contrat->numero }} - Édité le {{ $date }}
    </div>

    <div class="page-break"></div>
    
    <div class="header">
        <div class="company-name">IMMOGESTION PROFESSIONNELLE</div>
    </div>

    <div class="section">
        <div class="section-title">Article 6 - État des Lieux et Entretien</div>
        <p>Un état des lieux contradictoire est établi lors de la remise des clés le <strong>{{ $contrat->date_etat_lieux_entree ? \Carbon\Carbon::parse($contrat->date_etat_lieux_entree)->format('d/m/Y') : '....................' }}</strong>. 
        Le Preneur est tenu d'entretenir les locaux en bon état de réparations locatives.</p>
        <p><strong>Animaux :</strong> @if($contrat->animaux_autorises) La présence d'animaux domestiques est autorisée. @else La détention d'animaux est strictement interdite. @endif</p>
    </div>

    <div class="section">
        <div class="section-title">Article 7 - Clause Résolutoire</div>
        <p>À défaut de paiement d'un seul terme de loyer à son échéance, ou en cas de non-respect d'une des clauses du contrat, le présent bail sera résilié de plein droit, huit (8) jours après une mise en demeure restée infructueuse.</p>
    </div>

    @if($contrat->notes)
    <div class="section">
        <div class="section-title">Article 8 - Clauses Particulières</div>
        <div style="padding: 10px; border: 1px dashed #444; background: #fffbe6;">
            {{ $contrat->notes }}
        </div>
    </div>
    @endif

    <div class="signature-area">
        <p>Fait à ______________________, le <strong>{{ \Carbon\Carbon::parse($contrat->date_signature)->format('d/m/Y') }}</strong>, en deux exemplaires originaux.</p>
        
        <div class="sig-box">
            <strong>LE BAILLEUR (Mandataire)</strong><br>
            <div class="mention-manuscrite">Cachet et Signature précédés de la mention "Lu et approuvé"</div>
            <div class="sig-space"></div>
        </div>

        <div class="sig-box right">
            <strong>LE PRENEUR (LOCATAIRE)</strong><br>
            <div class="mention-manuscrite">Signature précédée de la mention "Lu et approuvé"</div>
            <div class="sig-space"></div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="footer">
        Page 2/2 - Document contractuel officiel généré par IMMOGESTION - Siège Social : Abidjan, Côte d'Ivoire.
    </div>

</body>
</html>