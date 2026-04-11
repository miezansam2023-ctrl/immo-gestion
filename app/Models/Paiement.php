<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Paiement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'numero',
        'contrat_id',
        'locataire_id',
        'bien_id',
        'gestionnaire_id',
        'type',
        'periode_debut',
        'periode_fin',
        'mois_annee',
        'type_selection',
        'mois_concernes',
        'montant_du',
        'montant_paye',
        'reste_a_payer',
        'date_echeance',
        'date_paiement',
        'mode_paiement',
        'reference_paiement',
        'statut',
        'jours_retard',
        'penalite',
        'numero_quittance',
        'fichier_quittance',
        'quittance_generee',
        'date_generation_quittance',
        'description',
        'notes',
        'fichier_recu',
    ];

    protected $casts = [
        'periode_debut'             => 'date',
        'periode_fin'               => 'date',
        'date_echeance'             => 'date',
        'date_paiement'             => 'date',
        'date_generation_quittance' => 'date',
        'montant_du'                => 'decimal:2',
        'montant_paye'              => 'decimal:2',
        'reste_a_payer'             => 'decimal:2',
        'penalite'                  => 'decimal:2',
        'quittance_generee'         => 'boolean',
        'mois_concernes'            => 'array',
    ];

    // ─── Relations ──────────────────────────────────────────
    public function contrat()
    {
        return $this->belongsTo(Contrat::class);
    }

    public function locataire()
    {
        return $this->belongsTo(Locataire::class);
    }

    public function bien()
    {
        return $this->belongsTo(Bien::class);
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    // ─── Accesseurs ─────────────────────────────────────────
    public function getMontantDuFormatAttribute()
    {
        return number_format($this->montant_du, 0, ',', ' ') . ' FCFA';
    }

    public function getMontantPayeFormatAttribute()
    {
        return number_format($this->montant_paye, 0, ',', ' ') . ' FCFA';
    }

    public function getResteAPayerFormatAttribute()
    {
        return number_format($this->reste_a_payer, 0, ',', ' ') . ' FCFA';
    }

    public function getStatutLibelleAttribute()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'paye'       => 'Payé',
            'partiel'    => 'Paiement partiel',
            'retard'     => 'En retard',
            'annule'     => 'Annulé',
        ];
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeClassAttribute()
    {
        $classes = [
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'paye'       => 'bg-green-100 text-green-800',
            'partiel'    => 'bg-blue-100 text-blue-800',
            'retard'     => 'bg-red-100 text-red-800',
            'annule'     => 'bg-gray-100 text-gray-800',
        ];
        return $classes[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }
    
    public function getEstEnRetardAttribute(): bool
    {
        if (!$this->date_paiement || !$this->contrat) return false;

        $jourPaiement = $this->contrat->jour_paiement ?? 5;
        // Date limite = jour_paiement du mois concerné
        $dateLimite = \Carbon\Carbon::parse($this->periode_debut)
            ->setDay(min($jourPaiement, \Carbon\Carbon::parse($this->periode_debut)->daysInMonth));

        return $this->date_paiement->gt($dateLimite);
    }

    // ─── Scopes ─────────────────────────────────────────────
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }
    public function scopePayes($query)
    {
        return $query->where('statut', 'paye');
    }
    public function scopeEnRetard($query)
    {
        return $query->where('statut', 'retard');
    }
    public function scopePartiels($query)
    {
        return $query->where('statut', 'partiel');
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('numero', 'like', "%{$search}%")
                ->orWhereHas('locataire', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenoms', 'like', "%{$search}%");
                });
        });
    }

    // ─── Méthodes ───────────────────────────────────────────
    public function estPaye()
    {
        return $this->statut === 'paye';
    }

    public function calculerJoursRetard()
    {
        if (!$this->date_echeance || !$this->date_paiement) {
            return 0;
        }

        if ($this->date_paiement->lte($this->date_echeance)) {
            return 0;
        }

        return $this->date_echeance->diffInDays($this->date_paiement);
    }

    public function genererNumero()
    {
        $lastPaiement = static::orderBy('id', 'desc')->first();
        $number = $lastPaiement ? $lastPaiement->id + 1 : 1;
        return 'PAY-' . date('Y') . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function genererNumeroQuittance()
    {
        $year = date('Y');
        $last = static::whereNotNull('numero_quittance')
            ->where('numero_quittance', 'like', "QUI-{$year}%")
            ->orderBy('id', 'desc')->first();
        $number = 1;
        if ($last && preg_match('/QUI-\d{4}-(\d+)/', $last->numero_quittance, $m)) {
            $number = intval($m[1]) + 1;
        }
        return 'QUI-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // ─── Boot ───────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paiement) {
            if (empty($paiement->numero)) {
                $paiement->numero = $paiement->genererNumero();
            }

            $paiement->reste_a_payer = max(
                0,
                $paiement->montant_du - ($paiement->montant_paye ?? 0)
            );

            // Générer quittance automatiquement pour paiement payé en totalité
            if ($paiement->statut === 'paye' && empty($paiement->numero_quittance)) {
                $paiement->quittance_generee = true;
                $paiement->numero_quittance = $paiement->genererNumeroQuittance();
                $paiement->date_generation_quittance = now()->toDateString();
            }

            // Met à jour le retard si date paiement dépassée
            $paiement->jours_retard = $paiement->calculerJoursRetard();
        });

        static::updating(function ($paiement) {
            // Si le paiement devient paye, générer une quittance si elle n'existe pas encore
            if ($paiement->isDirty('statut') && $paiement->statut === 'paye' && empty($paiement->numero_quittance)) {
                $paiement->quittance_generee = true;
                $paiement->numero_quittance = $paiement->genererNumeroQuittance();
                $paiement->date_generation_quittance = now()->toDateString();
            }

            // Calcule toujours le retard dès qu’on a une date de paiement + échéance
            $paiement->jours_retard = $paiement->calculerJoursRetard();

            // Si pas payé encore et échéance dépassée, on marque retard
            if (!$paiement->estPaye() && $paiement->date_paiement && $paiement->date_paiement->gt($paiement->date_echeance)) {
                $paiement->statut = 'retard';
            }
        });
    }
}
