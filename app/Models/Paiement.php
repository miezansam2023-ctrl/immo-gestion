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
        'periode_debut' => 'date',
        'periode_fin' => 'date',
        'date_echeance' => 'date',
        'date_paiement' => 'date',
        'date_generation_quittance' => 'date',
        'montant_du' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'reste_a_payer' => 'decimal:2',
        'penalite' => 'decimal:2',
        'quittance_generee' => 'boolean',
    ];

    // Relations
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

    // Accesseurs
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

    public function getPenaliteFormatAttribute()
    {
        return number_format($this->penalite, 0, ',', ' ') . ' FCFA';
    }

    public function getTypeLibelleAttribute()
    {
        $types = [
            'loyer' => 'Loyer',
            'caution' => 'Caution',
            'charges' => 'Charges',
            'eau' => 'Eau',
            'electricite' => 'Électricité',
            'frais_agence' => 'Frais d\'agence',
            'reparation' => 'Réparation',
            'penalite' => 'Pénalité',
            'autre' => 'Autre',
        ];
        
        return $types[$this->type] ?? $this->type;
    }

    public function getStatutLibelleAttribute()
    {
        $statuts = [
            'en_attente' => 'En attente',
            'paye' => 'Payé',
            'partiel' => 'Paiement partiel',
            'retard' => 'En retard',
            'annule' => 'Annulé',
        ];
        
        return $statuts[$this->statut] ?? $this->statut;
    }

    public function getStatutBadgeClassAttribute()
    {
        $classes = [
            'en_attente' => 'bg-yellow-100 text-yellow-800',
            'paye' => 'bg-green-100 text-green-800',
            'partiel' => 'bg-blue-100 text-blue-800',
            'retard' => 'bg-red-100 text-red-800',
            'annule' => 'bg-gray-100 text-gray-800',
        ];
        
        return $classes[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    // Scopes
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

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeLoyers($query)
    {
        return $query->where('type', 'loyer');
    }

    public function scopeParMois($query, $mois, $annee = null)
    {
        if ($annee) {
            return $query->where('mois_annee', "{$mois} {$annee}");
        }
        return $query->where('mois_annee', 'like', "{$mois}%");
    }

    public function scopeParAnnee($query, $annee)
    {
        return $query->where('mois_annee', 'like', "%{$annee}");
    }

    public function scopeRecherche($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('numero', 'like', "%{$search}%")
              ->orWhere('reference_paiement', 'like', "%{$search}%")
              ->orWhereHas('locataire', function($q) use ($search) {
                  $q->where('nom', 'like', "%{$search}%")
                    ->orWhere('prenoms', 'like', "%{$search}%");
              });
        });
    }

    // Méthodes
    public function estPaye()
    {
        return $this->statut === 'paye';
    }

    public function estEnRetard()
    {
        return $this->statut === 'retard' || 
               (!$this->estPaye() && $this->date_echeance && $this->date_echeance->isPast());
    }

    public function calculerJoursRetard()
    {
        if ($this->estPaye() || !$this->date_echeance) {
            return 0;
        }
        
        $datePaiement = $this->date_paiement ?? now();
        
        if ($datePaiement->gt($this->date_echeance)) {
            return $this->date_echeance->diffInDays($datePaiement);
        }
        
        return 0;
    }

    public function calculerPenalite($tauxParJour = 100)
    {
        $joursRetard = $this->calculerJoursRetard();
        
        if ($joursRetard > 0) {
            return $joursRetard * $tauxParJour;
        }
        
        return 0;
    }

    public function marquerCommePaye($montant = null, $modePaiement = null, $reference = null)
    {
        $this->montant_paye = $montant ?? $this->montant_du;
        $this->date_paiement = now();
        $this->mode_paiement = $modePaiement ?? $this->mode_paiement;
        $this->reference_paiement = $reference;
        
        $this->reste_a_payer = $this->montant_du - $this->montant_paye;
        
        if ($this->reste_a_payer <= 0) {
            $this->statut = 'paye';
        } else {
            $this->statut = 'partiel';
        }
        
        // Calculer les pénalités
        $this->jours_retard = $this->calculerJoursRetard();
        if ($this->jours_retard > 0) {
            $this->penalite = $this->calculerPenalite();
        }
        
        $this->save();
        
        return $this;
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
        $lastQuittance = static::whereNotNull('numero_quittance')
                              ->where('numero_quittance', 'like', "QUI-{$year}%")
                              ->orderBy('id', 'desc')
                              ->first();
        
        $number = 1;
        if ($lastQuittance && preg_match('/QUI-\d{4}-(\d+)/', $lastQuittance->numero_quittance, $matches)) {
            $number = intval($matches[1]) + 1;
        }
        
        return 'QUI-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    // Boot method
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($paiement) {
            if (empty($paiement->numero)) {
                $paiement->numero = $paiement->genererNumero();
            }
            
            if ($paiement->montant_paye) {
                $paiement->reste_a_payer = $paiement->montant_du - $paiement->montant_paye;
            } else {
                $paiement->reste_a_payer = $paiement->montant_du;
            }
        });

        static::updating(function ($paiement) {
            // Vérifier le retard
            if (!$paiement->estPaye() && $paiement->date_echeance && $paiement->date_echeance->isPast()) {
                $paiement->statut = 'retard';
                $paiement->jours_retard = $paiement->calculerJoursRetard();
            }
        });
    }
}